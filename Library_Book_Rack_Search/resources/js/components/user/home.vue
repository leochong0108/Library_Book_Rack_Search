<template>
    <div class="search-area">
       <div class="text-center" style="margin-bottom: 10rem;">
            <h1 class="search-title">Search For Books</h1>
            <span class="text-muted search-description">Fill in the name or the code of the book.</span>
            <div class="d-flex align-items-center justify-content-center gap-3 mt-3">
                <input type="text" v-model="search" class="form-control search-box">
                <button class="btn btn-dark" @click="submitSearch">Submit</button>
            </div>
        </div>
        
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

  export default {
    components: {
      Carousel,
      Slide,
    },
    data() {
        return {
          currentIndex: 0,
          search: null,
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
    mounted(){
      this.getBooks();
    },
    methods: {
        submitSearch(){
            if(!this.search){
              return false
            }
            // const response = axios.post('/api/');
        },
        nextSlide() {
          if(this.currentIndex < this.books.length - 1){
            this.currentIndex++;
          }else{
            this.currentIndex = 0; // Loop back to the first slide
          }
        },
        prevSlide() {
          if(this.currentIndex > 0){
            this.currentIndex--;
          }else{
            this.currentIndex = this.books.length - 1
          }
        },
        getBooks(){
          // const response = axios.get('/api/');
        },
        viewDetail(book) {
          // book detail
        }
    }
  };
  </script>

<style scoped>
  .search-title{
    font-size: 60px;
    font-weight: bold;
  }

  .search-description{
    font-size: 25px;
  }

  .search-area{
    padding: 10rem;
  }

  .search-box{
    width: 15%;
  }

  .carousel-container {
    position: relative; /* Positioning context for absolute elements */
  }

  .carousel-content {
    display: flex; /* Use flexbox to align items */
    align-items: center; /* Center items vertically */
    flex-direction: column;
  }

  .book-image {
    max-height: 500px;
    object-fit: cover;
  }

  .carousel-controls {
    position: absolute; /* Position the controls absolutely */
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Adjust to center properly */
    width: 100%; /* Full width for controls */
    display: flex; /* Flexbox for controls alignment */
    justify-content: space-between; /* Space controls to edges */
    pointer-events: none; /* Prevent pointer events on wrapper */
  }

  .carousel-controls span {
    pointer-events: auto;
    cursor: pointer;
    padding: 10px; /* Padding around icons */
  }

  .carousel__slide::marker{
    content: none;
  }
</style>

<style>
  .carousel__liveregion {
    display: none;
  }
</style>
