<template>
    <div class="d-flex align-items-center justify-content-between mb-5">
      <h1>Category Page</h1>

      <router-link to="/api/category/create" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Add New
      </router-link>
    </div>

    <div v-if="loading" class="text-center" style="padding: 13rem;">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-if="!loading && !categories.length">
      <p>No categories available. Please add some.</p>
    </div>

    <div v-if="categories.length">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(category, index) in categories" :key="category.id">
              <th scope="row">{{ index + 1 }}</th>
              <td>
                {{ category.name }}
                <button class="btn btn-secondary btn-sm ms-1" @click="copyToClipboard(category.name, index)"> <i :class="copyIcon(index)"></i></button>
              </td>
              <td>
                <button class="btn btn-success" @click="editCategory(category)"><i class="fas fa-edit"></i>&nbsp;Edit</button>
                <button class="btn btn-danger ms-2" @click="deleteCategory(category)"><i class="fas fa-trash-alt"></i>&nbsp;Delete</button>
              </td>
          </tr>
        </tbody>
      </table>
    </div>
</template>

  <script>
  import axios from 'axios';

  export default {
    components: {
      //
    },
    name: 'Category',
    data() {
        return {
          categories: [],
          copiedIndex: null,
          loading: false
        };
    },
    async mounted() {
      await this.getData();
    },
    methods: {
      async getData(){
        this.loading = true;
        try {
          const response = await axios.get('/api/getAllCategory');
          this.categories = response.data.data;
        } catch (error) {
          console.error("There was an error fetching categories:", error);
        } finally {
          this.loading = false;
        }
      },
      async copyToClipboard(text, index) {
        try {
          await navigator.clipboard.writeText(text);
          this.copiedIndex = index; // Set the copied index
          setTimeout(() => {
              this.copiedIndex = null; // Reset after 2 seconds
          }, 2000);
        } catch (err) {
          console.error('Failed to copy: ', err);
        }
      },
      copyIcon(index) {
        return this.copiedIndex === index ? 'fas fa-copy' : 'far fa-copy'; // Determine icon class
      },
      async deleteCategory(category) {
        const result = await this.$swal.fire({
          title: 'Are you sure?',
          text: `You want to delete ${category.name ? category.name : ''}!`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes',
          cancelButtonText: 'Cancel'
        });

        if(result.isConfirmed){
          try {
            await axios.delete(`/api/deleteCategory/${category.id}`);

            this.$swal.fire({
                title: 'Deleted successfully',
                icon: 'success',
                confirmButtonColor: '#007bff',
                confirmButtonText: 'Ok'
            });

            await this.getData();
          } catch (error) {
            console.error("There was an error deleting the category:", error); 
          }
        }
      },
      editCategory(category){
        this.$router.push({ 
          name: 'EditCategory', 
          params: { id: category.id } 
        });
      }
    }
  };
  </script>
  <style scoped>

  </style>
