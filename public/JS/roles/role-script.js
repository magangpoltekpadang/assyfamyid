function roleData() {
  return {
    roles: [],
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
    roleIdToDelete: null,

    init() {
      this.fetchRoles();
    },

    async fetchRoles() {
      try {
        const query = `
          query {
            roles {
              role_id
              role_name
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

        let allRoles = result.data.roles || [];

        // Filter pencarian
        if (this.search.trim()) {
          const keyword = this.search.toLowerCase();
          allRoles = allRoles.filter(r =>
            (r.role_name && r.role_name.toLowerCase().includes(keyword)) ||
            (r.code && r.code.toLowerCase().includes(keyword)) ||
            (r.description && r.description.toLowerCase().includes(keyword))
          );
        }

        // Manual pagination
        const start = (this.pagination.current_page - 1) * this.pagination.per_page;
        const end = start + this.pagination.per_page;
        const paginated = allRoles.slice(start, end);

        this.roles = paginated;
        this.pagination.total = allRoles.length;
        this.pagination.last_page = Math.max(Math.ceil(this.pagination.total / this.pagination.per_page), 1);
        this.pagination.from = paginated.length > 0 ? start + 1 : 0;
        this.pagination.to = start + paginated.length;

      } catch (error) {
        console.error('Error fetching roles:', error);
      }
    },

    async changePage(page) {
      if (page === '...' || isNaN(page)) return;
      this.pagination.current_page = parseInt(page);
      await this.fetchRoles();
    },

    async previousPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        await this.fetchRoles();
      }
    },

    async nextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.pagination.current_page++;
        await this.fetchRoles();
      }
    },

    async resetFilters() {
      this.search = '';
      this.pagination.current_page = 1;
      await this.fetchRoles();
    },

    confirmDelete(id) {
      this.roleIdToDelete = id;
      this.showDeleteModal = true;
    },

    async deleteRole() {
      try {
        const mutation = `
          mutation($role_id: ID!) {
            deleteRole(role_id: $role_id) {
              role_id
            }
          }
        `;

        const variables = {
          role_id: this.roleIdToDelete
        };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query: mutation, variables })
        });

        const result = await response.json();
        if (result.data?.deleteRole?.role_id) {
          this.showDeleteModal = false;
          this.roleIdToDelete = null;
          await this.fetchRoles();
        } else {
          console.error('Failed to delete role.');
        }
      } catch (error) {
        console.error('Error deleting role:', error);
      }
    }
  };
}
