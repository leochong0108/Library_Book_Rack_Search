<template>
    <div>
        <h2>Add New Category</h2>

        <hr>

        <div class="form-group mb-3">
            <label class="mb-3">Category Name</label>
            <input type="text" class="form-control" v-model="form.name" placeholder="Enter category name" />
        </div>

        <div class="form-group text-end">
            <button class="btn btn-primary" @click="submit">Submit</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            form: {
                name: ''
            }
        };
    },
    methods: {
        async submit() {
            try {
                await axios.post('/api/createCategory', this.form);

                this.$router.push('/api/category'); // Redirect to the category page after adding
            } catch (error) {
                console.error('Error adding category:', error);
            }
        }
    }
};
</script>

<style scoped>
/* Add styles as needed */
</style>
