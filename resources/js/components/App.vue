<template>
  <div id="app" class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Современная навигация -->
    <nav v-if="isAuthenticated" class="bg-white/80 backdrop-blur-md shadow-lg border-b border-gray-200/50 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Логотип и навигация -->
          <div class="flex items-center space-x-8">
            <router-link to="/" class="flex items-center space-x-2 group">
              <div class="w-8 h-8 bg-gradient-primary rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                <span class="text-white font-bold text-sm">₽</span>
              </div>
              <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Домашняя бухгалтерия
              </span>
            </router-link>
            
            <!-- Десктопная навигация -->
            <div class="hidden md:flex space-x-1">
              <router-link 
                to="/" 
                class="nav-link"
                :class="{ 'nav-link-active': $route.name === 'dashboard' }"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                Дашборд
              </router-link>
              <router-link 
                to="/categories" 
                class="nav-link"
                :class="{ 'nav-link-active': $route.name === 'categories' }"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Категории
              </router-link>
              <router-link 
                to="/expenses" 
                class="nav-link"
                :class="{ 'nav-link-active': $route.name === 'expenses' }"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Расходы
              </router-link>
            </div>
          </div>
          
          <!-- Пользователь и мобильное меню -->
          <div class="flex items-center space-x-4">
            <!-- Информация о пользователе -->
            <div class="hidden sm:flex items-center space-x-3">
              <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-medium">{{ user?.name?.charAt(0).toUpperCase() }}</span>
              </div>
              <span class="text-gray-700 font-medium">{{ user?.name }}</span>
            </div>
            
            <!-- Кнопка выхода -->
            <button 
              @click="logout" 
              class="btn btn-danger flex items-center space-x-2 hover:scale-105 transition-transform duration-200"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
              <span class="hidden sm:inline">Выйти</span>
            </button>
            
            <!-- Мобильное меню -->
            <button 
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="md:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
          </div>
        </div>
        
        <!-- Мобильная навигация -->
        <div v-if="mobileMenuOpen" class="md:hidden py-4 border-t border-gray-200 animate-fade-in">
          <div class="space-y-2">
            <router-link 
              to="/" 
              @click="mobileMenuOpen = false"
              class="mobile-nav-link"
              :class="{ 'mobile-nav-link-active': $route.name === 'dashboard' }"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
              </svg>
              Дашборд
            </router-link>
            <router-link 
              to="/categories" 
              @click="mobileMenuOpen = false"
              class="mobile-nav-link"
              :class="{ 'mobile-nav-link-active': $route.name === 'categories' }"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
              </svg>
              Категории
            </router-link>
            <router-link 
              to="/expenses" 
              @click="mobileMenuOpen = false"
              class="mobile-nav-link"
              :class="{ 'mobile-nav-link-active': $route.name === 'expenses' }"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              Расходы
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Основной контент -->
    <main class="flex-1 transition-all duration-300">
      <router-view class="animate-fade-in" />
    </main>
  </div>
</template>

<script>
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

export default {
  name: 'App',
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();
    const mobileMenuOpen = ref(false);

    const isAuthenticated = computed(() => authStore.isAuthenticated);
    const user = computed(() => authStore.user);

    const logout = async () => {
      await authStore.logout();
      router.push('/login');
    };

    onMounted(() => {
      authStore.checkAuth();
    });

    // Navigation guards
    router.beforeEach((to, from, next) => {
      if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next('/login');
      } else if (to.meta.guest && authStore.isAuthenticated) {
        next('/');
      } else {
        next();
      }
      
      // Закрываем мобильное меню при переходе
      mobileMenuOpen.value = false;
    });

    return {
      isAuthenticated,
      user,
      mobileMenuOpen,
      logout
    };
  }
};
</script>

<style scoped>
/* Стили навигации */
.nav-link {
  display: flex;
  align-items: center;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #4b5563;
  border-radius: 0.5rem;
  transition: all 0.2s;
}

.nav-link:hover {
  color: #111827;
  background-color: #f3f4f6;
}

.nav-link-active {
  color: #2563eb;
  background-color: #eff6ff;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.mobile-nav-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  font-size: 1rem;
  font-weight: 500;
  color: #4b5563;
  border-radius: 0.5rem;
  transition: all 0.2s;
}

.mobile-nav-link:hover {
  color: #111827;
  background-color: #f3f4f6;
}

.mobile-nav-link-active {
  color: #2563eb;
  background-color: #eff6ff;
}
</style>