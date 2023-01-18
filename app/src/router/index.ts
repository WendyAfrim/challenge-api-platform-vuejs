import { createRouter, createWebHistory } from 'vue-router';
import TenantHome from '@/views/TenantHome.vue';
import HomeownerHome from '@/views/HomeowerHome.vue';
import OwnerView from '../views/OwnerView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'tenant_home',
      component: TenantHome,
    },
    {
      path: '/homeowner',
      name: 'homeowner_home',
      component: HomeownerHome,
    },
    {
      path: '/homeowner/register',
      name: 'homeowner_register',
      component: OwnerView,
    }
  ]
})

export default router
