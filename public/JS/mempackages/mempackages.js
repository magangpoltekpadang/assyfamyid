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
          query,
          variables,
        }),
      });
      return await response.json();
    } catch (error) {
      console.error('GraphQL Error:', error);
      return { errors: [error] };
    }
  }

  Alpine.data('membershipPackageData', () => ({
    packages: [],
    loading: false,
    error: null,
    search: '',
    is_active: null, // filter status aktif: true/false/null

    async init() {
      await this.fetchPackages();
    },

    async fetchPackages() {
      this.loading = true;
      this.error = null;

      const query = `
        query GetMembershipPackages($search: String, $is_active: Boolean) {
          membershipPackages(search: $search, is_active: $is_active) {
            package_id
            package_name
            duration_days
            price
            max_vehicles
            description
            is_active
            created_at
            updated_at
          }
        }
      `;

      const variables = {};
      if (String(this.search).trim() !== '') variables.search = this.search;
      if (this.is_active !== null) variables.is_active = this.is_active;

      const result = await executeQuery(query, variables);

      if (result.errors) {
        this.error = result.errors[0].message || "Unknown error";
        console.error('GraphQL Error:', result.errors);
        this.packages = [];
      } else {
        // Safe access jika result.data atau membershipPackages undefined
        this.packages = result.data?.membershipPackages || [];
      }

      this.loading = false;
    },

    async filterByActiveStatus(value) {
      this.is_active = value;
      await this.fetchPackages();
    },

    async searchPackages() {
      await this.fetchPackages();
    },

    async createPackage(newPackage) {
      const mutation = `
        mutation CreatePackage($input: MembershipPackageInput!) {
          createMembershipPackage(input: $input) {
            package_id
            package_name
          }
        }
      `;

      const variables = { input: newPackage };

      const result = await executeQuery(mutation, variables);

      if (result.errors) {
        this.error = result.errors[0].message || "Unknown error";
        console.error('GraphQL Error:', result.errors);
        return null;
      } else {
        this.packages.push(result.data.createMembershipPackage);
        return result.data.createMembershipPackage;
      }
    },

    async updatePackage(package_id, input) {
      const mutation = `
        mutation UpdatePackage($package_id: ID!, $input: MembershipPackageInput!) {
          updateMembershipPackage(package_id: $package_id, input: $input) {
            package_id
            package_name
          }
        }
      `;

      const variables = { package_id, input };

      const result = await executeQuery(mutation, variables);

      if (result.errors) {
        this.error = result.errors[0].message || "Unknown error";
        console.error('GraphQL Error:', result.errors);
        return null;
      } else {
        const updated = result.data.updateMembershipPackage;
        const index = this.packages.findIndex(p => p.package_id === updated.package_id);
        if (index !== -1) this.packages[index] = updated;
        return updated;
      }
    },

    async deletePackage(package_id) {
      const mutation = `
        mutation DeletePackage($package_id: ID!) {
          deleteMembershipPackage(package_id: $package_id) {
            package_id
          }
        }
      `;

      const variables = { package_id };

      const result = await executeQuery(mutation, variables);

      if (result.errors) {
        this.error = result.errors[0].message || "Unknown error";
        console.error('GraphQL Error:', result.errors);
        return false;
      } else {
        this.packages = this.packages.filter(p => p.package_id !== package_id);
        return true;
      }
    },
  }));

  Alpine.start();
});
