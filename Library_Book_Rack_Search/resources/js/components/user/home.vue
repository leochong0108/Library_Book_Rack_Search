<template>
    <div class="search-area">
        <div class="text-center" style="margin-bottom: 10rem;">
            <h1 class="search-title">Search For Books</h1>
            <span class="text-muted search-description">Fill in the name or the code of the book.</span>
            <div class="d-flex align-items-center justify-content-center gap-3 mt-3">
                <input type="text" v-model="search" class="form-control search-box">
                <button class="btn btn-dark" @click="submitSearch">Submit</button>
            </div>
            <h2>or</h2>
            <div class="d-flex align-items-center justify-content-center gap-3 mt-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scannerModal">Start Scanner</button>
            </div>
        </div>

        <div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scannerModalLabel">Scan Barcode</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="reader" style="width: 100%;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel for New Arrivals -->
        <div class="new-arrival" v-if="books.length > 0">
            <h1 class="text-center mb-5">New Arrival</h1>
            <div class="carousel-container">
                <carousel :autoplay="0" :value="currentIndex" :numVisible="1" :numScroll="1" showIndicators="false">
                    <slide v-for="(book, index) in books" :key="book.id" v-show="currentIndex === index">
                        <div class="carousel-content">
                            <router-link :to="`/api/bookDetail/${book.id}`">
                                <img
                                    :src="book.image_path"
                                    alt="Book Cover"
                                    class="book-image"
                                    style="cursor: pointer;"
                                >
                            </router-link>
                            <h5 class="mt-3 mb-0">{{ book.title }}</h5>
                        </div>
                    </slide>
                </carousel>

                <div class="carousel-controls">
                    <span class="carousel-control-prev" @click="prevSlide" :class="{ disabled: currentIndex === 0 }">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </span>
                    <span class="carousel-control-next" @click="nextSlide" :class="{ disabled: currentIndex === books.length - 1 }">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { Carousel, Slide } from 'vue3-carousel';
import { Html5Qrcode } from 'html5-qrcode';

export default {
    components: {
        Carousel,
        Slide,
    },

    data() {
        return {
            currentIndex: 0,
            search: null,
            html5QrCode: null,
            books: [],
        };
    },

    mounted() {
        this.getBooks();

        // Modal event listeners for controlling the scanner
        const modal = document.getElementById('scannerModal');
        modal.addEventListener('show.bs.modal', this.startScanner);
        modal.addEventListener('hide.bs.modal', this.stopScanner);
    },

    beforeUnmount() {
        this.stopScanner();
    },

methods: {
    submitSearch() {
        if (!this.search) {
            return false;
        }
        // Submit search logic
    },

    nextSlide() {
        if (this.currentIndex < this.books.length - 1) {
            this.currentIndex++;
        } else {
            this.currentIndex = 0; // Loop back to the first slide
        }
    },

    prevSlide() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
        } else {
            this.currentIndex = this.books.length - 1;
        }
    },

    async getBooks() {
        try {
            const response = await axios.get('/api/getAllBook');
            this.books = response.data.data;
        } catch (error) {
            console.error('Failed to fetch books:', error);
        }
    },

    viewDetail(book) {
        this.$router.push({ name: 'bookDetail', params: { id: book.id } });
    },

    onScanSuccess(decodedText) {
        console.log("Scanned code:", decodedText); // Logging the scanned code
        this.stopScanner();

        // Verify the format of `decodedText` and adjust if necessary
        if (decodedText) {
            this.$router.push({ name: 'bookDetail', params: { id: decodedText } });
        } else {
            console.error("Scanned text is empty or undefined.");
        }
    },

    onScanSuccess(decodedText) {
    console.log("Scanned code:", decodedText); // Log the scanned code

    this.stopScanner();

    if (decodedText) {
        // Close the modal
        const modal = document.getElementById('scannerModal');
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        bootstrapModal.hide();

            this.$router.push({ name: 'bookDetail', params: { id: decodedText } });

    } else {
        console.error("Scanned text is empty or undefined.");
    }
},

    startScanner() {
        console.log("Starting scanner...");
        this.html5QrCode = new Html5Qrcode("reader");
        const config = {
            fps: 30,
            qrbox: 500,
        };
        this.html5QrCode.start(
            { facingMode: "environment" },
            config,
            this.onScanSuccess
        ).catch(err => console.error("Scanner failed to start:", err));
    },

    stopScanner() {
        if (this.html5QrCode) {
            console.log("Stopping scanner...");
            this.html5QrCode.stop().catch(err => console.error("Scanner failed to stop:", err)).finally(() => {
                this.html5QrCode = null;
            });
        }
    },
}
};
</script>

<style scoped>
    .search-title {
        font-size: 60px;
        font-weight: bold;
    }

    .search-description {
        font-size: 25px;
    }

    .search-area {
        padding: 10rem;
    }

    .search-box {
        width: 15%;
    }

    .carousel-container {
        position: relative;
    }

    .carousel-content {
        display: flex;
        align-items: center;
        flex-direction: column;
    }

    .book-image {
        max-height: 500px;
        object-fit: cover;
    }

    .carousel-controls {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 100%;
        display: flex;
        justify-content: space-between;
        pointer-events: none;
    }

    .carousel-controls span {
        pointer-events: auto;
        cursor: pointer;
        padding: 10px;
    }

    .carousel__slide::marker{
        content: none;
    }

    #reader {
        width: 100%;
        height: auto;
        border: 1px solid #ccc;
        margin-bottom: 20px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: darkgreen; /* Set a solid background color */
        opacity: 1 !important; /* Make the icon fully opaque */
        border-radius: 50%; /* Optional: to make the icon circular */
        padding: 10px; /* Optional: add padding to increase the clickable area */
    }

    .carousel-control-prev-icon::before,
    .carousel-control-next-icon::before {
        font-size: 30px; /* Adjust the size of the icon */
        color: white; /* Set the color of the arrow icon */
    }
</style>

<style>

    .carousel__liveregion {
        display: none;
    }

</style>
