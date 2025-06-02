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

    Alpine.data("outletData", () => ({
        outlets: [],
        pagination: {
            current_page: 1,
            per_page: 10,
            total: 0,
            last_page: 1,
            has_more: false
        },
        search: '',
        status: '',
        showDeleteModal: false,
        outletIdToDelete: null,
        loading: false,
        error: null,

        async init() {
            await this.fetchOutlets();
        },

        async fetchOutlets() {
            this.loading = true;
            this.error = null;

            const query = `
                query GetOutlets($page: Int, $perPage: Int, $search: String, $is_active: Boolean) {
                    outlets(page: $page, perPage: $perPage, search: $search, is_active: $is_active) {
                        data {
                            outlet_id
                            outlet_name
                            address
                            phone_number
                            latitude
                            longitude
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
                search: this.search,
            };

            if (this.status !== '') {
                variables.is_active = this.status === 'active';
            }

            const result = await executeQuery(query, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error("GraphQL Error:", result.errors);
            } else {
                this.outlets = result.data.outlets.data;
                const info = result.data.outlets.paginatorInfo;
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

        async deleteOutlet() {
            if (!this.outletIdToDelete) return;

            const mutation = `
                mutation DeleteOutlet($outlet_id: ID!) {
                    deleteOutlet(outlet_id: $outlet_id) {
                        outlet_id
                        outlet_name
                    }
                }
            `;

            const variables = {
                outlet_id: this.outletIdToDelete,
            };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error("GraphQL Error:", result.errors);
            } else {
                this.showDeleteModal = false;
                await this.fetchOutlets();
            }
        },

        async changePage(page) {
            if (page === "...") return;
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
        }
    }));

    Alpine.start();
});
