function shiftData() {
    return {
        shifts: [],
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
        shiftIdToDelete: null,

        init() {
            this.fetchShifts();
        },

        async fetchShifts() {
            try {
                const query = `
                    query {
                        shifts {
                            shift_id
                            outlet_id
                            shift_name
                            start_time
                            end_time
                            is_active
                            created_at
                            updated_at
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

                this.shifts = result.data.shifts || [];

                if (this.status !== '') {
                    const isActiveBool = this.status === '1';
                    this.shifts = this.shifts.filter(s => s.is_active === isActiveBool);
                }

                if (this.search) {
                    const lowerSearch = this.search.toLowerCase();
                    this.shifts = this.shifts.filter(s =>
                        s.shift_name?.toLowerCase().includes(lowerSearch)
                    );
                }

                this.pagination.total = this.shifts.length;
                this.pagination.last_page = 1;
                this.pagination.from = 1;
                this.pagination.to = this.shifts.length;
            } catch (error) {
                console.error('Error fetching shifts:', error);
            }
        },

        async changePage(page) {
            if (page === '...' || isNaN(page)) return;
            this.pagination.current_page = parseInt(page);
            await this.fetchShifts();
        },

        async previousPage() {
            if (this.pagination.current_page > 1) {
                this.pagination.current_page--;
                await this.fetchShifts();
            }
        },

        async nextPage() {
            if (this.pagination.current_page < this.pagination.last_page) {
                this.pagination.current_page++;
                await this.fetchShifts();
            }
        },

        async resetFilters() {
            this.search = '';
            this.status = '';
            this.pagination.current_page = 1;
            await this.fetchShifts();
        },

        confirmDelete(id) {
            this.shiftIdToDelete = id;
            this.showDeleteModal = true;
        },

        async deleteShift() {
            try {
                const mutation = `
                    mutation($shift_id: ID!) {
                        deleteShift(shift_id: $shift_id) {
                            shift_id
                        }
                    }
                `;

                const variables = {
                    shift_id: this.shiftIdToDelete,
                };

                const response = await fetch('/graphql', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ query: mutation, variables }),
                });

                const result = await response.json();

                if (result.data?.deleteShift?.shift_id) {
                    this.showDeleteModal = false;
                    this.shiftIdToDelete = null;
                    await this.fetchShifts();
                } else {
                    console.error('Failed to delete shift.');
                }
            } catch (error) {
                console.error('Error deleting shift:', error);
            }
        },
    };
}
