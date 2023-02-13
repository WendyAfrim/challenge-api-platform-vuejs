import { createRouter, createWebHistory } from 'vue-router';
import Base from '../components/Base.vue';
import HomeView from '../views/HomeView.vue';
import RegisterView from '../views/RegisterView.vue';
import PropertyRequestsView from "@/views/Homeowner/PropertyRequestsView.vue";
import ViewingView from "@/views/Homeowner/ViewingView.vue";
import VisitsProposalsView from "@/views/Homeowner/VisitsProposalsView.vue";
import LoginView from "@/views/LoginView.vue";
import TenantDashboardView from "@/views/Tenant/DashboardView.vue";
import HomeownerDashboardView from "@/views/Homeowner/DashboardView.vue";
import AgencyDashboardView from "@/views/Agency/DashboardView.vue";
import RequestNewLinkView from "@/views/RequestNewLinkView.vue";
import PropertyRegister from '@/views/Property/AddProperty.vue';
import { useAuthStore } from '@/stores/auth.store';
import PropertyPhotosUploadViews from "@/views/PropertyPhotosUploadViews.vue";
import WizardViewVue from '@/views/Tenant/WizardView.vue';
import ShowUserView from '@/views/Agency/ShowUserView.vue';
import ViewingsView from '@/views/Agency/ViewingsView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '',
      name: 'tenant',
      component: Base,
      meta: {
        forType: 'tenant',
        location: 'front'
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
          component: TenantDashboardView,
        },
        {
          path: 'first-steps',
          name: 'tenant_first_steps',
          component: WizardViewVue,
        },
      ],
    },
    {
      path: '/homeowner',
      component: Base,
      meta: {
        forType: 'homeowner',
        location: 'dashboard'
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
          component: HomeownerDashboardView,
        },
        {
          path: 'property/add',
          name: 'homeowner_property_add',
          component: PropertyRegister,
        },
        {
          path: 'property/requests/:id',
          name: 'homeowner_property_requests',
          component: PropertyRequestsView,
        },
        {
          path: 'viewings/:ownerId',
          name: 'homeowner_viewings',
          component: ViewingView,
        },
        {
          path: 'lodger/:id/visits/proposals/:propertyId',
          name: 'homeowner_visits_proposals',
          component: VisitsProposalsView
        },
        {
          path: 'property/:id/add/photos',
          name: 'homeowner_property_add_photos',
          component: PropertyPhotosUploadViews,
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
          component: AgencyDashboardView,
        },
        {
          path: 'users/:id',
          name: 'agency_show_user',
          component: ShowUserView,
        },
        {
          path: 'viewings',
          name: 'agency_viewings',
          component: ViewingsView,
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

router.beforeEach(async (to) => {
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
    if (to.name === 'logout') return true;
    const role = authStore.getRole;
    if (role === 'tenant') {
      if (authStore.user.validation_status === 'to_complete' && to.name !== 'tenant_first_steps') {
        return { name: 'tenant_first_steps' };
      }
      if (authStore.user.validation_status !== 'to_complete' && to.name === 'tenant_first_steps') {
        return { name: 'tenant_dashboard' };
      }
    }
    if (to.meta.public || (to.meta.forType && to.meta.forType !== role)) {
      return { name: `${role}_dashboard` }
    }
    return true;
  }
  return to.meta.public ? true : { name: 'tenant_login' }
})

export default router