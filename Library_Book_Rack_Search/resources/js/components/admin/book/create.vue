<template>
    <div>
        <h2>Add New Book</h2>

        <hr>

        <div class="form-group mb-3">
            <label class="mb-2">Book Title</label>
            <input type="text" class="form-control" v-model="form.title" placeholder="Enter book title" />
        </div>

        <div class="form-group mb-3">
            <label class="mb-2">Book Image</label>
            <input type="file" class="form-control" @change="handleFileUpload" />
        </div>

        <div class="form-group mb-3">
            <label class="mb-2">Book Description</label>
            <input type="text" class="form-control" v-model="form.description" placeholder="Enter book description" />
        </div>

        <div class="form-group mb-3">
            <label class="mb-2">Book Author</label>
            <input type="text" class="form-control" v-model="form.author" placeholder="Enter book author"/>
        </div>

        <div class="form-group mb-3">
            <label class="mb-2">Duration</label>
            <div class="input-group">
                <input type="number" class="form-control" v-model="form.duration" min="1" />
                <label class="input-group-text">day(s)</label>
            </div>
        </div>

        <div class="form-group mb-3">
            <label class="mb-2">Category</label>
            <v-select :options="categories" v-model="form.category_id" label="name" :reduce="category => category.id" placeholder="Select a category"></v-select>
        </div>

        <div class="form-group text-end">
            <button class="btn btn-primary" @click="submit" :disabled="is_submit">Submit</button>
        </div>
    </div>
</template>

<script>
    import { ref, reactive, onMounted } from 'vue';
    import { useRouter } from 'vue-router';
    import axios from 'axios';
    import Swal from 'sweetalert2';

    export default {
        setup() {
            const form = reactive({
                title: '',
                description: '',
                author: '',
                image: null,
                duration: '',
                category_id: ''
            });
    
            const categories = ref([]);
            const is_submit = ref(false);
            const errors = ref({});
            const router = useRouter();
    
            const getCategory = async () => {
                try {
                    const res = await axios.get('/api/getBookCategory');
                    categories.value = res.data.categories;
                } catch (error) {
                    console.error('Error fetching categories:', error);
                }
            };
    
            const handleFileUpload = (event) => {
                form.image = event.target.files[0];
            };
    
            const submit = async () => {
                if (is_submit.value) return;
    
                is_submit.value = true;
                errors.value = {};
    
                try {
                    const formData = new FormData();
    
                    formData.append('title', form.title);
                    formData.append('author', form.author);
                    formData.append('description', form.description);
                    formData.append('duration', form.duration);
                    formData.append('category_id', form.category_id);
    
                    if (form.image) {
                        formData.append('image', form.image);
                    }
    
                    await axios.post('/api/createBook', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    });
    
                    Swal.fire({
                        title: 'Added successfully',
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'Ok'
                    });
    
                    router.push('/api/book');
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        errors.value = error.response.data.errors;
                    } else {
                        console.error('Error adding book:', error);
                    }
                } finally {
                    is_submit.value = false;
                }
            };
    
            onMounted(() => {
                getCategory();
            });
    
            return {
                form,
                categories,
                is_submit,
                errors,
                submit,
                handleFileUpload
            };
        }
    };
    </script>