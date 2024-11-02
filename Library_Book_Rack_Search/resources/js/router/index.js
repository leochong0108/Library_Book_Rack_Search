import { createRouter, createWebHistory } from 'vue-router';
//import PostList from '../components/PostList.vue';

// Admin
import AdminLogin from '../components/admin/login.vue';
import AdminRegister from '../components/admin/register.vue';
import AdminLayout from '../components/admin/layout.vue';
import AdminDashboard from '../components/admin/dashboard.vue';
import Category from '../components/admin/category/index.vue';
import CreateCategory from '../components/admin/category/create.vue';

const routes = [
  //  { path: '/', component: Home },
   {
    path: '/api',
    name: 'AdminLogin',
    component: AdminLogin
   },
   {
    path: '/api/register',
    name: 'AdminRegister',
    component: AdminRegister
   },
   {
    path: '/api/index',
    component: AdminLayout, // Parent layout component
    children: [
      {
        path: '', // Default path
        name: 'Dashboard',
        component: AdminDashboard,
      },
      {
        path: '/api/category', // Path for Category
        name: 'Category',
        component: Category,
      },
      {
          path: '/api/category/create', // Path for Add Category
          name: 'CreateCategory',
          component: CreateCategory,
      },
      // Add more child routes as needed
    ]
  },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
