function serviceData() {
    return {
        services: [],
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
        serviceIdToDelete: null,

        init() {
            this.fetchServices();
        },

        async fetchServices() {
            try {
                const query = `
                    query($search: String, $is_active: Boolean) {
                        services(search: $search, is_active: $is_active) {
                            service_id
                            service_name
                            service_type_id
                            price
                            estimated_duration
                            is_active
                            outlet_id
                        }
                    }
                `;

                const variables = {
                    page: this.pagination.current_page,
                    perPage: this.pagination.per_page,
                    search: this.search || null,
                    is_active: this.status === '' ? null : this.status === '1',
                };

                const response = await fetch('/graphql', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ query, variables }),
                });

                const result = await response.json();

                if (result.errors) {
                    console.error('GraphQL errors:', result.errors);
                    return;
                }

                this.services = result.data.services || [];

                if (this.status !== '') {
                    const isActiveBool = this.status === '1';
                    this.services = this.services.filter(s => s.is_active === isActiveBool);
                }

                if (this.search) {
                    const lowerSearch = this.search.toLowerCase();
                    this.services = this.services.filter(s =>
                        s.service_name?.toLowerCase().includes(lowerSearch)
                    );
                }

                this.pagination.total = this.services.length;
                this.pagination.last_page = 1;
                this.pagination.from = 1;
                this.pagination.to = this.services.length;
            } catch (error) {
                console.error('Error fetching services:', error);
            }
        },

        async changePage(page) {
            if (page === '...' || isNaN(page)) return;
            this.pagination.current_page = parseInt(page);
            await this.fetchServices();
        },

        async previousPage() {
            if (this.pagination.current_page > 1) {
                this.pagination.current_page--;
                await this.fetchServices();
            }
        },

        async nextPage() {
            if (this.pagination.current_page < this.pagination.last_page) {
                this.pagination.current_page++;
                await this.fetchServices();
            }
        },

        async resetFilters() {
            this.search = '';
            this.status = '';
            this.pagination.current_page = 1;
            await this.fetchServices();
        },

        confirmDelete(id) {
            this.serviceIdToDelete = id;
            this.showDeleteModal = true;
        },

        async deleteService() {
            try {
                const mutation = `
                    mutation($service_id: ID!) {
                        deleteService(service_id: $service_id) {
                            service_id
                        }
                    }
                `;

                const variables = {
                    service_id: this.serviceIdToDelete,
                };

                const response = await fetch('/graphql', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ query: mutation, variables }),
                });

                const result = await response.json();

                if (result.data?.deleteService?.service_id) {
                    this.showDeleteModal = false;
                    this.serviceIdToDelete = null;
                    await this.fetchServices();
                } else {
                    console.error('Failed to delete service.');
                }
            } catch (error) {
                console.error('Error deleting service:', error);
            }
        },
    };
}
