function paymentMethodData() {
  return {
    paymentMethods: [],
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 5,
      total: 0,
      from: 0,
      to: 0,
    },
    search: '',
    status: '', // '1' untuk aktif, '0' untuk nonaktif, '' semua
    showDeleteModal: false,
    paymentMethodIdToDelete: null,

    init() {
      this.fetchPaymentMethods();
    },

    async fetchPaymentMethods() {
      try {
        const query = `
          query {
            paymentMethods {
              payment_method_id
              method_name
              code
              is_active
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

        let methods = result.data.paymentMethods || [];

        // Filter by status (is_active)
        if (this.status !== '') {
          const isActiveBool = this.status === '1';
          methods = methods.filter(m => m.is_active === isActiveBool);
        }

        // Filter by search on method_name or code
        if (this.search.trim() !== '') {
          const searchLower = this.search.toLowerCase();
          methods = methods.filter(m =>
            m.method_name.toLowerCase().includes(searchLower) ||
            (m.code && m.code.toLowerCase().includes(searchLower))
          );
        }

        // Pagination calculation
        this.pagination.total = methods.length;
        this.pagination.last_page = Math.ceil(this.pagination.total / this.pagination.per_page);

        const start = (this.pagination.current_page - 1) * this.pagination.per_page;
        const end = start + this.pagination.per_page;

        this.paymentMethods = methods.slice(start, end);

        this.pagination.from = this.pagination.total === 0 ? 0 : start + 1;
        this.pagination.to = Math.min(end, this.pagination.total);

      } catch (error) {
        console.error('Error fetching payment methods:', error);
      }
    },

    async changePage(page) {
      if (page === '...' || isNaN(page)) return;
      this.pagination.current_page = parseInt(page);
      await this.fetchPaymentMethods();
    },

    async previousPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        await this.fetchPaymentMethods();
      }
    },

    async nextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.pagination.current_page++;
        await this.fetchPaymentMethods();
      }
    },

    async resetFilters() {
      this.search = '';
      this.status = '';
      this.pagination.current_page = 1;
      await this.fetchPaymentMethods();
    },

    confirmDelete(id) {
      this.paymentMethodIdToDelete = id;
      this.showDeleteModal = true;
    },

    async deletePaymentMethod() {
      try {
        const mutation = `
          mutation($payment_method_id: ID!) {
            deletePaymentMethod(payment_method_id: $payment_method_id) {
              payment_method_id
            }
          }
        `;

        const variables = { payment_method_id: this.paymentMethodIdToDelete };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query: mutation, variables }),
        });

        const result = await response.json();

        if (result.data?.deletePaymentMethod?.payment_method_id) {
          this.showDeleteModal = false;
          this.paymentMethodIdToDelete = null;
          await this.fetchPaymentMethods();
        } else {
          console.error('Failed to delete payment method.');
        }
      } catch (error) {
        console.error('Error deleting payment method:', error);
      }
    },
  };
}
