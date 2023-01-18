import { createRouter, createWebHistory } from 'vue-router';
import TenantHome from '@/views/TenantHome.vue';
import HomeownerHome from '@/views/HomeowerHome.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'tenant_home',
      component: TenantHome,
    },
    {
      path: '/proprietaire',
      name: 'homeowner_home',
      component: HomeownerHome,
    },
  ]
})

export default router
