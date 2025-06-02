function vehicleTypeData() {
    return {
        vehicleTypes: [],
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
        status: '',
        showDeleteModal: false,
        vehicleTypeIdToDelete: null,

        init() {
            this.fetchVehicleTypes();
        },



        async fetchVehicleTypes() {
            try {
                const query = `
                    query($search: String, $is_active: Boolean) {
                        vehicleTypes(search: $search, is_active: $is_active) {
                        vehicle_type_id
                        type_name
                        code
                        description
                        is_active
                        }
                    }
                    `;

                const variables = {
                    page: this.pagination.current_page,
                    perPage: this.pagination.per_page,
                    search: this.search || null,
                    is_active: this.status === '' ? null : this.status === '1'
                };

                const response = await fetch('/graphql', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ query, variables })
                });

                const result = await response.json();
                if (result.errors) {
                    console.error('GraphQL errors:', result.errors);
                    return;
                }
                console.log('Fetched data:', result.data.vehicleTypes);

                this.vehicleTypes = result.data.vehicleTypes || [];

                if (this.status !== '') {
                    const isActiveBool = this.status === '1';
                    this.vehicleTypes = this.vehicleTypes.filter(v => v.is_active === isActiveBool);
                }

                if (this.search) {
                    const lowerSearch = this.search.toLowerCase();
                    this.vehicleTypes = this.vehicleTypes.filter(v =>
                        v.type_name.toLowerCase().includes(lowerSearch)
                    );
                }

                this.pagination.total = this.vehicleTypes.length;
                this.pagination.last_page = 1;
                this.pagination.from = 1;
                this.pagination.to = this.vehicleTypes.length;
            } catch (error) {
                console.error('Error fetching vehicle types:', error);
            }
        },

        async changePage(page) {
            if (page === '...' || isNaN(page)) return;
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
        },

        async deleteVehicleType() {
            try {
                const mutation = `
                    mutation($vehicle_type_id: ID!) {
                        deleteVehicleType(vehicle_type_id: $vehicle_type_id) {
                            vehicle_type_id
                        }
                    }
                `;

                const variables = {
                    vehicle_type_id: this.vehicleTypeIdToDelete
                };

                const response = await fetch('/graphql', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ query: mutation, variables })
                });

                const result = await response.json();

                if (result.data?.deleteVehicleType?.vehicle_type_id) {
                    this.showDeleteModal = false;
                    this.vehicleTypeIdToDelete = null;
                    await this.fetchVehicleTypes();
                } else {
                    console.error('Failed to delete vehicle type.');
                }
            } catch (error) {
                console.error('Error deleting vehicle type:', error);
            }
        }
    };
}
