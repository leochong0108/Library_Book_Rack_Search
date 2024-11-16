<template>
    <div style="padding: 3rem;">
        <h2 class="mb-5">Book List</h2>

        <div v-if="loading" class="text-center" style="padding: 13rem;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-if="!loading && !rented_books.length">
            No rented books found. Feel free to rent some books.
        </div>

        <div v-if="rented_books.length" class="row">
            <div v-for="(record, index) in rented_books" :key="record.id" class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <router-link :to="`/api/bookDetail/${record.book_id}`">
                        <img :src="record.book.image_path" class="card-img-top" alt="Book Image" width="250" height="450" v-if="record.book.image_path" />
                    </router-link>

                    <span class="badge text-bg-danger expired-badge" v-if="isExpired(record.expired_at)">Expired</span>

                    <div class="card-body">
                        <h5 class="card-title text-capitalize">{{ record.book.title || '-' }}</h5>
                        <p class="card-text">Expired At:
                            <span :class="{'text-danger': isExpired(record.expired_at)}">
                                {{ $moment(record.expired_at).format('YYYY-MM-DD HH:mm A') }}
                            </span>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary" @click="returnBook(record.book_id)">
                                Return Now
                            </button>
                        </div>
                    </div>
                </div>
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
        setup() {
            const router = useRouter();
            const rented_books = ref([]);
            const loading = ref(false);

            const isExpired = (expiredAt) => {
                const currentDate = new Date();
                const expiredDate = new Date(expiredAt);
                return expiredDate < currentDate;
            };

            const returnBook = async (book_id) => {
                const response = await axios.post(`/api/returnBook/${book_id}`, {}, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('access_token')}`
                    }
                });

                Swal.fire('Updated!', 'return book completed', 'success');
            }

            const getBookList = async () => {
                loading.value = true;
                try {
                    const response = await axios.get('/api/getRentedBook', {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('access_token')}`
                        }
                    });

                    rented_books.value = response.data.rented_books

                } catch (error) {
                    console.error('Error fetching books:', error);
                }  finally {
                    loading.value = false;
                }
            };

            onMounted(async () => {
                await getBookList();
            });

            return {
                rented_books,
                loading,
                isExpired,
                returnBook
            };
        }
    };
</script>

<style scoped>
    .expired-badge{
        position: absolute;
        top: 6px;
        right: 6px;
    }
</style>
