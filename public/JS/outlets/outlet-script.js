function outletData() {
  return {
    outlets: [],
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
    outletIdToDelete: null,

    init() {
      this.fetchOutlets();
    },

    async fetchOutlets() {
      try {
        const query = `
          query {
            outlets {
              outlet_id
              outlet_name
              address
              phone_number
              latitude
              longitude
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

        let list = result.data.outlets || [];

        // Filter by status
        if (this.status !== '') {
          const isActiveBool = this.status === '1';
          list = list.filter(o => o.is_active === isActiveBool);
        }

        // Filter by search (name or address or phone)
        if (this.search.trim() !== '') {
          const s = this.search.toLowerCase();
          list = list.filter(o =>
            o.outlet_name.toLowerCase().includes(s) ||
            (o.address && o.address.toLowerCase().includes(s)) ||
            (o.phone_number && o.phone_number.toLowerCase().includes(s))
          );
        }

        this.pagination.total = list.length;
        this.pagination.last_page = Math.ceil(this.pagination.total / this.pagination.per_page);

        const start = (this.pagination.current_page - 1) * this.pagination.per_page;
        const end = start + this.pagination.per_page;

        this.outlets = list.slice(start, end);

        this.pagination.from = this.pagination.total === 0 ? 0 : start + 1;
        this.pagination.to = Math.min(end, this.pagination.total);

      } catch (error) {
        console.error('Error fetching outlets:', error);
      }
    },

    async changePage(page) {
      if (page === '...' || isNaN(page)) return;
      this.pagination.current_page = parseInt(page);
      await this.fetchOutlets();
    },

    async previousPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        await this.fetchOutlets();
      }
    },

    async nextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.pagination.current_page++;
        await this.fetchOutlets();
      }
    },

    async resetFilters() {
      this.search = '';
      this.status = '';
      this.pagination.current_page = 1;
      await this.fetchOutlets();
    },

    confirmDelete(id) {
      this.outletIdToDelete = id;
      this.showDeleteModal = true;
    },

    async deleteOutlet() {
      try {
        const mutation = `
          mutation($outlet_id: ID!) {
            deleteOutlet(outlet_id: $outlet_id) {
              outlet_id
            }
          }
        `;

        const variables = { outlet_id: this.outletIdToDelete };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query: mutation, variables }),
        });

        const result = await response.json();

        if (result.data?.deleteOutlet?.outlet_id) {
          this.showDeleteModal = false;
          this.outletIdToDelete = null;
          await this.fetchOutlets();
        } else {
          console.error('Failed to delete outlet.');
        }
      } catch (error) {
        console.error('Error deleting outlet:', error);
      }
    },
  };
}
