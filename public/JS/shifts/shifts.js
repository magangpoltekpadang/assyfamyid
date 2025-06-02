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

    Alpine.data('shiftData', () => ({
        shifts: [],
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
            await this.fetchShifts();
        },

        async fetchShifts() {
            this.loading = true;
            this.error = null;

            const query = `
                query GetShifts($search: String, $is_active: Boolean) {
                    shifts(search: $search, is_active: $is_active) {
                        shift_id
                        shift_name
                        start_time
                        end_time
                        is_active
                        outlet_id
                        outlet {
                            outlet_id
                            outlet_name
                        }
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
                this.shifts = result.data.shifts;
            }

            this.loading = false;
        },

        async filterByActiveStatus(value) {
            this.is_active = value;
            await this.fetchShifts();
        },

        async searchShifts() {
            await this.fetchShifts();
        },

        async createShift(newShift) {
            const mutation = `
                mutation CreateShift($input: ShiftInput!) {
                    createShift(input: $input) {
                        shift_id
                        shift_name
                    }
                }
            `;

            const variables = { input: newShift };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return null;
            } else {
                this.shifts.push(result.data.createShift);
                return result.data.createShift;
            }
        },

        async updateShift(shift_id, input) {
            const mutation = `
                mutation UpdateShift($shift_id: ID!, $input: ShiftUpdateInput!) {
                    updateShift(shift_id: $shift_id, input: $input) {
                        shift_id
                        shift_name
                    }
                }
            `;

            const variables = { shift_id, input };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return null;
            } else {
                const updated = result.data.updateShift;
                const index = this.shifts.findIndex(s => s.shift_id === updated.shift_id);
                if (index !== -1) this.shifts[index] = updated;
                return updated;
            }
        },

        async deleteShift(shift_id) {
            const mutation = `
                mutation DeleteShift($shift_id: ID!) {
                    deleteShift(shift_id: $shift_id) {
                        shift_id
                    }
                }
            `;

            const variables = { shift_id };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return false;
            } else {
                this.shifts = this.shifts.filter(s => s.shift_id !== shift_id);
                return true;
            }
        }
    }));

    Alpine.start();
});
