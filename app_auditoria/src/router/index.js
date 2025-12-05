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
      path: '/users',
      name: 'users',
      component: () => import('@/views/UsersView.vue'),
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
  const authStore = useAuthStore()

  const isAuthenticated = authStore.isAuthenticated
  const isTokenExpired = authStore.isTokenExpired()
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const requiredRoles = to.meta.roles

  if (requiresAuth && (!isAuthenticated || isTokenExpired)) {
    console.log('Ruta protegida. Redirigiendo a login.')
    authStore.logout()
    next({ name: 'login' })
  } else if (!requiresAuth && isAuthenticated && to.name === 'login') {
    console.log('Ya autenticado. Redirigiendo a home.')
    next({ name: 'home' })
  } else if (requiresAuth && isAuthenticated && requiredRoles) {
    const userRoles = authStore.user?.roles || []
    const hasRequiredRole = requiredRoles.some(role => userRoles.includes(role))
    if (!hasRequiredRole) {
      console.log('Acceso denegado. Sin roles necesarios.')
      next({ name: 'home' }) // o una vista 403
    } else {
      next()
    }
  } else {
    next()
  }
})
export default router
