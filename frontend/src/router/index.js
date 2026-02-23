import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import RecipeDetails from '../views/RecipeDetail.vue';
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue';
import api from '../services/api';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/recipe/:id',
    name: 'RecipeDetails',
    component: RecipeDetails,
    props: true,
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/admin/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }, 
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
        top: 80,
      };
    }
    return savedPosition || { top: 0 };
  },
});

router.beforeEach(async (to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);

  if (requiresAuth) {
    try {
      await api.getUser();
      next();
    } catch (error) {
      console.warn("Unauthorized access - Redirecting to Login");
      next({ name: 'Login' });
    }
  } else {
    next();
  }
});

export default router;