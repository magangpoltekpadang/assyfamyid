function transactionServiceData() {
    return {
        stats: {
            total_transactions: 0,
            total_revenue: 0,
            today_transactions: 0,
        },
        transactions: [],
        allTransactions: [],
        pagination: {
            total: 0,
            current_page: 1,
            per_page: 10,
            last_page: 1,
            from: 0,
            to: 0,
            links: [],
        },
        search: '',
        status: '',
        transactionDate: '',
        showDeleteModal: false,
        deleteId: null,

        init() {
            this.fetchTransactions();
        },

        formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount || 0);
        },

        fetchTransactions() {
            const query = `
                query {
                    transactionServices {
                        transaction_service_id
                        transaction_id
                        service_id
                        quantity
                        unit_price
                        discount
                        total_price
                        staff_id
                        start_time
                        end_time
                        status
                        notes
                        created_at
                        updated_at
                        service { service_name }
                        transaction {
                            transaction_code
                            transaction_date
                            customer { name }
                        }
                        staff { name }
                    }
                }
            `;

            fetch('/graphql', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ query }),
            })
            .then(res => res.json())
            .then(result => {
                if (result.errors) {
                    console.error('GraphQL errors:', result.errors);
                    return;
                }

                this.allTransactions = result.data.transactionServices.map(t => ({
                    ...t,
                    service_name: t.service?.service_name ?? '-',
                    transaction_code: t.transaction?.transaction_code ?? '-',
                    transaction_date: t.transaction?.transaction_date ?? '-',
                    customer_name: t.transaction?.customer?.name ?? '-',
                    staff_name: t.staff?.name ?? '-',
                }));

                this.updateStats();
                this.paginateData();
            })
            .catch(error => console.error('Fetch error:', error));
        },

        updateStats() {
            this.stats.total_transactions = this.allTransactions.length;
            this.stats.total_revenue = this.allTransactions.reduce((sum, t) => sum + (t.total_price || 0), 0);
            const today = new Date().toISOString().slice(0, 10);
            this.stats.today_transactions = this.allTransactions.filter(t => t.start_time?.slice(0, 10) === today).length;
        },

        paginateData() {
            const start = (this.pagination.current_page - 1) * this.pagination.per_page;
            const end = start + this.pagination.per_page;
            let filtered = this.allTransactions;

            // Apply search
            if (this.search) {
                const term = this.search.toLowerCase();
                filtered = filtered.filter(t =>
                    t.transaction_code?.toLowerCase().includes(term) ||
                    t.customer_name?.toLowerCase().includes(term) ||
                    t.service_name?.toLowerCase().includes(term)
                );
            }

            // Apply status filter
            if (this.status) {
                filtered = filtered.filter(t => t.status === this.status);
            }

            // Apply date filter
            if (this.transactionDate) {
                filtered = filtered.filter(t => t.transaction_date?.slice(0, 10) === this.transactionDate);
            }

            this.pagination.total = filtered.length;
            this.pagination.last_page = Math.ceil(filtered.length / this.pagination.per_page);
            this.pagination.from = start + 1;
            this.pagination.to = Math.min(end, filtered.length);

            this.transactions = filtered.slice(start, end);
        },

        resetFilters() {
            this.search = '';
            this.status = '';
            this.transactionDate = '';
            this.pagination.current_page = 1;
            this.paginateData();
        },

        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.pagination.current_page = page;
                this.paginateData();
            }
        },

        previousPage() {
            if (this.pagination.current_page > 1) {
                this.pagination.current_page--;
                this.paginateData();
            }
        },

        nextPage() {
            if (this.pagination.current_page < this.pagination.last_page) {
                this.pagination.current_page++;
                this.paginateData();
            }
        },

        confirmDelete(id) {
            this.deleteId = id;
            this.showDeleteModal = true;
        },

        deleteTransaction() {
            fetch(`/transaction-services/${this.deleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal menghapus data.');
                this.showDeleteModal = false;
                this.fetchTransactions();
            })
            .catch(err => console.error(err));
        }
    }
}
