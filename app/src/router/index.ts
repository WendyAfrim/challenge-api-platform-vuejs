import { createRouter, createWebHistory } from "vue-router";
import Base from "../components/Base.vue";
import HomeView from "../views/HomeView.vue";
import RegisterView from "../views/RegisterView.vue";
import PropertyRequestsView from "@/views/Homeowner/PropertyRequestsView.vue";
import ViewingView from "@/views/Homeowner/ViewingView.vue";
import RequestSlotsProposalsView from "@/views/Homeowner/RequestSlotsProposalsView.vue";
import LoginView from "@/views/LoginView.vue";
import NotFoundView from "@/views/NotFoundView.vue";
import TenantDashboardView from "@/views/Tenant/DashboardView.vue";
import TenantRequestsView from "@/views/Tenant/TenantRequestsView.vue";
import TenantPropertiesView from "@/views/Tenant/TenantPropertiesView.vue";
import TenantRequestSlotsView from "@/views/Tenant/TenantRequestSlotsView.vue";

import HomeownerDashboardView from "@/views/Homeowner/DashboardView.vue";
import HomeownerRequestsView from "@/views/Homeowner/HomeownerRequestsViews.vue";
import AgencyDashboardView from "@/views/Agency/DashboardView.vue";
import RequestNewLinkView from "@/views/RequestNewLinkView.vue";
import PropertyRegister from "@/views/Homeowner/AddPropertyView.vue";
import { useAuthStore } from "@/stores/auth.store";
import PropertyPhotosUploadViews from "@/views/PropertyPhotosUploadViews.vue";
import WizardViewVue from "@/views/Tenant/WizardView.vue";
import ShowUserView from "@/views/Agency/ShowUserView.vue";
import ViewingsView from "@/views/Agency/ViewingsView.vue";
import PropertyDetailsView from "@/views/Property/PropertyDetailsView.vue";
import PropertiesView from "@/views/Agency/PropertiesView.vue";
import ShowVisitView from "@/views/Agency/ShowVisitView.vue";
import { Roles } from "@/enums/roles";
import { UserValidationStatus } from "@/enums/UserValidationStatus";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: Base,
      meta: {
        forType: Roles.Tenant,
        location: "front",
      },
      children: [
        {
          path: "",
          name: "home",
          component: HomeView,
          meta: {
            public: true,
          },
        },
      ],
    },
    {
      path: "/tenant",
      name: Roles.Tenant,
      component: Base,
      meta: {
        forType: Roles.Tenant,
        location: "front",
      },
      children: [
        {
          path: "",
          name: `${Roles.Tenant}_home`,
          component: HomeView,
          meta: {
            public: true,
          },
        },
        {
          path: "register",
          name: `${Roles.Tenant}_signup`,
          component: RegisterView,
          meta: {
            public: true,
          },
        },
        {
          path: "login",
          name: `${Roles.Tenant}_login`,
          component: LoginView,
          meta: {
            public: true,
          },
        },
        {
          path: "dashboard",
          name: `${Roles.Tenant}_dashboard`,
          component: TenantDashboardView,
        },
        {
          path: "first-steps",
          name: `${Roles.Tenant}_first_steps`,
          component: WizardViewVue,
        },
        {
          path: "properties",
          name: `${Roles.Tenant}_properties`,
          component: TenantPropertiesView,
        },
        {
          path: "requests",
          name: `${Roles.Tenant}_requests`,
          component: TenantRequestsView,
        },
        {
          path: "requests/:id/slots",
          name: `${Roles.Tenant}_request_slots`,
          component: TenantRequestSlotsView,
        },
        {
          path: "property/:id/details",
          name: `${Roles.Tenant}_property_details`,
          component: PropertyDetailsView,
        },
      ],
    },
    {
      path: "/homeowner",
      component: Base,
      meta: {
        forType: Roles.Homeowner,
        location: "dashboard",
      },
      children: [
        {
          path: "",
          name: `${Roles.Homeowner}_home`,
          component: HomeView,
          meta: {
            public: true,
          },
        },
        {
          path: "register",
          name: `${Roles.Homeowner}_signup`,
          component: RegisterView,
          meta: {
            public: true,
          },
        },
        {
          path: "login",
          name: `${Roles.Homeowner}_login`,
          component: LoginView,
          meta: {
            public: true,
          },
        },
        {
          path: "dashboard",
          name: `${Roles.Homeowner}_dashboard`,
          component: HomeownerDashboardView,
        },
        {
          path: "property/add",
          name: `${Roles.Homeowner}_property_add`,
          component: PropertyRegister,
        },
        {
          path: "property/requests/:id",
          name: `${Roles.Homeowner}_property_requests`,
          component: PropertyRequestsView,
        },
        {
          path: "requests",
          name: `${Roles.Homeowner}_requests`,
          component: HomeownerRequestsView,
        },
        {
          path: "viewings/:ownerId",
          name: `${Roles.Homeowner}_viewings`,
          component: ViewingView,
        },
        {
          path: "request/:id/slots/proposals",
          name: `${Roles.Homeowner}_request_slots_proposals`,
          component: RequestSlotsProposalsView,
        },
        {
          path: "property/:id/add/photos",
          name: `${Roles.Homeowner}_property_add_photos`,
          component: PropertyPhotosUploadViews,
        },
        {
          path: "property/:id/details",
          name: `${Roles.Homeowner}_property_details`,
          component: PropertyDetailsView,
        },
      ],
    },
    {
      path: "/admin",
      component: Base,
      meta: {
        forType: Roles.Agency,
      },
      children: [
        {
          path: "login",
          name: `${Roles.Agency}_login`,
          component: LoginView,
          meta: {
            public: true,
          },
        },
        {
          path: "dashboard",
          name: `${Roles.Agency}_dashboard`,
          component: AgencyDashboardView,
        },
        {
          path: "users/:id",
          name: `${Roles.Agency}_show_user`,
          component: ShowUserView,
        },
        {
          path: "viewings",
          name: `${Roles.Agency}_viewings`,
          component: ViewingsView,
        },
        {
          path: "property/:id/details",
          name: `${Roles.Agency}_property_details`,
          component: PropertyDetailsView,
        },
        {
          path: "properties",
          name: `${Roles.Agency}_properties`,
          component: PropertiesView,
        },
        {
          path: "visit/:id",
          name: `${Roles.Agency}_show_visit`,
          component: ShowVisitView,
        },
      ],
    },
    {
      path: "/logout",
      name: "logout",
      component: {
        beforeRouteEnter(to, from, next) {
          const authStore = useAuthStore();
          authStore.logout();
          const alert = {
            type: "info",
            message: "Vous vous êtes bien déconnecté",
          };
          next({
            name: `${Roles.Tenant}_login`,
            params: { alert: JSON.stringify(alert) },
          });
        },
      },
    },
    {
      path: "/email-verification-new-link",
      name: "email_verification_new_link",
      component: RequestNewLinkView,
      meta: {
        public: true,
      },
    },
    {
      path: "/:catchAll(.*)",
      name: "not_found",
      component: Base,
      meta: {
        forType: "tenant",
      },
      children: [
        {
          path: "",
          name: "not_found",
          component: NotFoundView,
          meta: {
            public: true,
          },
        },
      ],
    },
  ],
});

router.beforeEach(async (to) => {
  const authStore = useAuthStore();
  if (authStore.access_token) {
    if (authStore.isTokenExpired()) {
      try {
        await authStore.refreshAccessToken();
        router.push(to.path);
      } catch (error) {
        authStore.logout();
        return { name: `${Roles.Tenant}_login` };
      }
    }
    const user = await authStore.getUser;
    if (to.name === "logout") return true;
    if (user.role === Roles.Tenant) {
      if (
        user.validationStatus === UserValidationStatus.ToComplete &&
        to.name !== `${Roles.Tenant}_first_steps`
      ) {
        return { name: `${Roles.Tenant}_first_steps` };
      }
      if (
        user.validationStatus !== UserValidationStatus.ToComplete &&
        to.name === `${Roles.Tenant}_first_steps`
      ) {
        return { name: `${Roles.Tenant}_dashboard` };
      }
    }
    if (to.meta.public || (to.meta.forType && to.meta.forType !== user.role)) {
      return { name: `${user.role}_dashboard` };
    }
    return true;
  }
  return to.meta.public ? true : { name: `${Roles.Tenant}_login` };
});

export default router;
