function transactionData() {
  return {
    transactions: [],
    search: '',
    payment_status_id: '',

    totalTransactions: 0,
    paidTransactions: 0,
    unpaidTransactions: 0,

    showDeleteModal: false,
    transactionIdToDelete: null,

    // Tambahkan properti subtotal, discount, tax, final_price agar calculateFinalPrice jalan
    subtotal: 0,
    discount: 0,
    tax: 0,
    final_price: 0,

    init() {
      this.fetchTransactions();
    },

    async fetchTransactions() {
      try {
        const query = `
          query($search: String, $payment_status_id: ID) {
            transactions(search: $search, payment_status_id: $payment_status_id) {
              transaction_id
              transaction_code
              transaction_date
              final_price
              customer {
                name
              }
              outlet_id
              paymentStatus {
                status_name
                payment_status_id
              }
            }
          }
        `;

        const variables = {};
        if (this.search.trim() !== '') variables.search = this.search;
        if (this.payment_status_id !== '') variables.payment_status_id = this.payment_status_id;

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

        this.transactions = result.data.transactions || [];

        // Update statistik
        this.totalTransactions = this.transactions.length;
        this.paidTransactions = this.transactions.filter(
          t => t.paymentStatus?.payment_status_id == 1
        ).length;
        this.unpaidTransactions = this.transactions.filter(
          t => t.paymentStatus?.payment_status_id == 2
        ).length;

      } catch (error) {
        console.error('Fetch error:', error);
        this.transactions = [];
      }
    },

    calculateFinalPrice() {
      const sub = parseFloat(this.subtotal);
      const disc = parseFloat(this.discount);
      const t = parseFloat(this.tax);

      const subtotalVal = isNaN(sub) ? 0 : sub;
      const discountVal = isNaN(disc) ? 0 : disc;
      const taxVal = isNaN(t) ? 0 : t;

      this.final_price = subtotalVal - discountVal + taxVal;

      if (this.final_price < 0) this.final_price = 0;
    },

    async resetFilters() {
      this.search = '';
      this.payment_status_id = '';
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
