<template>
    <div>
        <h2>Edit Category</h2>

        <hr>

        <div v-if="loading" class="text-center" style="padding: 13rem;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div v-else>
            <div class="form-group mb-3">
                <label class="mb-3">Category Name</label>
                <input type="text" class="form-control" v-model="form.name" placeholder="Enter category name"/>
            </div>
    
            <div class="form-group text-end">
                <button class="btn btn-primary" @click="submit">Submit</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['id'],
    data() {
        return {
            loading: true,
            form: {
                name: ''
            }
        };
    },
    async created() {
        if (this.id) {
            this.loading = true; // Set loading state to true

            try {
                const response = await axios.get(`/api/getCategory/${this.id}`);
                const category = response.data.category;
                this.form.name = category.name;
            } catch (error) {
                console.error("There was an error fetching category:", error);
            } finally {
                this.loading = false; // Set loading state to false
            }
        }
    },
    methods: {
        async submit() {
            try {
                await axios.put(`/api/updateCategory/${this.id}`, this.form);

                this.$swal.fire({
                    title: 'Update successful',
                    icon: 'success',
                    confirmButtonColor: '#007bff',
                    confirmButtonText: 'Ok'
                });

                this.$router.push('/api/category'); // Redirect to the category page after adding
            } catch (error) {
                console.error('Error updating category:', error);
            }
        }
    }
};
</script>

<style scoped>
/* Add styles as needed */
</style>
