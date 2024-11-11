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

        <!-- Modal for Scanner -->
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
                            <img :src="book.image" alt="Book Cover" class="book-image" @click="viewDetail(book)">
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
            books: [
                {
                    id: 1,
                    title: 'The Great Gatsby',
                    author: 'F. Scott Fitzgerald',
                    description: 'A novel about the American dream.',
                    image: 'https://m.media-amazon.com/images/I/91oy4zw56KL._AC_UF350,350_QL50_.jpg',
                    publishedYear: 1925,
                },
                {
                    id: 2,
                    title: 'To Kill a Mockingbird',
                    author: 'Harper Lee',
                    description: 'A novel about racial injustice in the South.',
                    image: 'https://m.media-amazon.com/images/I/91oy4zw56KL._AC_UF350,350_QL50_.jpg',
                    publishedYear: 1960,
                },
                {
                    id: 3,
                    title: '1984',
                    author: 'George Orwell',
                    description: 'A dystopian novel set in a totalitarian society.',
                    image: 'https://img-cdn.pixlr.com/image-generator/history/65bb506dcb310754719cf81f/ede935de-1138-4f66-8ed7-44bd16efc709/medium.webp',
                    publishedYear: 1949,
                },
            ],
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

        getBooks() {
            // Fetch books logic
        },

        viewDetail(book) {
            // View book detail logic
        },

        onScanSuccess(decodedText) {
            this.stopScanner();
            this.$router.push({ name: 'bookDetail', params: { id: decodedText } });
        },

        startScanner() {
            this.html5QrCode = new Html5Qrcode("reader");
            const config = {
                fps: 10,
                qrbox: 350,
            };
            this.html5QrCode.start(
                { facingMode: "environment" },
                config,
                this.onScanSuccess
            ).catch(err => console.error(err));
        },

        stopScanner() {
            if (this.html5QrCode) {
                this.html5QrCode.stop().catch(err => console.error(err)).finally(() => {
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

</style>

<style>

    .carousel__liveregion {
        display: none;
    }

</style>
