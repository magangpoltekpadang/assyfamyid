document.addEventListener("DOMContentLoaded", function () {
    // GraphQL endpoint
    const endpoint = "/graphql";

    // Function to execute GraphQL queries
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
            console.error('GraphQL Error:', error);
            return { errors: [error] };
        }
    }

    // Vehicle Type Data Component
    Alpine.data('vehicleTypeData', () => ({
        vehicleTypes: [],
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
        vehicleTypeIdToDelete: null,
        loading: false,
        error: null,

        async init() {
            await this.fetchVehicleTypes();
        },

        async fetchVehicleTypes() {
            this.loading = true;
            this.error = null;

            const query = `
                query GetVehicleTypes($page: Int, $perPage: Int, $search: String, $is_active: Boolean) {
                    vehicleTypes(page: $page, perPage: $perPage, search: $search, is_active: $is_active) {
                        data {
                            vehicle_type_id
                            type_name
                            code
                            description
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
                console.error('GraphQL Error:', result.errors);
            } else {
                this.vehicleTypes = result.data.vehicleTypes.data;
                this.pagination = {
                    current_page: result.data.vehicleTypes.paginatorInfo.currentPage,
                    last_page: result.data.vehicleTypes.paginatorInfo.lastPage,
                    per_page: result.data.vehicleTypes.paginatorInfo.perPage,
                    total: result.data.vehicleTypes.paginatorInfo.total,
                    has_more: result.data.vehicleTypes.paginatorInfo.hasMorePages,
                };
            }

            this.loading = false;
        },

        async deleteVehicleType() {
            if (!this.vehicleTypeIdToDelete) return;

            const mutation = `
                mutation DeleteVehicleType($vehicle_type_id: ID!) {
                    deleteVehicleType(vehicle_type_id: $vehicle_type_id) {
                        vehicle_type_id
                        type_name
                    }
                }
            `;

            const variables = {
                vehicle_type_id: this.vehicleTypeIdToDelete,
            };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
            } else {
                this.showDeleteModal = false;
                await this.fetchVehicleTypes();
            }
        },

        // Pagination methods
        async changePage(page) {
            if (page === '...') return;
            this.pagination.current_page = parseInt(page);
            await this.fetchVehicleTypes();
        },

        async previousPage() {
            if (this.pagination.current_page > 1) {
                this.pagination.current_page--;
                await this.fetchVehicleTypes();
            }
        },

        async nextPage() {
            if (this.pagination.current_page < this.pagination.last_page) {
                this.pagination.current_page++;
                await this.fetchVehicleTypes();
            }
        },

        async resetFilters() {
            this.search = '';
            this.status = '';
            this.pagination.current_page = 1;
            await this.fetchVehicleTypes();
        },

        confirmDelete(id) {
            this.vehicleTypeIdToDelete = id;
            this.showDeleteModal = true;
        }
    }));

    // Initialize Alpine.js
    Alpine.start();
});
