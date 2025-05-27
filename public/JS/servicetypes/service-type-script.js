function serviceTypeData() {
    return {
        serviceTypes: [],
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
        serviceTypeIdToDelete: null,

        init() {
            this.fetchServiceTypes();
        },

        async fetchServiceTypes() {
            try {
                const query = `
                    query {
                        serviceTypes {
                            service_type_id
                            type_name
                            code
                            description
                            is_active
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

                console.log('Fetched data:', result.data.serviceTypes);

                let allServiceTypes = result.data.serviceTypes || [];

                // Filter status (aktif / nonaktif)
                if (this.status !== '') {
                    const isActiveBool = this.status === '1';
                    allServiceTypes = allServiceTypes.filter(s => s.is_active === isActiveBool);
                }

                // Search
                if (this.search) {
                    const lowerSearch = this.search.toLowerCase();
                    allServiceTypes = allServiceTypes.filter(s =>
                        s.type_name.toLowerCase().includes(lowerSearch)
                    );
                }

                // Manual pagination
                const start = (this.pagination.current_page - 1) * this.pagination.per_page;
                const end = start + this.pagination.per_page;
                this.serviceTypes = allServiceTypes.slice(start, end);

                this.pagination.total = allServiceTypes.length;
                this.pagination.last_page = Math.ceil(this.pagination.total / this.pagination.per_page);
                this.pagination.from = start + 1;
                this.pagination.to = Math.min(end, this.pagination.total);

            } catch (error) {
                console.error('Error fetching service types:', error);
            }
        },

        async changePage(page) {
            if (page === '...' || isNaN(page)) return;
            this.pagination.current_page = parseInt(page);
            await this.fetchServiceTypes();
        },

        async previousPage() {
            if (this.pagination.current_page > 1) {
                this.pagination.current_page--;
                await this.fetchServiceTypes();
            }
        },

        async nextPage() {
            if (this.pagination.current_page < this.pagination.last_page) {
                this.pagination.current_page++;
                await this.fetchServiceTypes();
            }
        },

        async resetFilters() {
            this.search = '';
            this.status = '';
            this.pagination.current_page = 1;
            await this.fetchServiceTypes();
        },

        confirmDelete(id) {
            this.serviceTypeIdToDelete = id;
            this.showDeleteModal = true;
        },

        async deleteServiceType() {
            try {
                const mutation = `
                    mutation($service_type_id: ID!) {
                        deleteServiceType(service_type_id: $service_type_id) {
                            service_type_id
                        }
                    }
                `;

                const variables = {
                    service_type_id: this.serviceTypeIdToDelete
                };

                const response = await fetch('/graphql', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ query: mutation, variables })
                });

                const result = await response.json();
                if (result.data?.deleteServiceType?.service_type_id) {
                    this.showDeleteModal = false;
                    this.serviceTypeIdToDelete = null;
                    await this.fetchServiceTypes();
                } else {
                    console.error('Failed to delete service type.');
                }
            } catch (error) {
                console.error('Error deleting service type:', error);
            }
        }
    };
}
