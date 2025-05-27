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

    Alpine.data('customerData', () => ({
        customers: [],
        loading: false,
        error: null,
        search: '',
        is_member: null,  // filter member or not: true/false/null
        pagination: {
            current_page: 1,
            per_page: 10,
            total: 0,
            last_page: 1,
            has_more: false
        },

        async init() {
            await this.fetchCustomers();
        },

        async fetchCustomers() {
            this.loading = true;
            this.error = null;

            const query = `
                query GetCustomers($search: String, $is_member: Boolean) {
                    customers(search: $search, is_member: $is_member) {
                        customer_id
                        plate_number
                        name
                        phone_number
                        vehicle_type_id
                        vehicle_color
                        member_number
                        join_date
                        member_expiry_date
                        is_member
                        created_at
                        updated_at
                    }
                }
            `;

            const variables = {};
            if (this.search.trim() !== '') variables.search = this.search;
            if (this.is_member !== null) variables.is_member = this.is_member;

            const result = await executeQuery(query, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
            } else {
                this.customers = result.data.customers;
                // Kalau backend ada pagination, update pagination di sini
                // Namun dari schema, @all biasanya tidak paginasi, jadi skip pagination update
            }

            this.loading = false;
        },

        // Filter berdasarkan status member: true/false/null
        async filterByMemberStatus(value) {
            this.is_member = value;
            await this.fetchCustomers();
        },

        async searchCustomers() {
            await this.fetchCustomers();
        },

        // Contoh metode createCustomer
        async createCustomer(newCustomer) {
            const mutation = `
                mutation CreateCustomer($input: CustomerCreateInput!) {
                    createCustomer(input: $input) {
                        customer_id
                        name
                        plate_number
                    }
                }
            `;

            const variables = { input: newCustomer };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return null;
            } else {
                // Tambah customer baru ke daftar jika ingin langsung update UI
                this.customers.push(result.data.createCustomer);
                return result.data.createCustomer;
            }
        },

        // Contoh updateCustomer
        async updateCustomer(customer_id, input) {
            const mutation = `
                mutation UpdateCustomer($customer_id: ID!, $input: CustomerInput!) {
                    updateCustomer(customer_id: $customer_id, input: $input) {
                        customer_id
                        name
                        plate_number
                    }
                }
            `;

            const variables = { customer_id, input };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return null;
            } else {
                // Update list customer dengan data baru jika ingin update UI
                const updated = result.data.updateCustomer;
                const index = this.customers.findIndex(c => c.customer_id === updated.customer_id);
                if (index !== -1) this.customers[index] = updated;
                return updated;
            }
        },

        // Contoh deleteCustomer
        async deleteCustomer(customer_id) {
            const mutation = `
                mutation DeleteCustomer($customer_id: ID!) {
                    deleteCustomer(customer_id: $customer_id) {
                        customer_id
                    }
                }
            `;

            const variables = { customer_id };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return false;
            } else {
                this.customers = this.customers.filter(c => c.customer_id !== customer_id);
                return true;
            }
        }
    }));

    Alpine.start();
});
