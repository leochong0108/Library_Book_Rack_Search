<template>
    <div>
        <h2>Edit Book</h2>

        <hr>

        <div v-if="loading" class="text-center" style="padding: 13rem;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div v-else>
            <div v-if="previous_image">
                <img :src="previous_image" alt="Book Image" class="rounded-3 mb-3" width="150" height="150" />
            </div>

            <div class="form-group mb-3">
                <label class="mb-2">Book Title</label>
                <input type="text" class="form-control" v-model="form.title" placeholder="Enter book title" />
            </div>

            <div class="form-group mb-3">
                <label class="mb-2">New Book Image</label>
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
                <label class="mb-2">Book Location ID</label>
                <input type="text" class="form-control" v-model="form.location_id" placeholder="Enter Book Location ID"/>
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
                <v-select :options="book_racks" v-model="form.book_rack_id" label="id" :reduce="rack => rack.id" placeholder="Select a rack"></v-select>
            </div>

            <div class="form-group mb-3">
                <label class="mb-2">Book Rack Layer</label>
                <v-select :options="rack_layers" v-model="form.rack_layer" label="label" :reduce="layer => layer.value" placeholder="Select a layer"></v-select>
            </div>

            <div class="form-group text-end">
                <button class="btn btn-primary" @click="submit" :disabled="is_submit">Submit</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { ref, reactive, onMounted, watch } from 'vue';
    import { useRouter } from 'vue-router';
    import axios from 'axios';
    import Swal from 'sweetalert2';

    export default {
        props: ['id'],
        setup(props) {
            const loading = ref(true);
            const form = reactive({
                title: '',
                duration: '',
                author: '',
                location_id: '',
                image: null,
                description: '',
                category_id: '',
                book_rack_id: '',
                rack_layer: ''
            });

            const previous_image = ref(null);
            const errors = reactive({});
            const is_submit = ref(false);
            const categories = ref([]);
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
                const selectedRack = book_racks.value.find(rack => Number(rack.id) === Number(rackId));

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

            const getBookData = async () => {
                if (props.id) {
                    loading.value = true;
                    try {
                        const response = await axios.get(`/api/getBook/${props.id}`);
                        const book = response.data.book;

                        if (book.title) form.title = book.title;
                        if (book.author) form.author = book.author;
                        if (book.location_id) form.location_id = book.location_id;
                        if (book.description) form.description = book.description;
                        if (book.duration) form.duration = book.duration;
                        if (book.category_id) form.category_id = book.category_id;
                        if (book.image_path) previous_image.value = book.image_path;
                        if (book.book_rack_id) form.book_rack_id = book.book_rack_id;
                        if (book.rack_layer) form.rack_layer = book.rack_layer;

                    } catch (error) {
                        console.error("There was an error fetching book:", error);
                    } finally {
                        loading.value = false;
                    }
                }
            };

            const submit = async () => {
                if (is_submit.value) return;

                is_submit.value = true;

                try {
                    const formData = new FormData();

                    if (form.title) formData.append('title', form.title);
                    if (form.author) formData.append('author', form.author);
                    if (form.location_id) formData.append('location_id', form.location_id);
                    if (form.description) formData.append('description', form.description);
                    if (form.duration) formData.append('duration', form.duration);
                    if (form.category_id) formData.append('category_id', form.category_id);
                    if (form.image) formData.append('image', form.image);
                    if (form.book_rack_id) formData.append('book_rack_id', form.book_rack_id);
                    if (form.rack_layer) formData.append('rack_layer', form.rack_layer);

                    await axios.post(`/api/updateBook/${props.id}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    });

                    await Swal.fire({
                        title: 'Updated successfully',
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'Ok'
                    });

                    router.push('/api/book');
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        const errors = error.response.data.errors;
                        Object.assign(errors, error.response.data.errors);
                    } else {
                        console.error('Error updating book:', error);
                    }
                } finally {
                    is_submit.value = false;
                }
            };

            const handleFileUpload = (event) => {
                form.image = event.target.files[0];
            };

            onMounted(async () => {
                await getCategory();
                await getBookData();
            });

            return {
                loading,
                form,
                errors,
                is_submit,
                categories,
                previous_image,
                submit,
                handleFileUpload,
                book_racks,
                rack_layers,
                updateRackLayers,
            };
        }
    };
</script>

<style scoped>
/* Add styles as needed */
</style>
