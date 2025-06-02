function paymentStatusData() {
  return {
    paymentStatuses: [],
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0,
    },
    search: '',
    showDeleteModal: false,
    paymentStatusIdToDelete: null,

    init() {
      this.fetchPaymentStatuses();
    },

    async fetchPaymentStatuses() {
      try {
        const query = `
          query {
            paymentStatuses {
              payment_status_id
              status_name
              code
              description
              created_at
              updated_at
            }
          }
        `;

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query })
        });

        const result = await response.json();
        if (result.errors) {
          console.error('GraphQL errors:', result.errors);
          return;
        }

        let allStatuses = result.data.paymentStatuses || [];

        // Filter pencarian
        if (this.search.trim()) {
          const keyword = this.search.toLowerCase();
          allStatuses = allStatuses.filter(s =>
            (s.status_name && s.status_name.toLowerCase().includes(keyword)) ||
            (s.code && s.code.toLowerCase().includes(keyword)) ||
            (s.description && s.description.toLowerCase().includes(keyword))
          );
        }

        // Urutkan berdasarkan created_at desc (jika tersedia)
        // allStatuses.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        // Manual pagination
        const start = (this.pagination.current_page - 1) * this.pagination.per_page;
        const end = start + this.pagination.per_page;
        const paginated = allStatuses.slice(start, end);

        this.paymentStatuses = paginated;
        this.pagination.total = allStatuses.length;
        this.pagination.last_page = Math.max(Math.ceil(this.pagination.total / this.pagination.per_page), 1);
        this.pagination.from = paginated.length > 0 ? start + 1 : 0;
        this.pagination.to = start + paginated.length;

      } catch (error) {
        console.error('Error fetching payment statuses:', error);
      }
    },

    async changePage(page) {
      if (page === '...' || isNaN(page)) return;
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
    },

    async deletePaymentStatus() {
      try {
        const mutation = `
          mutation($payment_status_id: ID!) {
            deletePaymentStatus(payment_status_id: $payment_status_id) {
              payment_status_id
            }
          }
        `;

        const variables = {
          payment_status_id: this.paymentStatusIdToDelete
        };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query: mutation, variables })
        });

        const result = await response.json();
        if (result.data?.deletePaymentStatus?.payment_status_id) {
          this.showDeleteModal = false;
          this.paymentStatusIdToDelete = null;
          await this.fetchPaymentStatuses();
        } else {
          console.error('Failed to delete payment status.');
        }
      } catch (error) {
        console.error('Error deleting payment status:', error);
      }
    }
  };
}
