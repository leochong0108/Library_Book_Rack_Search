<template>
    <div class="sidebar bg-light">
      <ul class="nav flex-column">
        <li class="nav-item" :class="{ current: isActive('/api/index') }">
          <router-link to="/api/index" class="nav-link text-dark" active-class="active">Dashboard</router-link>
        </li>
        <li class="nav-item" :class="{ current: isActive('/api/category') }">
    		<router-link to="/api/category" class="nav-link text-dark" active-class="active">Category</router-link>
        </li>
        <li class="nav-item" :class="{ current: isActive('/api/book') }">
          <router-link to="/api/book" class="nav-link text-dark" active-class="active">Book</router-link>
        </li>
        <li class="nav-item" :class="{ current: isActive('/api/rack') }">
          <router-link to="/api/rack" class="nav-link text-dark" active-class="active">Book Rack</router-link>
        </li>
        <li class="nav-item" :class="{ current: isActive('/api/record') }">
          <router-link to="/api/record" class="nav-link text-dark" active-class="active">Record</router-link>
        </li>
        <!-- Add more items as needed -->
      </ul>
    </div>
  </template>

  <script>
  export default {
    name: 'AdminSidebar',
    methods: {
      isActive(path) {
        return this.$route.path === path || this.$route.path.startsWith(path + '/');  // Check if the given path matches the current route path
      },
    },
  };
  </script>

  <style scoped>
  .sidebar {
    width: 250px;
    position: fixed;
    height: 100%;
  }
  </style>
