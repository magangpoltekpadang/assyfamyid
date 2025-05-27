function customerData() {
  return {
    customers: [],
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0,
      links: [],
    },
    search: '',
    isMemberFilter: '',
    showDeleteModal: false,
    customerIdToDelete: null,

    init() {
      this.fetchCustomers();
    },

    async fetchCustomers() {
      try {
        const query = `
          query($search: String, $is_member: Boolean) {
            customers(search: $search, is_member: $is_member) {
              customer_id
              name
              plate_number
              phone_number
              is_member
            }
          }
        `;

        const variables = {
          search: this.search || null,
          is_member: this.isMemberFilter === '' ? null : this.isMemberFilter === '1',
        };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query, variables }),
        });

        const result = await response.json();

        if (result.errors) {
          console.error('GraphQL errors:', result.errors);
          this.customers = [];
          return;
        }

        this.customers = result.data.customers || [];

        // Manual filter jika backend belum support filter (optional)
        if (this.isMemberFilter !== '') {
          const isMemberBool = this.isMemberFilter === '1';
          this.customers = this.customers.filter(c => c.is_member === isMemberBool);
        }
        if (this.search) {
          const lowerSearch = this.search.toLowerCase();
          this.customers = this.customers.filter(c =>
            (c.name && c.name.toLowerCase().includes(lowerSearch)) ||
            (c.plate_number && c.plate_number.toLowerCase().includes(lowerSearch)) ||
            (c.phone_number && c.phone_number.toLowerCase().includes(lowerSearch))
          );
        }

        // Stats count
        this.pagination.total = this.customers.length;
        this.pagination.last_page = 1; // kalau backend belum support pagination
        this.pagination.from = this.customers.length > 0 ? 1 : 0;
        this.pagination.to = this.customers.length;

        // Stats
        this.totalCustomers = this.customers.length;
        this.memberCustomers = this.customers.filter(c => c.is_member).length;
        this.nonMemberCustomers = this.customers.filter(c => !c.is_member).length;

      } catch (error) {
        console.error('Fetch error:', error);
        this.customers = [];
      }
    },

    async resetFilters() {
      this.search = '';
      this.isMemberFilter = '';
      this.pagination.current_page = 1;
      await this.fetchCustomers();
    },

    confirmDelete(id) {
      this.customerIdToDelete = id;
      this.showDeleteModal = true;
    },

    async deleteCustomer() {
      try {
        const mutation = `
          mutation($customer_id: ID!) {
            deleteCustomer(customer_id: $customer_id) {
              customer_id
            }
          }
        `;

        const variables = {
          customer_id: this.customerIdToDelete,
        };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query: mutation, variables }),
        });

        const result = await response.json();

        if (result.data?.deleteCustomer?.customer_id) {
          this.showDeleteModal = false;
          this.customerIdToDelete = null;
          await this.fetchCustomers();
        } else {
          console.error('Failed to delete customer.');
        }
      } catch (error) {
        console.error('Error deleting customer:', error);
      }
    },
  };
}
