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
    setup() {
        const router = useRouter();

        onMounted(() => {
            const isAuthenticated = !!localStorage.getItem('access_token');
            const userRole = 'user'; // localStorage.getItem('user_role')

            if (isAuthenticated) {
                if (userRole === 'admin') {
                    router.push('/api/index'); // Redirect to admin dashboard
                } else {
                    router.push('/api/home'); // Redirect to user home
                }
            } else {
                router.push('/api'); // Redirect to login if not authenticated
            }
        });
    },
};
</script>

<style>
/* Add some global styles here if needed */
</style>
