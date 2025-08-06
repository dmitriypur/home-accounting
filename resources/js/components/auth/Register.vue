<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <!-- Логотип и заголовок -->
      <div class="text-center mb-8">
        <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
          </svg>
        </div>
        <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2">
          Создать аккаунт
        </h2>
        <p class="text-gray-600">Присоединяйтесь к нам для управления финансами</p>
      </div>

      <!-- Форма регистрации -->
      <div class="bg-white rounded-2xl shadow-xl p-8 animate-fade-in">
        <form @submit.prevent="handleRegister" class="space-y-6">
          <!-- Имя -->
          <div>
            <label class="form-label">Полное имя</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
              </div>
              <input
                v-model="form.name"
                type="text"
                required
                class="form-input pl-10"
                placeholder="Введите ваше полное имя"
              />
            </div>
          </div>

          <!-- Email -->
          <div>
            <label class="form-label">Email адрес</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                </svg>
              </div>
              <input
                v-model="form.email"
                type="email"
                required
                class="form-input pl-10"
                placeholder="example@email.com"
              />
            </div>
          </div>

          <!-- Пароль -->
          <div>
            <label class="form-label">Пароль</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
              </div>
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="form-input pl-10 pr-10"
                placeholder="Создайте надежный пароль"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
              >
                <svg v-if="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
              </button>
            </div>
          </div>

          <!-- Подтверждение пароля -->
          <div>
            <label class="form-label">Подтверждение пароля</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <input
                v-model="form.password_confirmation"
                :type="showPasswordConfirmation ? 'text' : 'password'"
                required
                class="form-input pl-10 pr-10"
                placeholder="Повторите пароль"
              />
              <button
                type="button"
                @click="showPasswordConfirmation = !showPasswordConfirmation"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
              >
                <svg v-if="!showPasswordConfirmation" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
              </button>
            </div>
          </div>

          <!-- Индикатор силы пароля -->
          <div v-if="form.password" class="space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Сила пароля:</span>
              <span :class="passwordStrengthColor">{{ passwordStrengthText }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div 
                :class="passwordStrengthColor.replace('text-', 'bg-')"
                class="h-2 rounded-full transition-all duration-300"
                :style="{ width: passwordStrengthWidth }"
              ></div>
            </div>
          </div>

          <!-- Ошибки -->
          <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 animate-shake">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <p class="text-red-700 text-sm">{{ error }}</p>
            </div>
          </div>

          <!-- Кнопка регистрации -->
          <button
            type="submit"
            :disabled="loading || !isFormValid"
            class="btn btn-primary w-full py-3 text-lg font-semibold relative overflow-hidden group"
          >
            <span v-if="loading" class="flex items-center justify-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Создание аккаунта...
            </span>
            <span v-else class="flex items-center justify-center">
              <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
              </svg>
              Создать аккаунт
            </span>
          </button>
        </form>

        <!-- Ссылка на вход -->
        <div class="mt-6 text-center">
          <p class="text-gray-600">
            Уже есть аккаунт?
            <router-link 
              to="/login" 
              class="font-semibold text-blue-600 hover:text-blue-500 transition-colors duration-200 ml-1"
            >
              Войти в систему
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

export default {
  name: 'Register',
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();
    
    const form = ref({
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    });
    
    const loading = ref(false);
    const error = ref('');
    const showPassword = ref(false);
    const showPasswordConfirmation = ref(false);
    
    // Проверка силы пароля
    const passwordStrength = computed(() => {
      const password = form.value.password;
      if (!password) return 0;
      
      let strength = 0;
      if (password.length >= 8) strength++;
      if (/[a-z]/.test(password)) strength++;
      if (/[A-Z]/.test(password)) strength++;
      if (/[0-9]/.test(password)) strength++;
      if (/[^A-Za-z0-9]/.test(password)) strength++;
      
      return strength;
    });
    
    const passwordStrengthText = computed(() => {
      const strength = passwordStrength.value;
      if (strength <= 1) return 'Слабый';
      if (strength <= 2) return 'Средний';
      if (strength <= 3) return 'Хороший';
      if (strength <= 4) return 'Сильный';
      return 'Очень сильный';
    });
    
    const passwordStrengthColor = computed(() => {
      const strength = passwordStrength.value;
      if (strength <= 1) return 'text-red-500';
      if (strength <= 2) return 'text-orange-500';
      if (strength <= 3) return 'text-yellow-500';
      if (strength <= 4) return 'text-green-500';
      return 'text-green-600';
    });
    
    const passwordStrengthWidth = computed(() => {
      return `${(passwordStrength.value / 5) * 100}%`;
    });
    
    const isFormValid = computed(() => {
      return form.value.name && 
             form.value.email && 
             form.value.password && 
             form.value.password_confirmation &&
             form.value.password === form.value.password_confirmation &&
             passwordStrength.value >= 2;
    });
    
    const handleRegister = async () => {
      if (form.value.password !== form.value.password_confirmation) {
        error.value = 'Пароли не совпадают';
        return;
      }
      
      if (passwordStrength.value < 2) {
        error.value = 'Пароль слишком слабый. Используйте минимум 8 символов с буквами и цифрами';
        return;
      }
      
      loading.value = true;
      error.value = '';
      
      const result = await authStore.register(form.value);
      
      if (result.success) {
        router.push('/');
      } else {
        error.value = result.message;
      }
      
      loading.value = false;
    };
    
    return {
      form,
      loading,
      error,
      showPassword,
      showPasswordConfirmation,
      passwordStrengthText,
      passwordStrengthColor,
      passwordStrengthWidth,
      isFormValid,
      handleRegister
    };
  }
};
</script>