import { createRouter, createWebHistory } from 'vue-router';
import TenantHome from '@/views/TenantHome.vue';
import HomeownerHome from '@/views/Homeowner/HomeowerHome.vue';
import HomeownerRegister from '@/views/Homeowner/HomeownerRegister.vue'
import UserLogin from "@/views/User/UserLogin.vue";
import UserDashboard from "@/views/User/UserDashboard.vue";
import PropertyRegister from '@/views/Homeowner/AddProperty.vue';
import Folders from '@/views/Homeowner/FoldersList.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'tenant_home',
      component: TenantHome,
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
      name: 'homeowner_home',
      component: HomeownerHome,
    },
    {
      path: '/homeowner/register',
      name: 'homeowner_register',
      component: HomeownerRegister,
    },
    {
      path: '/homeowner/property/add',
      name: 'homeowner_property_add',
      component: PropertyRegister,
    },
    {
      path: '/homeowner/folders',
      name: 'homeowner_folders',
      component: Folders,
    }
  ]
})

export default router
