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
            console.error('GraphQL Error:', error);
            return { errors: [error] };
        }
    }

    Alpine.data('serviceData', () => ({
        services: [],
        loading: false,
        error: null,
        search: '',
        is_active: null,
        pagination: {
            current_page: 1,
            per_page: 10,
            total: 0,
            last_page: 1,
            has_more: false
        },

        async init() {
            await this.fetchServices();
        },

        async fetchServices() {
            this.loading = true;
            this.error = null;

            const query = `
                query GetServices($search: String, $is_active: Boolean) {
                    services(search: $search, is_active: $is_active) {
                        service_id
                        service_name
                        price
                        estimated_duration
                        is_active
                        outlet {
                            outlet_name
                        }
                        service_type {
                            type_name
                        }
                        created_at
                        updated_at
                    }
                }
            `;

            const variables = {};
            if (this.search.trim() !== '') variables.search = this.search;
            if (this.is_active !== null) variables.is_active = this.is_active;

            const result = await executeQuery(query, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
            } else {
                this.services = result.data.services;
            }

            this.loading = false;
        },

        async filterByActiveStatus(value) {
            this.is_active = value;
            await this.fetchServices();
        },

        async searchServices() {
            await this.fetchServices();
        },

        async createService(newService) {
            const mutation = `
                mutation CreateService($input: ServiceCreateInput!) {
                    createService(input: $input) {
                        service_id
                        service_name
                    }
                }
            `;

            const variables = { input: newService };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return null;
            } else {
                this.services.push(result.data.createService);
                return result.data.createService;
            }
        },

        async updateService(service_id, input) {
            const mutation = `
                mutation UpdateService($service_id: ID!, $input: ServiceInput!) {
                    updateService(service_id: $service_id, input: $input) {
                        service_id
                        service_name
                    }
                }
            `;

            const variables = { service_id, input };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return null;
            } else {
                const updated = result.data.updateService;
                const index = this.services.findIndex(s => s.service_id === updated.service_id);
                if (index !== -1) this.services[index] = updated;
                return updated;
            }
        },

        async deleteService(service_id) {
            const mutation = `
                mutation DeleteService($service_id: ID!) {
                    deleteService(service_id: $service_id) {
                        service_id
                    }
                }
            `;

            const variables = { service_id };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return false;
            } else {
                this.services = this.services.filter(s => s.service_id !== service_id);
                return true;
            }
        }
    }));

    Alpine.start();
});
