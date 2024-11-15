<template>

<div class ="container justify-content-center" style="margin: 100px;">
    <div class="row" style="margin-right: 0px;">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <img :src="books.image_path" alt="Book Image" class="img-fluid" v-if="books.image_path" width="250" height="500"/>
                    </div>
                    <div class="col-md-5">
                        <h1 class="card-title" style="color:red">{{ books.title || 'Book Title' }}</h1>
                        <h6 class="card-text">{{ books.description || 'Description not available.' }}</h6>
                        <span class="text-success" v-if="books.is_available"><span class="fas fa-circle"></span>&nbsp;Available</span>
                        <span class="text-danger" v-else><span class="fas fa-circle"></span>&nbsp;No Available</span>
                        <dl>
                            <dt>author</dt>
                            <dd>{{ books.author || 'Unknown' }}</dd>
                            <dt>description</dt>
                            <dd>{{ books.description || 'Not specified' }}</dd>
                        </dl>
                        <hr />
                        <button class="btn btn-danger" :disabled="!books.is_available" @click="rentBook(books.id)" style="margin-right: 20px;" v-if="!is_admin">Rent Now</button>
                        <button class="btn btn-danger" :disabled="books.is_available" @click="returnBook(books.id)">Return Now</button>
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </div>
    </div>
</div>

</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import {  useRouter, useRoute } from 'vue-router';
import Swal from 'sweetalert2';

export default {
    setup() {
        const books = ref([]);
        const error = ref(null);
        const router = useRouter();
        const route = useRoute();
        const is_admin = ref(false);

        const getBook = async () => {
            const bookId = route.params.id;
            try {
                console.log(`/api/findBookByScan/${route.params.id}`);
                const response = await axios.get(`/api/findBookByScan/${bookId}`);
                books.value = response.data.data;
            } catch (err) {
                error.value = err.response?.data?.message || 'Error fetching books';
            }
        };

        const rentBook = async (id) => {

            if (!localStorage.getItem('access_token')) {
                const result = await Swal.fire({
                    title: 'Reminder',
                    text: 'Please login first!',
                    icon: 'info',
                    confirmButtonColor: '#007bff',
                    confirmButtonText: 'Login Now'
                });

                if (result.isConfirmed) {
                    router.push('/api/user/login');
                }
                return;
            }

            try {
                const response = await axios.post(`/api/rentBook/${id}`, {}, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('access_token')}`
                    }
                });
                debugger;

                Swal.fire({
                    title: 'Rented successfully',
                    icon: 'success',
                    confirmButtonColor: '#007bff',
                    confirmButtonText: 'Ok'
                });

                //books.value = response.data.data;
            } catch (err) {
                error.value = err.response?.data?.message || 'Error fetching books';
            }
            router.push('/api/home');
        };

        const returnBook = async (id) => {
            try {
                const response = await axios.post(`/api/returnBook/${id}`);
                Swal.fire('Updated!', 'return book completed', 'success');
            } catch (err) {
                error.value = err.response?.data?.message || 'Error return book'
                Swal.fire('Error', err.response?.data?.message , 'error');
            }
            getBook();
        }


        onMounted(() => {
            if (localStorage.getItem('user_role') == 'admin') {
                is_admin.value = true;
            }
            getBook();
        });

        return {
            books,
            error,
            is_admin,
            getBook,
            rentBook,
            returnBook,
        };
    }
};
</script>
