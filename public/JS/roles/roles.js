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

    Alpine.data("roleData", () => ({
      roles: [],
      pagination: {
        current_page: 1,
        per_page: 10,
        total: 0,
        last_page: 1,
        has_more: false,
      },
      search: '',
      showDeleteModal: false,
      roleIdToDelete: null,
      loading: false,
      error: null,

      async init() {
        await this.fetchRoles();
      },

      async fetchRoles() {
        this.loading = true;
        this.error = null;

        const query = `
          query GetRoles($page: Int, $perPage: Int, $search: String) {
            roles(page: $page, perPage: $perPage, search: $search) {
              data {
                role_id
                role_name
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
          this.roles = result.data.roles.data;
          const info = result.data.roles.paginatorInfo;
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

      async deleteRole() {
        if (!this.roleIdToDelete) return;

        const mutation = `
          mutation DeleteRole($role_id: ID!) {
            deleteRole(role_id: $role_id) {
              role_id
              role_name
            }
          }
        `;

        const variables = {
          role_id: this.roleIdToDelete,
        };

        const result = await executeQuery(mutation, variables);

        if (result.errors) {
          this.error = result.errors[0].message;
          console.error("GraphQL Error:", result.errors);
        } else {
          this.showDeleteModal = false;
          await this.fetchRoles();
        }
      },

      async changePage(page) {
        if (page === "...") return;
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
      }
    }));

    Alpine.start();
  });

