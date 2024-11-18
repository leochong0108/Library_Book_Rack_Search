<!-- resources/js/App.vue -->
<template>
    <!-- <h1>TESTING</h1> -->
    <div id="app">
        <router-view></router-view> <!-- Where the routed components will be displayed -->
    </div>
</template>

<script>
import { ref, provide, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

export default {
    name: 'App',
    setup() {
        const count_rented_book = ref(0);

        const countRentedBook = async () => {
            try {
                const res = await axios.get('/api/countRentedBook', {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('access_token')}`,
                }
                });
                count_rented_book.value = res.data.count_rented_book;
            } catch (error) {
                console.error('Error fetching rented book count:', error);
            }
        };

        provide('countRentedBook', countRentedBook);
        provide('count_rented_book', count_rented_book);

        onMounted(() => {
            if (localStorage.getItem('access_token')) {
                countRentedBook();
            }
        });

        return {
            count_rented_book,
            countRentedBook
        };
    }
};
</script>

<style>
/* Add some global styles here if needed */
</style>
