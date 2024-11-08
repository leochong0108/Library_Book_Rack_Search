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
                <span v-if="errors.name" class="text-danger">{{ errors.name[0] }}</span>
            </div>
    
            <div class="form-group text-end">
                <button class="btn btn-primary" @click="submit" :disabled="is_submit">Submit</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { ref, reactive, onMounted } from 'vue';
    import { useRouter } from 'vue-router';
    import axios from 'axios';
    import Swal from 'sweetalert2';
    
    export default {
        props: ['id'],
        setup(props) {
            const loading = ref(true);
            const form = reactive({ 
                name: '' 
            });
            const errors = reactive({});
            const is_submit = ref(false);
            const router = useRouter();
    
            const fetchCategory = async () => {
                try {
                    const response = await axios.get(`/api/getCategory/${props.id}`);
                    form.name = response.data.category.name;
                } catch (error) {
                    console.error("There was an error fetching category:", error);
                } finally {
                    loading.value = false;
                }
            };
    
            onMounted(fetchCategory);
    
            const submit = async () => {
                if (is_submit.value) return;
                is_submit.value = true;
    
                try {
                    await axios.put(`/api/updateCategory/${props.id}`, form);
    
                    Swal.fire({
                        title: 'Updated successfully',
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'Ok'
                    });
    
                    router.push('/api/category');
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        Object.assign(errors, error.response.data.errors);
                    } else {
                        console.error('Error updating category:', error);
                    }
                } finally {
                    is_submit.value = false;
                }
            };
    
            return {
                loading,
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
