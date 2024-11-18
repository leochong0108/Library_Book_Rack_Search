<template>

<div class ="container justify-content-center" style="margin: 100px;">
    <div class="row" style="margin-right: 0px;">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-5 mt-2">
                        <img :src="books.image_path" alt="Book Image" class="img-fluid" v-if="books.image_path" width="300" height="500"/>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex align-items-center">
                            <h1 class="card-title text-danger mb-0">
                                {{ books.title || 'Book Title' }}
                            </h1>
                            <span class="ms-3 mt-2">
                                <span class="text-success" v-if="books.is_available">
                                    <span class="fas fa-circle"></span>&nbsp;Available
                                </span>
                                <span class="text-danger" v-else>
                                    <span class="fas fa-circle"></span>&nbsp;Not Available
                                </span>
                            </span>
                        </div>
                        <div class="d-flex align-items-center">
                            <h6 class="card-text mt-3">{{ books.description || 'Description not available.' }}</h6>
                        </div>
                        <hr>
                        <dl>
                            <dt>
                                Author
                                <i
                                    class="fas fa-info-circle info-icon"
                                    @mouseenter="showTooltip('author', $event)"
                                    @mouseleave="hideTooltip"
                                ></i>
                            </dt>
                            <dd>{{ books.author || 'Unknown' }}</dd>

                            <dt>
                                Category
                                <i
                                    class="fas fa-info-circle info-icon"
                                    @mouseenter="showTooltip('category', $event)"
                                    @mouseleave="hideTooltip"
                                ></i>
                            </dt>
                            <dd>{{ books.category?.name || 'Unknown' }}</dd>

                            <dt>
                                Library Floor
                                <i
                                    class="fas fa-info-circle info-icon"
                                    @mouseenter="showTooltip('floor', $event)"
                                    @mouseleave="hideTooltip"
                                ></i>
                            </dt>
                            <dd>{{ books.floor || 'Not specified' }}</dd>

                            <dt>
                                Book Rack ID
                                <i
                                    class="fas fa-info-circle info-icon"
                                    @mouseenter="showTooltip('book_rack_id', $event)"
                                    @mouseleave="hideTooltip"
                                ></i>
                            </dt>
                            <dd>{{ books.book_rack_id || 'Not specified' }}</dd>

                            <dt>
                                Rack Layer
                                <i
                                    class="fas fa-info-circle info-icon"
                                    @mouseenter="showTooltip('rack_layer', $event)"
                                    @mouseleave="hideTooltip"
                                ></i>
                            </dt>
                            <dd>{{ books.rack_layer || 'Not specified' }}</dd>

                            <dt>
                                Position in Rack Layer
                                <i
                                    class="fas fa-info-circle info-icon"
                                    @mouseenter="showTooltip('location_id', $event)"
                                    @mouseleave="hideTooltip"
                                ></i>
                            </dt>
                            <dd>{{ books.location_id || 'Not specified' }}</dd>


                        </dl>

                        <!-- Tooltip -->
                        <div v-if="showInfo" :style="tooltipStyle" class="tooltip-box">
                            {{ tooltipText }}
                        </div>

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
import { inject } from 'vue';

export default {
    setup() {
        const books = ref([]);
        const error = ref(null);
        const router = useRouter();
        const route = useRoute();
        const is_admin = ref(false);
        const showInfo = ref(false);
        const tooltipText = ref('');
        const tooltipStyle = ref({});
        const countRentedBook = inject('countRentedBook');

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

                await countRentedBook();

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
        };

        const explanations = {
            author: 'The author of the book.',
            category: 'The category or genre of the book.',
            book_rack_id: 'The unique identifier of the book rack where the book is stored.',
            rack_layer: 'The specific layer or shelf of the rack where the book is located.',
            floor: 'The floor of the library where the book rack is located.',
            location_id: 'The exact position of the book within the rack layer. The number count from left to right',
        };

        const showTooltip = (key, event) => {
            tooltipText.value = explanations[key];
            tooltipStyle.value = {
                top: `${event.clientY + 30}px`,
                left: `${event.clientX + 50}px`,
            };
            showInfo.value = true;
        };

        const hideTooltip = () => {
            showInfo.value = false;
        };


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
            showInfo,
            tooltipText,
            tooltipStyle,
            showTooltip,
            hideTooltip,
        };
    }
};
</script>

<style>
.info-icon {
    margin-left: 5px;
    color: #007bff;
    cursor: pointer;
}

.tooltip-box {
    position: fixed;
    background-color: #333;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    font-size: 14px;
    z-index: 1000;
    white-space: nowrap;
    pointer-events: none;
    transform: translate(-50%, -50%);
}

</style>
