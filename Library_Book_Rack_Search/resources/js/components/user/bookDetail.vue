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
                            <h1 class="card-title text-danger mb-0 text-capitalize">
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
                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <h6 class="card-text mb-0">{{ books.description || 'Description not available.' }}</h6>
                            <button class="btn btn-primary" @click="openMapModal()"><i class="fas fa-map-marker-alt"></i> Book Location</button>
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

    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scannerModalLabel">Book Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="books.book_rack_id">
                        <h5 class="ms-2">Floor {{books.book_rack.floor}}</h5>
                        <div class="d-flex flex-wrap" v-for="(row, rowIndex) in chunkedRacks" :key="rowIndex">
                            <div class="p-3 border rounded text-center flex-fill rack" v-for="rack in row" :key="rack.id" :class="{ 'current-rack': rack.id == books.book_rack_id }" @click="viewRack(rack.id)"
                            >
                                <h5 class="mb-0">{{ rack.name }}</h5>
                            </div>
                        </div>
                    </div>

                    <div v-if="is_detail">
                        <hr>
                        <div class="d-flex flex-wrap" >
                            <div v-for="rack in layers" :key="rack.id" class="rack-container border rounded p-3 me-3">
                                <div v-for="layer in rack.layers.slice().reverse()" :key="layer.id" class="rack-layer border p-2 mb-2" :class="{ 'current-layer': layer.id == books.rack_layer }"
                                >
                                    <p>Layer {{ layer.id }}</p>
                                    <div class="layer-books d-flex flex-wrap">
                                        <span class="book-item border rounded me-2 p-1 text-capitalize" v-if="layer.id == books.rack_layer"
                                        >
                                            {{ books.title }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> 
</div>

</template>

<script>
import { ref, onMounted, computed, nextTick } from 'vue';
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
        const is_detail = ref(false);

        const layers = ref([
            {
                id: 1,
                layers: [
                    { id: 1, books: ["Book A", "Book B"] },
                    { id: 2, books: ["Book C"] },
                    { id: 3, books: [] },
                ],
            },
        ]);

        const racks = ref([
            { id: 1, name: 'Book Rack 1' },
            { id: 2, name: 'Book Rack 2' },
            { id: 3, name: 'Book Rack 3' },
            { id: 4, name: 'Book Rack 4' },
            { id: 5, name: 'Book Rack 5' },
            { id: 6, name: 'Book Rack 6' },
        ]);

        const racksPerRow = ref(3);

        const chunkArray = (array, chunkSize) => {
            const chunks = [];
            for (let i = 0; i < array.length; i += chunkSize) {
                chunks.push(array.slice(i, i + chunkSize));
            }
            return chunks;
        };

        const chunkedRacks = computed(() => chunkArray(racks.value, racksPerRow.value));

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

        const viewRack = (rack_id) => {
            if(rack_id != books.value.book_rack_id){
                is_detail.value = false;
                return false
            }

            is_detail.value = !is_detail.value;
        }

        const openMapModal = () => {
            nextTick(() => {
                const modal = new bootstrap.Modal(document.getElementById('mapModal'));
                modal.show();
            });
        };

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
            chunkedRacks,
            layers,
            is_detail,
            openMapModal,            
            viewRack
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

.current-rack {
    background-color: #ffc107 !important;
    color: #fff;
    font-weight: bold;
}

.rack-container {
    width: 250px;
    background-color: #f8f9fa;
}

.rack-layer {
    background-color: #e9ecef;
}

.layer-books {
    flex-wrap: wrap;
}

.book-item {
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    margin-bottom: 5px;
    display: inline-block;
}

.current-layer {
    background-color: #ffc107 !important;
    color: white;
}

.rack{
    margin: 5px;
    background-color: #007bff;
}

.rack:hover{
    cursor: pointer;
}
</style>
