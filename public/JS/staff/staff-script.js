function staffData() {
    return {
        staffs: [],
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
        staffIdToDelete: null,

        init() {
            this.fetchStaffs();
        },

        async fetchStaffs() {
            try {
                const query = `
                    query {
                        staffs {
                            staff_id
                            name
                            email
                            phone_number
                            role_id
                            outlet_id
                            is_active
                            outlet {
                                outlet_id
                                outlet_name
                            }
                            role {
                                role_id
                                role_name
                            }
                        }
                    }
                `;

                const response = await fetch('/graphql', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ query }),
                });

                const result = await response.json();

                if (result.errors) {
                    console.error('GraphQL errors:', result.errors);
                    return;
                }

                let staffs = result.data.staffs || [];

                // Filter status aktif/nonaktif jika ada filter
                if (this.status !== '') {
                    const isActiveBool = this.status === '1';
                    staffs = staffs.filter(s => s.is_active === isActiveBool);
                }

                // Filter pencarian (name, email, phone, outlet name)
                if (this.search) {
                    const lowerSearch = this.search.toLowerCase();
                    staffs = staffs.filter(s =>
                        s.name?.toLowerCase().includes(lowerSearch) ||
                        s.email?.toLowerCase().includes(lowerSearch) ||
                        s.phone_number?.toLowerCase().includes(lowerSearch) ||
                        s.outlet?.outlet_name?.toLowerCase().includes(lowerSearch)
                    );
                }

                this.staffs = staffs;

                // Manual pagination (sementara hanya 1 halaman)
                this.pagination.total = staffs.length;
                this.pagination.last_page = 1;
                this.pagination.from = staffs.length > 0 ? 1 : 0;
                this.pagination.to = staffs.length;

            } catch (error) {
                console.error('Error fetching staffs:', error);
            }
        },

        async changePage(page) {
            if (page === '...' || isNaN(page)) return;
            this.pagination.current_page = parseInt(page);
            await this.fetchStaffs();
        },

        async previousPage() {
            if (this.pagination.current_page > 1) {
                this.pagination.current_page--;
                await this.fetchStaffs();
            }
        },

        async nextPage() {
            if (this.pagination.current_page < this.pagination.last_page) {
                this.pagination.current_page++;
                await this.fetchStaffs();
            }
        },

        async resetFilters() {
            this.search = '';
            this.status = '';
            this.pagination.current_page = 1;
            await this.fetchStaffs();
        },

        confirmDelete(id) {
            this.staffIdToDelete = id;
            this.showDeleteModal = true;
        },

        async deleteStaff() {
            try {
                const mutation = `
                    mutation($staff_id: ID!) {
                        deleteStaff(staff_id: $staff_id) {
                            staff_id
                        }
                    }
                `;

                const variables = { staff_id: this.staffIdToDelete };

                const response = await fetch('/graphql', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ query: mutation, variables }),
                });

                const result = await response.json();

                if (result.data?.deleteStaff?.staff_id) {
                    this.showDeleteModal = false;
                    this.staffIdToDelete = null;
                    await this.fetchStaffs();
                } else {
                    console.error('Failed to delete staff.');
                }
            } catch (error) {
                console.error('Error deleting staff:', error);
            }
        },
    };
}
