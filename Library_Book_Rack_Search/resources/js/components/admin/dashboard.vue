<template>
    <div>
      <h1 class="mb-5">Dashboard Page</h1>

      <div v-if="loading" class="text-center" style="padding: 13rem;">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <div class="dashboard" v-else>
        <div class="card" @click="goTo('category')">
          <div class="card-icon">
            <i class="fas fa-folder"></i>
          </div>
          <div class="card-info">
            <h3>Categories</h3>
            <p>{{ countCategory }}</p>
          </div>
        </div>

        <div class="card" @click="goTo('book')">
          <div class="card-icon">
            <i class="fas fa-book"></i>
          </div>
          <div class="card-info">
            <h3>Books</h3>
            <p>{{ countBook }}</p>
          </div>
        </div>

        <div class="card" @click="goTo('rack')">
          <div class="card-icon">
            <i class="fas fa-boxes-stacked"></i>
          </div>
          <div class="card-info">
            <h3>Book Racks</h3>
            <p>{{ countBookRack }}</p>
          </div>
        </div>

        <div class="card" @click="goTo('record')">
          <div class="card-icon">
            <i class="fas fa-clipboard-list"></i>
          </div>
          <div class="card-info">
            <h3>Records</h3>
            <p>{{ countRecord }}</p>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  export default {
    name: 'Dashboard',
    setup() {
      const countCategory = ref(null);
      const countBook = ref(null);
      const countBookRack = ref(null);
      const countRecord = ref(null);
      const loading = ref(false);
      const router = useRouter();
  
      const getData = async () => {
        loading.value = true;
        try {
          const response = await axios.get('/api/getDashboardData');
          countCategory.value = response.data.count_category;
          countBook.value = response.data.count_book;
          countBookRack.value = response.data.count_book_rack;
          countRecord.value = response.data.count_record;
        } catch (error) {
          console.error("Failed to fetch data:", error);
        } finally {
          loading.value = false;
        }
      };

      const goTo = async (page) => {
        router.push(`/api/${page}`);
      }
  
      onMounted(() => {
        getData();
      });
  
      return {
        loading,
        countCategory,
        countBook,
        countBookRack,
        countRecord,
        goTo
      };
    }
  };
</script>

<style scoped>
  .dashboard {
    display: flex;
    gap: 1rem;
  }

  .card {
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 1rem;
    width: 150px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .card-icon {
    font-size: 2rem;
    color: #4CAF50;
    margin-bottom: 0.5rem;
  }

  .card-info h3 {
    margin: 0;
    font-size: 1rem;
    color: #555;
  }

  .card-info p {
    margin: 0;
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
  }

  .card:hover{
    cursor: pointer;
  }
</style>
    


