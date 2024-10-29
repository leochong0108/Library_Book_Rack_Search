<template>
    <div>
      <button @click="showAlert" class="btn btn-primary">Show Alert</button>      
    </div>
  </template>
  
  <script>
  import { ref, onMounted, getCurrentInstance } from 'vue';
  import axios from 'axios';
  
  export default {
    setup() {
      const { appContext } = getCurrentInstance();

      const showAlert = () => {
        appContext.config.globalProperties.$swal.fire({
            title: 'Are you sure?',
            text: 'You won’t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // do something
            }
        });
        };
  
      return { showAlert };
    }
  };
  </script>