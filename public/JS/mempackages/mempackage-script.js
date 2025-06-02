function membershipPackageData() {
  return {
    packages: [],
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
    isActiveFilter: '',
    showDeleteModal: false,
    packageIdToDelete: null,

    init() {
      this.fetchPackages();
    },

    async fetchPackages() {
      try {
        const query = `
          query {
            membershipPackages {
              package_id
              package_name
              duration_days
              price
              max_vehicles
              is_active
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

        let allPackages = result.data.membershipPackages || [];

        if (this.isActiveFilter !== '') {
          const isActiveNum = parseInt(this.isActiveFilter);
          allPackages = allPackages.filter(p => p.is_active === isActiveNum);
        }

        if (this.search) {
          const lowerSearch = this.search.toLowerCase();
          allPackages = allPackages.filter(p =>
            p.package_name.toLowerCase().includes(lowerSearch)
          );
        }

        const start = (this.pagination.current_page - 1) * this.pagination.per_page;
        const end = start + this.pagination.per_page;
        this.packages = allPackages.slice(start, end);

        this.pagination.total = allPackages.length;
        this.pagination.last_page = Math.ceil(this.pagination.total / this.pagination.per_page);
        this.pagination.from = start + 1;
        this.pagination.to = Math.min(end, this.pagination.total);
      } catch (error) {
        console.error('Error fetching membership packages:', error);
      }
    },

    async changePage(page) {
      if (page === '...' || isNaN(page)) return;
      this.pagination.current_page = parseInt(page);
      await this.fetchPackages();
    },

    async previousPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        await this.fetchPackages();
      }
    },

    async nextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.pagination.current_page++;
        await this.fetchPackages();
      }
    },

    async resetFilters() {
      this.search = '';
      this.isActiveFilter = '';
      this.pagination.current_page = 1;
      await this.fetchPackages();
    },

    confirmDelete(id) {
      this.packageIdToDelete = id;
      this.showDeleteModal = true;
    },

    async deletePackage() {
      try {
        const mutation = `
          mutation($package_id: ID!) {
            deleteMembershipPackage(package_id: $package_id) {
              package_id
            }
          }
        `;

        const variables = {
          package_id: this.packageIdToDelete,
        };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query: mutation, variables }),
        });

        const result = await response.json();

        if (result.data?.deleteMembershipPackage?.package_id) {
          this.showDeleteModal = false;
          this.packageIdToDelete = null;
          await this.fetchPackages();
        } else {
          console.error('Failed to delete membership package.');
        }
      } catch (error) {
        console.error('Error deleting membership package:', error);
      }
    },
  };
}
