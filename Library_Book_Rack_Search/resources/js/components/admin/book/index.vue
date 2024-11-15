<template>
    <div class="d-flex align-items-center justify-content-between mb-5">
        <h1>Book Page</h1>

        <router-link to="/api/book/create" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add New
        </router-link>
    </div>

    <div v-if="loading" class="text-center" style="padding: 13rem;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div v-if="!loading && !books.length">
        <p>No books available. Please add some.</p>
    </div>

    <div v-if="books.length">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Category</th>
                    <th scope="col">Author</th>
                    <th scope="col">Duration (days)</th>
                    <th scope="col">Description</th>
                    <th scope="col">Availability</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(book, index) in books" :key="book.id">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <img class="rounded-3" :src="book.image_path" v-if="book.image_path" alt="Book Image" width="150" height="150" />
                            <span class="text-capitalize">{{ book.title ? book.title : '-' }}</span>
                        </div>
                    </td>
                    <td>{{ book.category ? book.category.name : '-' }}</td>
                    <td>{{ book.author ? book.author : '-' }}</td>
                    <td>{{ book.duration ? book.duration : '-' }}</td>
                    <td>{{ book.description ? book.description : '-' }}</td>
                    <td>
                        <span class="text-success" v-if="book.is_available"><span class="fas fa-circle"></span>&nbsp;Available</span>
                        <span class="text-danger" v-else><span class="fas fa-circle"></span>&nbsp;No Available</span>
                    </td>
                    <td>
                        <button class="btn btn-primary" @click="showDetail(book)"><i class="fas fa-search"></i></button>
                        <button class="btn btn-success ms-2" @click="editBook(book)"><i class="fas fa-edit"></i>&nbsp;Edit</button>
                        <button class="btn btn-danger ms-2" @click="deleteBook(book)"><i class="fas fa-trash-alt"></i>&nbsp;Delete</button>
                        <button class="btn btn-secondary ms-2" @click="openBarcodeModal(book)"><i class="fas fa-barcode"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="barcodeModalLabel">Book Barcode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <svg ref="barcodeSvg" width="300" height="100"></svg>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, nextTick } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import Swal from 'sweetalert2';
import JsBarcode from 'jsbarcode';

export default {
    setup() {
        const books = ref([]);
        const loading = ref(false);
        const router = useRouter();
        const selectedBook = ref(null);
        const barcodeSvg = ref(null);

        const getData = async () => {
            loading.value = true;
            try {
                const response = await axios.get('/api/getAllBook?is_admin=true');
                books.value = response.data.data;
            } catch (error) {
                console.error("There was an error fetching books:", error);
            } finally {
                loading.value = false;
            }
        };

        const deleteBook = async (book) => {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: `You want to delete ${book.title ? book.title : ''}!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/api/deleteBook/${book.id}`);
                    Swal.fire({
                        title: 'Deleted successfully',
                        icon: 'success',
                        confirmButtonColor: '#007bff',
                        confirmButtonText: 'Ok'
                    });
                    await getData();
                } catch (error) {
                    console.error("There was an error deleting the book:", error);
                }
            }
        };

        const editBook = (book) => {
            router.push({
                name: 'EditBook',
                params: { id: book.id }
            });
        };

        const openBarcodeModal = (book) => {
            selectedBook.value = book;
            nextTick(() => {
                JsBarcode(barcodeSvg.value, book.id.toString(), {
                    format: 'CODE128',
                    width: 5,
                    height: 40,
                    displayValue: true,
                    fontSize: 16
                });
                const modal = new bootstrap.Modal(document.getElementById('barcodeModal'));
                modal.show();
            });
        };

        const showDetail = (book) => {
            // show book detail
        };

        onMounted(() => {
            getData();
        });

        return {
            books,
            loading,
            getData,
            deleteBook,
            editBook,
            openBarcodeModal,
            showDetail,
            barcodeSvg
        };
    }
};
</script>

<style scoped>
.text-capitalize {
    text-transform: capitalize;
}
</style>
