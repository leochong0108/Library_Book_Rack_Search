<template>
    <nav class="navbar navbar-expand-lg navbar-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item me-2">
              <a class="nav-link" href="#">Profile</a>
            </li>
            <li class="nav-item d-flex">
                <div class="btn-logout">
                    <a class="nav-link d-flex align-items-center gap-2" href="#" @click="logout">
                        <span>Log Out</span>
                        <div class="circle-wrapper">
                          <i class="fas fa-sign-out-alt"></i>
                        </div>
                    </a>
                </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </template>

<script>
import { ref, onMounted, getCurrentInstance } from 'vue';
import axios from 'axios';

export default {
    name: 'AdminNavbar',
    methods: {
      async logout(){
        const result = await this.$swal.fire({
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
                    await axios.post('/api/logout', {});

                    if (localStorage.getItem('access_token')) {
                      localStorage.removeItem('access_token');
                    }

                    await this.$swal.fire({
                        title: 'Logout successful',
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'Ok'
                    });
                    this.$router.push('/api');
                } catch (error) {
                    console.error('Logout failed:', error);
                    this.$swal.fire({
                        title: 'Error',
                        text: 'Logout failed. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Okay'
                    });
                }
            }
      }
    }
}
</script>

<style scoped>
  .btn-logout{
    border-bottom-right-radius: 20px;
    border-top-right-radius: 20px;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    background-color: lightgray;
    color: white;
  }

  .circle-wrapper {
    width: 25px; 
    height: 25px;
    border-radius: 50%;
    background-color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    color: gray;
  }

  .navbar{
    border-bottom: 1px solid lightgray;
  }
</style>
  