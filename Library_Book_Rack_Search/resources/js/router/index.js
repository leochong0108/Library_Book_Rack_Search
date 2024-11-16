import { createRouter, createWebHistory } from 'vue-router';

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
import rackHome from '../components/admin/bookRack/rackHome.vue';
import recordHome from '../components/admin/record/recordHome.vue';
import EditBook from '../components/admin/book/edit.vue';

// User
import UserLayout from '../components/user/layout.vue';
import Home from '../components/user/home.vue';
import Search from '../components/user/search.vue';
import Service from '../components/user/service.vue';
import AboutUs from '../components/user/about_us.vue';
import ContactUs from '../components/user/contact_us.vue';
import bookDetail from '../components/user/bookDetail.vue';
import Login from '../components/user/login.vue';
import Register from '../components/user/register.vue';
import BookList from '../components/user/book_list.vue';

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
      {
        path: '/api/rack',
        name: 'rackHome',
        component: rackHome,
      },
      {
        path: '/api/record',
        name: 'recordHome',
        component: recordHome,
      },
      {
        path: '/api/book/:id/edit', // Path for Edit Book
        name: 'EditBook',
        component: EditBook,
        props: true
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
        path: '/api/bookDetail/:id',
        name: 'bookDetail',
        component: bookDetail,
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
      {
        path: '/api/book-list',
        name: 'BookList',
        component: BookList,
      },
      // Add other user routes here
    ],
  },
  {
    path: '/api/user/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/api/user/register',
    name: 'Register',
    component: Register,
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
    scrollBehavior(to, from, savedPosition) {
        // Always scroll to top
        return { top: 0 };
    },
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('access_token');
  const userRole = localStorage.getItem('user_role');
  const is_user_logout = localStorage.getItem('is_user_logout') || false;

  const publicRoutes = ['Login', 'Register', 'AdminLogin', 'AdminRegister'];

  if (isAuthenticated) {

    if (userRole === 'admin') {
      // If the admin is already on the admin dashboard, allow navigation
      if (to.name === 'Dashboard') {
        return next();
      }

      // Redirect admin to dashboard if they're navigating to other routes
      if (!to.path.startsWith('/api')) {
        return next({ name: 'Dashboard' });
      }

      return next();
    }

    if (userRole === 'user') {
      // If the user is already on the home page, allow navigation
      if (to.name === 'Home') {
        return next();
      }

      // Redirect users to home if they try to access admin routes
      if (to.path.startsWith('/api')) {
        return next();
      }

      return next(); // Allow navigation within user routes
    }

    return next(); // Proceed for other roles if defined
  }

  // If not authenticated, allow navigation to login, or redirect to login if trying to access a protected route
  if (publicRoutes.includes(to.name)) {
    return next();
  }

  if (!is_user_logout) {
    return next({ name: 'Login' });
  }

  return next();
});

export default router;
