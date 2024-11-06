<template>
    <div class="d-flex align-items-center justify-content-between mb-5">
        <h1>Book Page</h1>

        <router-link to="/api/book/create" class="btn btn-primary">
          <i class="fas fa-plus-circle"></i> Add New
        </router-link>
    </div>

    <div v-if="loading" class="text-center" style="padding: 13rem;">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-if="!loading && !books.length">
      <p>No books available. Please add some.</p>
    </div>

    <div v-if="books.length">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Category</th>
            <th scope="col">Author</th>
            <th scope="col">Duration (days)</th>
            <th scope="col">Description</th>
            <th scope="col">Availability</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(book, index) in books" :key="book.id">
            <th scope="row">{{ index + 1 }}</th>
            <td>
              <span class="text-capitalize">{{ book.title ? book.title : '-'}}</span>
            </td>
            <td>{{ book.category ? book.category.name : '-' }}</td>
            <td>{{ book.author ? book.author : '-' }}</td>
            <td>{{ book.duration ? book.duration : '-' }}</td>
            <td>{{ book.description ? book.description : '-'}}</td>
            <td>
              <span class="text-success" v-if="book.is_available"><span class="fas fa-circle"></span>&nbsp;Available</span>
              <span class="text-danger" v-else><span class="fas fa-circle"></span>&nbsp;No Available</span>
            </td>
            <td>
              <button class="btn btn-success" @click="editBook(book)"><i class="fas fa-edit"></i>&nbsp;Edit</button>
              <button class="btn btn-danger ms-2" @click="deleteBook(book)"><i class="fas fa-trash-alt"></i>&nbsp;Delete</button>
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
    name: 'Book',
    data() {
        return {
          books: [],
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
          const response = await axios.get('/api/getAllBook');
          this.books = response.data.data;
        } catch (error) {
          console.error("There was an error fetching books:", error);
        } finally {
          this.loading = false;
        }
      },
      async deleteBook(book) {
        const result = await this.$swal.fire({
          title: 'Are you sure?',
          text: `You want to delete ${book.title ? book.title : ''}!`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes',
          cancelButtonText: 'Cancel'
        });

        if(result.isConfirmed){
          try {
            await axios.delete(`/api/deleteBook/${book.id}`);
            await this.getData();
          } catch (error) {
            console.error("There was an error deleting the book:", error);  
          }
        }
      },
      editBook(book){
        // this.$router.push({ 
        //   name: 'EditBook', 
        //   params: { id: book.id } 
        // });
      }
    }
  };
</script>

<style scoped>
  .text-capitalize{
    text-transform: capitalize;
  }
</style>
  