document.addEventListener("DOMContentLoaded", function () {
  const endpoint = "/graphql";

  async function executeQuery(query, variables = {}) {
    try {
      const response = await fetch(endpoint, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
        },
        body: JSON.stringify({
          query: query,
          variables: variables,
        }),
      });
      return await response.json();
    } catch (error) {
      console.error("GraphQL Error:", error);
      return { errors: [error] };
    }
  }

  Alpine.data("paymentStatusData", () => ({
    paymentStatuses: [],
    pagination: {
      current_page: 1,
      per_page: 10,
      total: 0,
      last_page: 1,
      has_more: false,
    },
    search: '',
    showDeleteModal: false,
    paymentStatusIdToDelete: null,
    loading: false,
    error: null,

    async init() {
      await this.fetchPaymentStatuses();
    },

    async fetchPaymentStatuses() {
      this.loading = true;
      this.error = null;

      const query = `
        query GetPaymentStatuses($page: Int, $perPage: Int, $search: String) {
          paymentStatuses(page: $page, perPage: $perPage, search: $search) {
            data {
              payment_status_id
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
        this.paymentStatuses = result.data.paymentStatuses.data;
        const info = result.data.paymentStatuses.paginatorInfo;
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

    async deletePaymentStatus() {
      if (!this.paymentStatusIdToDelete) return;

      const mutation = `
        mutation DeletePaymentStatus($payment_status_id: ID!) {
          deletePaymentStatus(payment_status_id: $payment_status_id) {
            payment_status_id
            status_name
          }
        }
      `;

      const variables = {
        payment_status_id: this.paymentStatusIdToDelete,
      };

      const result = await executeQuery(mutation, variables);

      if (result.errors) {
        this.error = result.errors[0].message;
        console.error("GraphQL Error:", result.errors);
      } else {
        this.showDeleteModal = false;
        await this.fetchPaymentStatuses();
      }
    },

    async changePage(page) {
      if (page === "...") return;
      this.pagination.current_page = parseInt(page);
      await this.fetchPaymentStatuses();
    },

    async previousPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        await this.fetchPaymentStatuses();
      }
    },

    async nextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.pagination.current_page++;
        await this.fetchPaymentStatuses();
      }
    },

    async resetFilters() {
      this.search = '';
      this.pagination.current_page = 1;
      await this.fetchPaymentStatuses();
    },

    confirmDelete(id) {
      this.paymentStatusIdToDelete = id;
      this.showDeleteModal = true;
    }
  }));

  Alpine.start();
});
