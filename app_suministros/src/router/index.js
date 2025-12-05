import { createRouter, createWebHistory } from 'vue-router'
import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
      meta: { requiresAuth: false } //roles: ['admin', 'editor']
    },
    {
      path: '/home',
      name: 'home',
      component: () => import('@/views/HomeView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/',
      name: 'request',
      component: () => import('@/views/RequestView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/supplies',
      name: 'supplies',
      component: () => import('@/views/SuppliesView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/supply/:id',
      name: 'supply',
      component: () => import('@/views/SupplyView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/new-supply',
      name: 'new_supply',
      component: () => import('@/views/NewSupplyView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/update-supply/:id',
      name: 'update_supply',
      component: () => import('@/views/UpdateSupplyView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/authorization',
      name: 'authorization',
      component: () => import('@/views/AuthorizationView.vue'),
      meta: { requiresAuth: true, roles: ['Supervisor', 'Administrador'] }
    },
    {
      path: '/manager',
      name: 'manager',
      component: () => import('@/views/ManagerView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/processing/:id',
      name: 'processing',
      component: () => import('@/views/ProcessingView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/adjust/',
      name: 'adjust',
      component: () => import('@/views/AdjustView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('@/views/ProfileView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/change',
      name: 'change',
      component: () => import('@/views/ChangePasswordView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/faq',
      name: 'faq',
      component: () => import('@/views/FaqView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      component: () => import('@/views/NotFoundView.vue'),
      redirect: { name: 'home' }
    }
  ]
})
router.beforeEach(async (to, from, next) => {
  const fakeAuth = import.meta.env.VITE_APP_DISABLE_AUTH === 'true'
  const authStore = useAuthStore()
  const isAuthenticated = authStore.isAuthenticated
  const isTokenExpired = authStore.isTokenExpired()
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const requiredRoles = to.meta.roles
  if (requiresAuth && (!isAuthenticated || isTokenExpired)) {
    authStore.logout()
    if (fakeAuth) {
      return next({ name: 'login' })
    }
    return (window.location.href = '/dai/login')
  }
  if (!requiresAuth && isAuthenticated && to.name === 'login') {
    if (fakeAuth) {
      return next({ name: 'home' })
    }
    return (window.location.href = '/dai/home')
  }
  const userRoles = authStore.rol || []
  if (requiresAuth && isAuthenticated && requiredRoles) {
    const hasRequiredRole = requiredRoles.some(role => userRoles.includes(role))

    if (!hasRequiredRole) {
      console.log('Acceso denegado. Sin roles necesarios.')
      if (fakeAuth) {
        return next({ name: 'home' })
      }
      return (window.location.href = '/dai/home')
    }
  }
  next()
})
export default router
