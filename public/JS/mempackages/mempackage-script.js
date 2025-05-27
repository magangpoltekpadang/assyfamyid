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
    packageToDelete: null,

    // computed properties mirip serviceTypeData style
    get totalPackages() {
      return this.packages.length;
    },

    get activePackages() {
      return this.packages.filter(p => p.is_active === 1).length;
    },

    get inactivePackages() {
      return this.packages.filter(p => p.is_active === 0).length;
    },

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

        // Filter berdasarkan isActiveFilter jika ada
        if (this.isActiveFilter !== '') {
          const isActiveBool = this.isActiveFilter === '1';
          allPackages = allPackages.filter(p => p.is_active === isActiveBool);
        }

        // Filter search berdasarkan package_name (case insensitive)
        if (this.search) {
          const lowerSearch = this.search.toLowerCase();
          allPackages = allPackages.filter(p =>
            p.package_name.toLowerCase().includes(lowerSearch)
          );
        }

        // Manual pagination
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

    confirmDelete(packageId) {
      this.packageToDelete = packageId;
      this.showDeleteModal = true;
    },

    async deletePackage() {
      try {
        const mutation = `
          mutation($id: ID!) {
            deleteMembershipPackage(id: $id) {
              status
              message
            }
          }
        `;

        const variables = { id: this.packageToDelete };

        const response = await fetch('/graphql', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ query: mutation, variables }),
        });

        const result = await response.json();

        if (result.errors || result.data.deleteMembershipPackage.status !== 'success') {
          console.error('Failed to delete package:', result.errors || result.data.deleteMembershipPackage.message);
          return;
        }

        this.showDeleteModal = false;
        this.packageToDelete = null;
        await this.fetchPackages();
      } catch (error) {
        console.error('Error deleting package:', error);
      }
    },
  };
}
