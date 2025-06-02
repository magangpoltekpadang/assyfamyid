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

  Alpine.data("notificationTypeData", () => ({
    notificationTypes: [],
    pagination: {
      current_page: 1,
      per_page: 10,
      total: 0,
      last_page: 1,
      has_more: false,
    },
    search: '',
    isActiveFilter: '',
    showDeleteModal: false,
    notificationTypeIdToDelete: null,
    loading: false,
    error: null,

    async init() {
      await this.fetchNotificationTypes();
    },

    async fetchNotificationTypes() {
      this.loading = true;
      this.error = null;

      const query = `
        query GetNotificationTypes($page: Int, $perPage: Int, $search: String, $is_active: Boolean) {
          notificationTypes(page: $page, perPage: $perPage, search: $search, is_active: $is_active) {
            data {
              notification_type_id
              type_name
              code
              template_text
              is_active
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

      if (this.isActiveFilter !== '') {
        variables.is_active = this.isActiveFilter === '1';
      }

      const result = await executeQuery(query, variables);

      if (result.errors) {
        this.error = result.errors[0].message;
        console.error("GraphQL Error:", result.errors);
      } else {
        this.notificationTypes = result.data.notificationTypes.data;
        const info = result.data.notificationTypes.paginatorInfo;
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

    async deleteNotificationType() {
      if (!this.notificationTypeIdToDelete) return;

      const mutation = `
        mutation DeleteNotificationType($notification_type_id: ID!) {
          deleteNotificationType(notification_type_id: $notification_type_id) {
            notification_type_id
            type_name
          }
        }
      `;

      const variables = {
        notification_type_id: this.notificationTypeIdToDelete,
      };

      const result = await executeQuery(mutation, variables);

      if (result.errors) {
        this.error = result.errors[0].message;
        console.error("GraphQL Error:", result.errors);
      } else {
        this.showDeleteModal = false;
        this.notificationTypeIdToDelete = null;
        await this.fetchNotificationTypes();
      }
    },

    async changePage(page) {
      if (page === "...") return;
      this.pagination.current_page = parseInt(page);
      await this.fetchNotificationTypes();
    },

    async previousPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        await this.fetchNotificationTypes();
      }
    },

    async nextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.pagination.current_page++;
        await this.fetchNotificationTypes();
      }
    },

    async resetFilters() {
      this.search = '';
      this.isActiveFilter = '';
      this.pagination.current_page = 1;
      await this.fetchNotificationTypes();
    },

    confirmDelete(id) {
      this.notificationTypeIdToDelete = id;
      this.showDeleteModal = true;
    },
  }));

  Alpine.start();
});
