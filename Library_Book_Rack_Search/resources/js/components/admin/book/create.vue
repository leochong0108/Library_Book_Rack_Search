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
            <input type="text" class="form-control" v-model="form.description" />
        </div>

        <div class="form-group mb-3">
            <label class="mb-2">Book Author</label>
            <input type="text" class="form-control" v-model="form.author" />
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
                    title: '',
                    duration: '',
                    author: '',
                    image: null,
                    description: '',
                    category_id: '',
                },
                categories: []
            };
        },
        mounted(){
            this.getCategory();
        },
        methods: {
            async submit() {
                try {
                    await axios.post('/api/createBook', this.form);
                    
                    this.$router.push('/api/book'); // Redirect to the category page after adding
                } catch (error) {
                    console.error('Error adding book:', error);
                }
            },
            handleFileUpload(event){
                this.form.image = event.target.files[0];
            },
            async getCategory(){
                try {
                    const res = await axios.get('/api/getBookCategory');
                    this.categories = res.data.categories;
                } catch (error) {
                    console.error('Error fetching categories:', error);
                }
            }
        }
    };
    </script>