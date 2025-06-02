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

    Alpine.data('transactionData', () => ({
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

        async init() {
            await this.fetchTransactions();
        },

        async fetchTransactions() {
            this.loading = true;
            this.error = null;

            const query = `
                query GetTransactions($search: String) {
                    transactions(search: $search) {
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
                        }
                    }
                }
            `;

            const variables = {};
            if (this.search.trim() !== '') variables.search = this.search;

            const result = await executeQuery(query, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error('GraphQL Error:', result.errors);
            } else {
                this.transactions = result.data.transactions;
                // Jika backend sudah support pagination, isi properti pagination di sini
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
        }
    }));

    Alpine.start();
});

