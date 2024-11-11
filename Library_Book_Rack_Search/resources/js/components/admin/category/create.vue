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
    import { ref, reactive } from 'vue';
    import { useRouter } from 'vue-router';
    import axios from 'axios';
    import Swal from 'sweetalert2';
    
    export default {
        setup(props, { emit }) {
            const form = reactive({
                name: ''
            });
            const errors = reactive({});
            const is_submit = ref(false);
            const router = useRouter();
    
            const submit = async () => {
                if (is_submit.value) return;
    
                is_submit.value = true;
    
                try {
                    await axios.post('/api/createCategory', form);
    
                    Swal.fire({
                        title: 'Added successfully',
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'Ok'
                    });
    
                    form.name = '';
                    for (let key in errors) errors[key] = null;
    
                    router.push('/api/category');
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        const errorData = error.response.data.errors;
                        Object.keys(errorData).forEach(key => {
                            errors[key] = errorData[key];
                        });
                    } else {
                        console.error('Error adding category:', error);
                    }
                } finally {
                    is_submit.value = false;
                }
            };
    
            return {
                form,
                errors,
                is_submit,
                submit
            };
        }
    };
</script>

<style scoped>
/* Add styles as needed */
</style>
