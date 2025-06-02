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
            console.error("GraphQL Error:", error);
            return { errors: [error] };
        }
    }

    Alpine.data("paymentMethodData", () => ({
        paymentMethods: [],
        pagination: {
            current_page: 1,
            per_page: 10,
            total: 0,
            last_page: 1,
            has_more: false
        },
        search: '',
        status: '',
        showDeleteModal: false,
        paymentMethodIdToDelete: null,
        loading: false,
        error: null,

        async init() {
            await this.fetchPaymentMethods();
        },

        async fetchPaymentMethods() {
            this.loading = true;
            this.error = null;

            const query = `
                query GetPaymentMethods($page: Int, $perPage: Int, $search: String, $is_active: Boolean) {
                    paymentMethods(page: $page, perPage: $perPage, search: $search, is_active: $is_active) {
                        data {
                            payment_method_id
                            method_name
                            code
                            is_active
                            created_at
                            updated_at
                        }
                        paginatorInfo {
                            currentPage
                            lastPage
                            perPage
                            total
                            hasMorePages
                        }
                    }
                }
            `;

            const variables = {
                page: this.pagination.current_page,
                perPage: this.pagination.per_page,
                search: this.search,
            };

            if (this.status !== '') {
                variables.is_active = this.status === 'active';
            }

            const result = await executeQuery(query, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error("GraphQL Error:", result.errors);
            } else {
                this.paymentMethods = result.data.paymentMethods.data;
                const info = result.data.paymentMethods.paginatorInfo;
                this.pagination = {
                    current_page: info.currentPage,
                    last_page: info.lastPage,
                    per_page: info.perPage,
                    total: info.total,
                    has_more: info.hasMorePages,
                };
            }

            this.loading = false;
        },

        async deletePaymentMethod() {
            if (!this.paymentMethodIdToDelete) return;

            const mutation = `
                mutation DeletePaymentMethod($payment_method_id: ID!) {
                    deletePaymentMethod(payment_method_id: $payment_method_id) {
                        payment_method_id
                        method_name
                    }
                }
            `;

            const variables = {
                payment_method_id: this.paymentMethodIdToDelete,
            };

            const result = await executeQuery(mutation, variables);

            if (result.errors) {
                this.error = result.errors[0].message;
                console.error("GraphQL Error:", result.errors);
            } else {
                this.showDeleteModal = false;
                await this.fetchPaymentMethods();
            }
        },

        async changePage(page) {
            if (page === "...") return;
            this.pagination.current_page = parseInt(page);
            await this.fetchPaymentMethods();
        },

        async previousPage() {
            if (this.pagination.current_page > 1) {
                this.pagination.current_page--;
                await this.fetchPaymentMethods();
            }
        },

        async nextPage() {
            if (this.pagination.current_page < this.pagination.last_page) {
                this.pagination.current_page++;
                await this.fetchPaymentMethods();
            }
        },

        async resetFilters() {
            this.search = '';
            this.status = '';
            this.pagination.current_page = 1;
            await this.fetchPaymentMethods();
        },

        confirmDelete(id) {
            this.paymentMethodIdToDelete = id;
            this.showDeleteModal = true;
        }
    }));

    Alpine.start();
});

