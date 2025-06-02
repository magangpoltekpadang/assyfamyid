function notificationTypeData() {
  return {
    notificationTypes: [],
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0,
    },
    search: '',
    isActiveFilter: '',
    showDeleteModal: false,
    notificationTypeIdToDelete: null,

    init() {
      this.fetchNotificationTypes();
    },

    async fetchNotificationTypes() {
      try {
        const query = `
          query {
            notificationTypes {
              notification_type_id
              type_name
              code
              template_text
              is_active
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

        // Ambil array penuh dari response
        let allNotificationTypes = result.data.notificationTypes || [];

        // Filter berdasarkan status aktif / nonaktif jika ada filter
        if (this.isActiveFilter !== '') {
          const filterBool = this.isActiveFilter === '1' || this.isActiveFilter === 'true';
          allNotificationTypes = allNotificationTypes.filter(nt => nt.is_active === filterBool);
        }

        // Filter pencarian case insensitive di type_name atau code
        if (this.search) {
          const lowerSearch = this.search.toLowerCase();
          allNotificationTypes = allNotificationTypes.filter(nt =>
            (nt.type_name && nt.type_name.toLowerCase().includes(lowerSearch)) ||
            (nt.code && nt.code.toLowerCase().includes(lowerSearch))
          );
        }

        // Pagination manual
        const start = (this.pagination.current_page - 1) * this.pagination.per_page;
        const end = start + this.pagination.per_page;
        this.notificationTypes = allNotificationTypes.slice(start, end);

        // Update pagination info
        this.pagination.total = allNotificationTypes.length;
        this.pagination.last_page = Math.max(1, Math.ceil(this.pagination.total / this.pagination.per_page));
        this.pagination.from = this.pagination.total > 0 ? start + 1 : 0;
        this.pagination.to = Math.min(end, this.pagination.total);

      } catch (error) {
        console.error('Error fetching notification types:', error);
      }
    },

    async changePage(page) {
      if (page === '...' || isNaN(page)) return;
      this.pagination.current_page = parseInt(page);
      await this.fetchNotificationTypes();
    },

    async previousPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        await this.fetchNotificationTypes();
      }
    },

    async nextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.pagination.current_page++;
        await this.fetchNotificationTypes();
      }
    },

    async resetFilters() {
      this.search = '';
      this.isActiveFilter = '';
      this.pagination.current_page = 1;
      await this.fetchNotificationTypes();
    },

    confirmDelete(id) {
      this.notificationTypeIdToDelete = id;
      this.showDeleteModal = true;
    },

    async deleteNotificationType() {
      try {
        const mutation = `
          mutation($notification_type_id: ID!) {
            deleteNotificationType(notification_type_id: $notification_type_id) {
              notification_type_id
            }
          }
        `;

        const variables = { notification_type_id: this.notificationTypeIdToDelete };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query: mutation, variables }),
        });

        const result = await response.json();

        if (result.data?.deleteNotificationType?.notification_type_id) {
          this.showDeleteModal = false;
          this.notificationTypeIdToDelete = null;
          await this.fetchNotificationTypes();
        } else {
          console.error('Failed to delete notification type.');
        }
      } catch (error) {
        console.error('Error deleting notification type:', error);
      }
    },
  };
}
