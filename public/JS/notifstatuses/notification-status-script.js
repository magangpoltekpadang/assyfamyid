function notificationStatusData() {
  return {
    notificationStatuses: [],
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0,
    },
    search: '',
    // hapus isActiveFilter karena field is_active gak ada
    showDeleteModal: false,
    notificationStatusIdToDelete: null,

    init() {
      this.fetchNotificationStatuses();
    },

    async fetchNotificationStatuses() {
      try {
        const query = `
          query {
            notificationStatuses {
              status_id
              status_name
              code
              description
              created_at
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

        let allStatuses = result.data.notificationStatuses || [];

        if (this.search) {
          const lowerSearch = this.search.toLowerCase();
          allStatuses = allStatuses.filter(s =>
            (s.status_name && s.status_name.toLowerCase().includes(lowerSearch)) ||
            (s.code && s.code.toLowerCase().includes(lowerSearch))
          );
        }

        const start = (this.pagination.current_page - 1) * this.pagination.per_page;
        const end = start + this.pagination.per_page;
        this.notificationStatuses = allStatuses.slice(start, end);

        this.pagination.total = allStatuses.length;
        this.pagination.last_page = Math.max(1, Math.ceil(this.pagination.total / this.pagination.per_page));
        this.pagination.from = this.pagination.total > 0 ? start + 1 : 0;
        this.pagination.to = Math.min(end, this.pagination.total);

      } catch (error) {
        console.error('Error fetching notification statuses:', error);
      }
    },

    async changePage(page) {
      if (page === '...' || isNaN(page)) return;
      this.pagination.current_page = parseInt(page);
      await this.fetchNotificationStatuses();
    },

    async previousPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        await this.fetchNotificationStatuses();
      }
    },

    async nextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.pagination.current_page++;
        await this.fetchNotificationStatuses();
      }
    },

    async resetFilters() {
      this.search = '';

      this.pagination.current_page = 1;
      await this.fetchNotificationStatuses();
    },

    confirmDelete(id) {
      this.notificationStatusIdToDelete = id;
      this.showDeleteModal = true;
    },

    async deleteNotificationStatus() {
      try {
        const mutation = `
          mutation($status_id: ID!) {
            deleteNotificationStatus(status_id: $status_id) {
              status_id
            }
          }
        `;

        const variables = { status_id: this.notificationStatusIdToDelete };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query: mutation, variables }),
        });

        const result = await response.json();

        if (result.data?.deleteNotificationStatus?.status_id) {
          this.showDeleteModal = false;
          this.notificationStatusIdToDelete = null;
          await this.fetchNotificationStatuses();
        } else {
          console.error('Failed to delete notification status.');
        }
      } catch (error) {
        console.error('Error deleting notification status:', error);
      }
    },
  };
}
