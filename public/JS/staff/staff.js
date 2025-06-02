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

    Alpine.data('staffData', () => ({
        staffs: [],
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
            await this.fetchStaffs();
        },

        async fetchStaffs() {
            this.loading = true;
            this.error = null;

            const query = `
                query GetStaffs($search: String, $is_active: Boolean) {
                    staffs(search: $search, is_active: $is_active) {
                        staff_id
                        name
                        email
                        phone_number
                        role_id
                        is_active
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
                this.staffs = result.data.staffs;
            }

            this.loading = false;
        },

        async filterByActiveStatus(value) {
            this.is_active = value;
            await this.fetchStaffs();
        },

        async searchStaffs() {
            await this.fetchStaffs();
        },

        async createStaff(newStaff) {
            const mutation = `
                mutation CreateStaff($input: StaffInput!) {
                    createStaff(input: $input) {
                        staff_id
                        name
                    }
                }
            `;

            const variables = { input: newStaff };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return null;
            } else {
                this.staffs.push(result.data.createStaff);
                return result.data.createStaff;
            }
        },

        async updateStaff(staff_id, input) {
            const mutation = `
                mutation UpdateStaff($staff_id: ID!, $input: StaffUpdateInput!) {
                    updateStaff(staff_id: $staff_id, input: $input) {
                        staff_id
                        name
                    }
                }
            `;

            const variables = { staff_id, input };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return null;
            } else {
                const updated = result.data.updateStaff;
                const index = this.staffs.findIndex(s => s.staff_id === updated.staff_id);
                if (index !== -1) this.staffs[index] = updated;
                return updated;
            }
        },

        async deleteStaff(staff_id) {
            const mutation = `
                mutation DeleteStaff($staff_id: ID!) {
                    deleteStaff(staff_id: $staff_id) {
                        staff_id
                    }
                }
            `;

            const variables = { staff_id };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return false;
            } else {
                this.staffs = this.staffs.filter(s => s.staff_id !== staff_id);
                return true;
            }
        }
    }));

    Alpine.start();
});
