import { createRouter, createWebHistory } from 'vue-router';
//import PostList from '../components/PostList.vue';

// Admin
import AdminLogin from '../components/admin/login.vue';
import AdminRegister from '../components/admin/register.vue';
import AdminLayout from '../components/admin/layout.vue';
import AdminDashboard from '../components/admin/dashboard.vue';

import Category from '../components/admin/category/index.vue';
import CreateCategory from '../components/admin/category/create.vue';
import EditCategory from '../components/admin/category/edit.vue';

import Book from '../components/admin/book/index.vue';
import CreateBook from '../components/admin/book/create.vue';

// User
import UserLayout from '../components/user/layout.vue';
import Home from '../components/user/home.vue';
import Search from '../components/user/search.vue';
import Service from '../components/user/service.vue';
import AboutUs from '../components/user/about_us.vue';
import ContactUs from '../components/user/contact_us.vue';

const adminRoutes = [
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
      {
        path: '/api/category/:id/edit', // Path for Edit Category
        name: 'EditCategory',
        component: EditCategory,
        props: true
      },
      {
        path: '/api/book', // Path for Book
        name: 'Book',
        component: Book,
      },
      {
          path: '/api/book/create', // Path for Add Book
          name: 'CreateBook',
          component: CreateBook,
      },
      // Add more child routes as needed
    ]
  },
];

// User Routes
const userRoutes = [
  {
    path: '/api/home',
    component: UserLayout, // Use UserLayout as the parent component
    children: [
      {
        path: '',
        name: 'Home',
        component: Home,
      },
      {
        path: '/api/search',
        name: 'Search',
        component: Search,
      },
      {
        path: '/api/service',
        name: 'Service',
        component: Service,
      },
      {
        path: '/api/about-us',
        name: 'AboutUs',
        component: AboutUs,
      },
      {
        path: '/api/contact-us',
        name: 'ContactUs',
        component: ContactUs,
      },
      // Add other user routes here
    ],
  },
];

// Combine Routes
const routes = [
  ...adminRoutes,
  ...userRoutes,
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
