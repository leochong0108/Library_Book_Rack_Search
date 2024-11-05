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
          </ul>
  
          <button class="btn btn-sign-in">Sign In</button>
          <button class="btn btn-dark">Register</button>
        </div>
      </div>
    </nav>
  </template>

  <script>
  export default {
    name: 'Footer',
    data() {
        return {
          //
        };
    },
    methods: {
      //
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