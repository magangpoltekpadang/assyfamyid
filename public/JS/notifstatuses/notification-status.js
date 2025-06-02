document.addEventListener("DOMContentLoaded", function () {
  const endpoint = "/graphql";

  async function executeQuery(query, variables = {}) {
    try {
      const response = await fetch(endpoint, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ query, variables }),
      });
      return await response.json();
    } catch (error) {
      console.error("GraphQL Error:", error);
      return { errors: [error] };
    }
  }

  Alpine.data("notificationStatusData", () => ({
    notificationStatuses: [],
    pagination: {
      current_page: 1,
      per_page: 10,
      total: 0,
      last_page: 1,
      has_more: false,
    },
    search: '',
    showDeleteModal: false,
    notificationStatusIdToDelete: null,
    loading: false,
    error: null,

    async init() {
      await this.fetchNotificationStatuses();
    },

    async fetchNotificationStatuses() {
      this.loading = true;
      this.error = null;

      const query = `
        query GetNotificationStatuses($page: Int, $perPage: Int, $search: String) {
          notificationStatuses(page: $page, perPage: $perPage, search: $search) {
            data {
              status_id
              status_name
              code
              description
              created_at
              updated_at
            }
            paginatorInfo {
              currentPage
              lastPage
              perPage
              total
              hasMorePages
            }
          }
        }
      `;

      const variables = {
        page: this.pagination.current_page,
        perPage: this.pagination.per_page,
        search: this.search || null,
      };

      const result = await executeQuery(query, variables);

      if (result.errors) {
        this.error = result.errors[0].message;
        console.error("GraphQL Error:", result.errors);
      } else {
        this.notificationStatuses = result.data.notificationStatuses.data;
        const info = result.data.notificationStatuses.paginatorInfo;
        this.pagination = {
          current_page: info.currentPage,
          last_page: info.lastPage,
          per_page: info.perPage,
          total: info.total,
          has_more: info.hasMorePages,
        };
      }

      this.loading = false;
    },

    async deleteNotificationStatus() {
      if (!this.notificationStatusIdToDelete) return;

      const mutation = `
        mutation DeleteNotificationStatus($status_id: ID!) {
          deleteNotificationStatus(status_id: $status_id) {
            status_id
            status_name
          }
        }
      `;

      const variables = {
        status_id: this.notificationStatusIdToDelete,
      };

      const result = await executeQuery(mutation, variables);

      if (result.errors) {
        this.error = result.errors[0].message;
        console.error("GraphQL Error:", result.errors);
      } else {
        this.showDeleteModal = false;
        this.notificationStatusIdToDelete = null;
        await this.fetchNotificationStatuses();
      }
    },

    async changePage(page) {
      if (page === "...") return;
      this.pagination.current_page = parseInt(page);
      await this.fetchNotificationStatuses();
    },

    async previousPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        await this.fetchNotificationStatuses();
      }
    },

    async nextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.pagination.current_page++;
        await this.fetchNotificationStatuses();
      }
    },

    async resetFilters() {
      this.search = '';
      this.pagination.current_page = 1;
      await this.fetchNotificationStatuses();
    },

    confirmDelete(id) {
      this.notificationStatusIdToDelete = id;
      this.showDeleteModal = true;
    },
  }));

  Alpine.start();
});
