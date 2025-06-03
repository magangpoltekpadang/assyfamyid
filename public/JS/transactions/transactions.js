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
                body: JSON.stringify({ query, variables }),
            });
            return await response.json();
        } catch (error) {
            console.error("GraphQL Error:", error);
            return { errors: [error] };
        }
    }

    window.transactionData = () => ({
        transactions: [],
        loading: false,
        error: null,
        search: '',
        pagination: {
            current_page: 1,
            per_page: 10,
            total: 0,
            last_page: 1,
            has_more: false
        },

        subtotal: 0,
        discount: 0,
        tax: 0,
        final_price: 0,

        async init() {
            await this.fetchTransactions();
        },

        async fetchTransactions() {
            this.loading = true;
            this.error = null;

            const query = `
                query($search: String, $payment_status_id: ID) {
                    transactions(search: $search, payment_status_id: $payment_status_id) {
                        transaction_id
                        transaction_code
                        transaction_date
                        final_price
                        customer { name }
                        outlet_id
                        paymentStatus {
                            status_name
                            payment_status_id
                        }
                    }
                }
            `;

            const variables = {};
            if (this.search.trim() !== '') {
                variables.search = this.search;
            }

            const result = await executeQuery(query, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
            } else {
                this.transactions = result.data.transactions;
            }

            this.loading = false;
        },

        async searchTransactions() {
            await this.fetchTransactions();
        },

        async deleteTransaction(transaction_id) {
            const mutation = `
                mutation DeleteTransaction($transaction_id: ID!) {
                    deleteTransaction(transaction_id: $transaction_id) {
                        transaction_id
                    }
                }
            `;

            const variables = { transaction_id };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
                return false;
            } else {
                this.transactions = this.transactions.filter(t => t.transaction_id !== transaction_id);
                return true;
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
        }
    });

    Alpine.start();
});
