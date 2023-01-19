import { createRouter, createWebHistory } from 'vue-router';
import Base from '../components/Base.vue';
import HomeView from '../views/HomeView.vue';
import RegisterView from '../views/RegisterView.vue';
import LoginView from "@/views/LoginView.vue";
import DashboardView from "@/views/DashboardView.vue";
import RequestNewLinkView from "@/views/RequestNewLinkView.vue";
import PropertyRegister from '@/views/Property/AddProperty.vue';
import { useAuthStore } from '@/stores/auth.store';

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
          meta: {
            public: true,
          },
        },
        {
          path: 'register',
          name: 'tenant_signup',
          component: RegisterView,
          meta: {
            public: true,
          },
        },
        {
          path: 'login',
          name: 'tenant_login',
          component: LoginView,
          meta: {
            public: true,
          },
        },
        {
          path: 'dashboard',
          name: 'tenant_dashboard',
          component: DashboardView,
        },
      ],
    },
    {
      path: '/homeowner',
      component: Base,
      meta: {
        forType: 'homeowner',
      },
      children: [
        {
          path: '',
          name: 'homeowner_home',
          component: HomeView,
          meta: {
            public: true,
          },
        },
        {
          path: 'register',
          name: 'homeowner_signup',
          component: RegisterView,
          meta: {
            public: true,
          },
        },
        {
          path: 'login',
          name: 'homeowner_login',
          component: LoginView,
          meta: {
            public: true,
          },
        },
        {
          path: 'dashboard',
          name: 'homeowner_dashboard',
          component: DashboardView,
        },
        {
          path: 'property/add',
          name: 'homeowner_property_add',
          component: PropertyRegister,
        }
      ],
    },
    {
      path: '/admin',
      component: Base,
      meta: {
        forType: 'agency',
      },
      children: [
        {
          path: 'login',
          name: 'agency_login',
          component: LoginView,
          meta: {
            public: true,
          },
        },
        {
          path: 'dashboard',
          name: 'agency_dashboard',
          component: DashboardView,
        },
      ],
    },
    {
      path: '/logout',
      name: 'logout',
      component: {
        beforeRouteEnter(to, from, next) {
          const authStore = useAuthStore();
          authStore.logout();
          const alert = { type: 'info', message: 'Vous vous êtes bien déconnecté' };
          next({ name: 'tenant_login', params: { alert: JSON.stringify(alert) } });
        }
      },
    },
    {
      path: '/email-verification-new-link',
      name: 'email_verification_new_link',
      component: RequestNewLinkView,
      meta: {
        public: true,
      },
    },
  ]
})

router.beforeEach(async (to, from) => {
  const authStore = useAuthStore();
  if (authStore.access_token) {
    if (authStore.isTokenExpired()) {
      try {
        await authStore.refreshAccessToken();
        router.push(to.path);
      } catch (error) {
        authStore.logout();
        return { name: 'tenant_login' }
      }
    }
    const role = authStore.getRole;
    if (to.meta.public || (to.meta.forType && to.meta.forType !== role)) {
      return { name: `${role}_dashboard` }
    }
    return true;
  }
  return to.meta.public ? true : { name: 'tenant_login' }
})

export default router
