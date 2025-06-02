function transactionData() {
  return {
    transactions: [],
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0,
    },
    search: '',
    payment_status_id: '',  // tambahkan ini karena ada filter payment_status

    // Statistik jumlah transaksi
    totalTransactions: 0,
    paidTransactions: 0,
    unpaidTransactions: 0,

    showDeleteModal: false,
    transactionIdToDelete: null,

    init() {
      this.fetchTransactions();
    },

    async fetchTransactions() {
      try {
        const query = `
          query($search: String, $payment_status_id: ID, $page: Int) {
            transactions(search: $search, payment_status_id: $payment_status_id, page: $page) {
              data {
                transaction_id
                transaction_code
                transaction_date
                final_price
                customer {
                  name
                }
                outlet {
                  outlet_name
                }
                payment_status {
                  status_name
                  payment_status_id
                }
              }
              pagination {
                total
                current_page
                last_page
                per_page
                from
                to
              }
            }
          }
        `;

        const variables = {
          search: this.search || null,
          payment_status_id: this.payment_status_id || null,
          page: this.pagination.current_page,
        };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query, variables }),
        });

        const result = await response.json();

        if (result.errors) {
          console.error('GraphQL errors:', result.errors);
          this.transactions = [];
          return;
        }

        const transactionsData = result.data.transactions;

        this.transactions = transactionsData.data || [];

        this.pagination = transactionsData.pagination || {
          total: 0,
          current_page: 1,
          last_page: 1,
          per_page: 10,
          from: 0,
          to: 0,
        };

        this.totalTransactions = this.pagination.total || this.transactions.length;

        // Pastikan asumsi payment_status_id: 1=Paid, 2=Unpaid (sesuaikan dengan data sebenarnya)
        this.paidTransactions = this.transactions.filter(
          t => t.payment_status?.payment_status_id === 1
        ).length;

        this.unpaidTransactions = this.transactions.filter(
          t => t.payment_status?.payment_status_id === 2
        ).length;

      } catch (error) {
        console.error('Fetch error:', error);
        this.transactions = [];
      }
    },

    async resetFilters() {
      this.search = '';
      this.payment_status_id = '';
      this.pagination.current_page = 1;
      await this.fetchTransactions();
    },

    confirmDelete(id) {
      this.transactionIdToDelete = id;
      this.showDeleteModal = true;
    },

    async deleteTransaction() {
      try {
        const mutation = `
          mutation($transaction_id: ID!) {
            deleteTransaction(transaction_id: $transaction_id) {
              transaction_id
            }
          }
        `;

        const variables = {
          transaction_id: this.transactionIdToDelete,
        };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query: mutation, variables }),
        });

        const result = await response.json();

        if (result.data?.deleteTransaction?.transaction_id) {
          this.showDeleteModal = false;
          this.transactionIdToDelete = null;
          await this.fetchTransactions();
        } else {
          console.error('Failed to delete transaction.');
        }
      } catch (error) {
        console.error('Error deleting transaction:', error);
      }
    },
  };
}
