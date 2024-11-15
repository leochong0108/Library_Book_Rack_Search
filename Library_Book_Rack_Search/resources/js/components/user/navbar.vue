<template>
    <nav class="navbar navbar-expand-lg p-3">
      <div class="d-flex align-items-center justify-content-between w-100">
        <div>
          <a class="navbar-brand" href="#"><router-link to="/api/home" class="text-decoration-none text-dark">Library Book Rack Search</router-link></a>
        </div>
       
        <div class="d-flex align-items-center gap-3">
          <ul class="navbar-nav me-auto my-2 my-lg-0 text-dark" style="--bs-scroll-height: 100px;">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#"><router-link to="/api/home" class="text-decoration-none text-dark">Home</router-link></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><router-link to="/api/search" class="text-decoration-none text-dark">Search</router-link></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><router-link to="/api/service" class="text-decoration-none text-dark">Service</router-link></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><router-link to="/api/about-us" class="text-decoration-none text-dark">About Us</router-link></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><router-link to="/api/contact-us" class="text-decoration-none text-dark">Contact Us</router-link></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><router-link to="/api/book-list" class="text-decoration-none text-dark">Book List <span class="badge text-bg-primary" v-if="count_rented_book > 0">{{count_rented_book}}</span></router-link></a>
            </li>
          </ul>
  
          <router-link to="/api/user/login" class="btn btn-sign-in" v-if="!is_login">Sign In</router-link>
          <button class="btn btn-primary" @click="logout" v-else>Log Out</button>
          <router-link to="/api/user/register" class="btn btn-dark" v-if="!is_login">Register</router-link>
        </div>
      </div>
    </nav>
</template>

<script>
  import { ref, reactive, onMounted } from 'vue';
  import { useRouter } from 'vue-router';
  import axios from 'axios';
  import Swal from 'sweetalert2';

  export default {
    setup(){
      const is_login = ref(false);
      const count_rented_book = ref(null);
      const router = useRouter();

      const logout = async () => {
        const result = await Swal.fire({
          title: 'Are you sure?',
          text: 'You want to logout!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes',
          cancelButtonText: 'Cancel'
        });
  
        if (result.isConfirmed) {
          try {
            await axios.post('/api/logout', {}, {
              headers: {
                Authorization: `Bearer ${localStorage.getItem('access_token')}`,
              }
            });

            ['access_token', 'user_role', 'user_data'].forEach(item => {
              if (localStorage.getItem(item)) {
                localStorage.removeItem(item);
              }
            });

            localStorage.setItem('is_user_logout', true);

            is_login.value = false;

            router.replace({ path: router.currentRoute.value.fullPath });

            await Swal.fire({
              title: 'Logout successful',
              icon: 'success',
              confirmButtonColor: '#007bff',
              confirmButtonText: 'Ok'
            });
            
          } catch (error) {
            console.error('Logout failed:', error);
            Swal.fire({
                title: 'Error',
                text: 'Logout failed. Please try again.',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Okay'
            });
          }
        }
      }

      const countRentedBook = async () => {
          try {
            const res = await axios.get('/api/countRentedBook', {
              headers: {
                Authorization: `Bearer ${localStorage.getItem('access_token')}`,
              }
            });

            count_rented_book.value = res.data.count_rented_book
          } catch (error) {
            console.error('Error fetching records:', error);
          }
      };

      onMounted(async () => {
        if (localStorage.getItem('access_token')) {
          is_login.value = true;

          countRentedBook();
        }
      });

      return {
        is_login,
        count_rented_book,
        logout
      };
    }
  }; 
</script>

<style scoped>
  .btn-sign-in{
    background-color: lightgray;
    border: white;
    color: black;
  }
</style>