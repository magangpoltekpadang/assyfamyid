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

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error("GraphQL Error:", error);
            return { errors: [error] };
        }
    }

    window.transactionServiceData = () => ({
        transactionServices: [],
        loading: false,
        error: null,
        search: '',
        status: '',
        pagination: {
            current_page: 1,
            per_page: 10,
            total: 0,
            last_page: 1,
            has_more: false
        },

        async init() {
            await this.fetchTransactionServices();
        },

        async fetchTransactionServices() {
            this.loading = true;
            this.error = null;

            const query = `
                query($search: String, $status: String) {
                    transactionServices(search: $search, status: $status) {
                        transaction_service_id
                        transaction_id
                        service {
                            service_name
                        }
                        quantity
                        unit_price
                        discount
                        total_price
                        staff {
                            name
                        }
                        start_time
                        end_time
                        status
                        notes
                    }
                }
            `;

            const variables = {
                ...(this.search.trim() && { search: this.search }),
                ...(this.status && { status: this.status }),
            };

            const result = await executeQuery(query, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error("GraphQL Error:", result.errors);
            } else {
                this.transactionServices = result.data?.transactionServices ?? [];
            }

            this.loading = false;
        },

        async searchTransactionServices() {
            await this.fetchTransactionServices();
        },

        async deleteTransactionService(transaction_service_id) {
            const mutation = `
                mutation($transaction_service_id: ID!) {
                    deleteTransactionService(transaction_service_id: $transaction_service_id) {
                        transaction_service_id
                    }
                }
            `;

            const variables = { transaction_service_id };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error("GraphQL Error:", result.errors);
                return false;
            } else {
                this.transactionServices = this.transactionServices.filter(
                    t => t.transaction_service_id !== transaction_service_id
                );
                return true;
            }
        },

        calculateRowTotal(unit_price, quantity, discount) {
            const price = parseFloat(unit_price);
            const qty = parseInt(quantity, 10);
            const disc = parseFloat(discount);

            const validPrice = isNaN(price) ? 0 : price;
            const validQty = isNaN(qty) ? 0 : qty;
            const validDiscount = isNaN(disc) ? 0 : disc;

            return Math.max((validPrice * validQty) - validDiscount, 0);
        }
    });

    Alpine.start();
});
