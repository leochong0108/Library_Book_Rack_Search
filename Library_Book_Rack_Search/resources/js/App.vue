<!-- resources/js/App.vue -->
<template>
    <!-- <h1>TESTING</h1> -->
    <div id="app">
        <router-view></router-view> <!-- Where the routed components will be displayed -->
    </div>
</template>

<script>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';

export default {
    name: 'App',
};
</script>

<style>
/* Add some global styles here if needed */
</style>
