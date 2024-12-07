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

        <div class="form-group mb-3">
            <label class="mb-2">Book Rack</label>
            <v-select :options="book_racks" v-model="form.book_rack_id" label="id" :reduce="rack => rack.id" placeholder="Select a rack" @change="updateRackLayers"></v-select>
        </div>

        <div class="form-group mb-3">
            <label class="mb-2">Book Rack Layer</label>
            <v-select :options="rack_layers" v-model="form.rack_layer" label="label" :reduce="layer => layer.value" placeholder="Select a layer"></v-select>
        </div>

        <div class="form-group text-end">
            <button class="btn btn-primary" @click="submit" :disabled="is_submit">Submit</button>
        </div>
    </div>
</template>

<script>
    import { ref, reactive, onMounted, watch } from 'vue';
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
                category_id: '',
                book_rack_id: '',
                rack_layer: ''
            });
    
            const categories = ref([]);
            const is_submit = ref(false);
            const errors = ref({});
            const router = useRouter();
            const book_racks = ref([]);
            const rack_layers = ref([]);
    
            const getCategory = async () => {
                try {
                    const res = await axios.get('/api/getBookCategory');
                    categories.value = res.data.categories;
                    book_racks.value = res.data.book_racks;
                } catch (error) {
                    console.error('Error fetching categories:', error);
                }
            };

            const updateRackLayers = (rackId) => {
                const selectedRack = book_racks.value.find(rack => rack.id === rackId);

                if (selectedRack && selectedRack.rack_layer) {
                    rack_layers.value = Array.from({ length: selectedRack.rack_layer }, (_, i) => ({
                        value: i + 1,
                        label: `Layer ${i + 1}`
                    }));
                } else {
                    rack_layers.value = [];
                }
            };

            watch(
                () => form.book_rack_id,
                (newRackId) => {
                    updateRackLayers(newRackId);
                }
            );
    
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
                    formData.append('book_rack_id', form.book_rack_id);
                    formData.append('rack_layer', form.rack_layer);
    
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
                handleFileUpload,
                book_racks,
                rack_layers,
                updateRackLayers,
            };
        }
    };
    </script>