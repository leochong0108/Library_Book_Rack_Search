<template>
    <div>
        <h2>Add New Category</h2>

        <hr>

        <div class="form-group mb-3">
            <label class="mb-3">Category Name</label>
            <input type="text" class="form-control" v-model="form.name" placeholder="Enter category name" />
            <span v-if="errors.name" class="text-danger">{{ errors.name[0] }}</span>
        </div>

        <div class="form-group text-end">
            <button class="btn btn-primary" @click="submit" :disabled="is_submit">Submit</button>
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
            },
            errors: {},
            is_submit: false
        };
    },
    methods: {
        async submit() {

            if (this.is_submit) return;

            this.is_submit = true;

            try {
                await axios.post('/api/createCategory', this.form);

                this.$swal.fire({
                    title: 'Added successfully',
                    icon: 'success',
                    confirmButtonColor: '#007bff',
                    confirmButtonText: 'Ok'
                });

                this.$router.push('/api/category'); // Redirect to the category page after adding
            } catch (error) {
                if(error.response && error.response.status === 422){
                    const errors = error.response.data.errors;
                    this.errors = errors;
                }else{
                    console.error('Error adding category:', error);
                }
            } finally {
                this.is_submit = false; // Reset after submission is complete
            }
        }
    }
};
</script>

<style scoped>
/* Add styles as needed */
</style>
