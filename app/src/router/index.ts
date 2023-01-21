import { createRouter, createWebHistory } from 'vue-router';
import Base from '../components/Base.vue';
import HomeView from '../views/HomeView.vue';
import RegisterView from '../views/RegisterView.vue';
import UserLogin from "@/views/User/UserLogin.vue";
import UserDashboard from "@/views/User/UserDashboard.vue";


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '',
      name: 'tenant',
      component: Base,
      meta: {
        forType: 'tenant',
      },
      children: [
        {
          path: '',
          name: 'tenant_home',
          component: HomeView,
        },
        {
          path: 'register',
          name: 'tenant_signup',
          component: RegisterView,
        },
      ],
    },
    {
      path: '/login',
      name: 'login',
      component: UserLogin,
    },
    {
      path: '/user/dashboard',
      name: 'user_dashboard',
      component: UserDashboard,
    },
    {
      path: '/homeowner',
      name: 'homeowner',
      component: Base,
      meta: {
        forType: 'homeowner',
      },
      children: [
        {
          path: '',
          name: 'homeowner_home',
          component: HomeView,
        },
        {
          path: 'register',
          name: 'homeowner_signup',
          component: RegisterView,
        },
      ],
    },
  ]
})

export default router
