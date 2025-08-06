<template>
  <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Заголовок с приветствием -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2">
            Добро пожаловать! 👋
          </h1>
          <p class="text-gray-600 text-lg">Вот обзор ваших финансов</p>
        </div>
        <div class="hidden sm:block">
          <div class="text-right">
            <p class="text-sm text-gray-500">Сегодня</p>
            <p class="text-lg font-semibold text-gray-900">{{ new Date().toLocaleDateString('ru-RU', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
          </div>
        </div>
      </div>
    </div>
      
    <!-- Статистические карточки -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Карточка "Расходы за месяц" -->
      <div class="stat-card group cursor-pointer" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
          <div>
            <div class="flex items-center space-x-2 mb-2">
              <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
              <span class="text-sm font-medium text-gray-500">Расходы</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 mb-1">{{ formatCurrency(summary.expenses?.this_month) }}</p>
            <div class="flex items-center text-xs">
              <span class="text-red-600 font-medium">Расходы</span>
              <span class="text-gray-500 ml-1">за месяц</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Карточка "Доходы за месяц" -->
      <div class="stat-card group cursor-pointer" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between">
          <div>
            <div class="flex items-center space-x-2 mb-2">
              <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
              <span class="text-sm font-medium text-gray-500">Доходы</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 mb-1">{{ formatCurrency(summary.incomes?.this_month) }}</p>
            <div class="flex items-center text-xs">
              <span class="text-green-600 font-medium">Доходы</span>
              <span class="text-gray-500 ml-1">за месяц</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Карточка "Баланс" -->
      <div class="stat-card group cursor-pointer" style="animation-delay: 0.3s">
        <div class="flex items-center justify-between">
          <div>
            <div class="flex items-center space-x-2 mb-2">
              <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
              </div>
              <span class="text-sm font-medium text-gray-500">Баланс</span>
            </div>
            <p class="text-2xl font-bold mb-1" :class="(summary.balance?.this_month) >= 0 ? 'text-green-600' : 'text-red-600'">{{ formatCurrency(summary.balance?.this_month) }}</p>
            <div class="flex items-center text-xs">
              <span :class="(summary.balance?.this_month) >= 0 ? 'text-green-600' : 'text-red-600'" class="font-medium">{{ (summary.balance?.this_month) >= 0 ? 'Профицит' : 'Дефицит' }}</span>
              <span class="text-gray-500 ml-1">за месяц</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Карточка "Транзакции" -->
      <div class="stat-card group cursor-pointer" style="animation-delay: 0.4s">
        <div class="flex items-center justify-between">
          <div>
            <div class="flex items-center space-x-2 mb-2">
              <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
              </div>
              <span class="text-sm font-medium text-gray-500">Транзакций</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 mb-1">{{ (summary.expenses?.transactions_this_month || 0) + (summary.incomes?.transactions_this_month || 0) }}</p>
            <div class="flex items-center text-xs">
              <span class="text-blue-600 font-medium">{{ summary.expenses?.transactions_this_month || 0 }}/{{ summary.incomes?.transactions_this_month || 0 }}</span>
              <span class="text-gray-500 ml-1">расх/дох</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Последние транзакции -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
      <!-- Последние расходы -->
      <div class="card animate-slide-in" style="animation-delay: 0.5s">
        <div class="card-header">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
              <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Последние расходы</h2>
            </div>
            <router-link to="/expenses" class="btn btn-primary text-sm w-full sm:w-auto text-center">
              Все расходы
            </router-link>
          </div>
        </div>
        <div class="card-body p-0">
          <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-pulse flex space-x-4">
              <div class="rounded-full bg-gray-300 h-12 w-12"></div>
              <div class="flex-1 space-y-2 py-1">
                <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                <div class="h-4 bg-gray-300 rounded w-1/2"></div>
              </div>
            </div>
          </div>
          <div v-else-if="recentExpenses.length === 0" class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
            </div>
            <p class="text-gray-500 text-lg font-medium mb-2">Нет расходов</p>
            <p class="text-gray-400 text-sm mb-4">Добавьте первый расход, чтобы начать отслеживание</p>
            <router-link to="/expenses" class="btn btn-primary">
              Добавить расход
            </router-link>
          </div>
          <div v-else class="divide-y divide-gray-100">
            <div 
              v-for="(expense, index) in recentExpenses" 
              :key="expense.id" 
              class="p-4 hover:bg-gray-50 transition-colors duration-200 group"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 min-w-0 flex-1">
                  <div class="w-10 h-10 bg-gradient-to-br from-red-50 to-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="text-lg">{{ expense.category.icon || '📁' }}</span>
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-900 truncate">
                      {{ expense.description || expense.category.name }}
                    </p>
                    <p class="text-xs text-gray-500">{{ formatDate(expense.date) }}</p>
                  </div>
                </div>
                <div class="text-right flex-shrink-0">
                  <p class="text-sm font-bold text-red-600">-{{ formatCurrency(expense.amount) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Последние доходы -->
      <div class="card animate-slide-in" style="animation-delay: 0.6s">
        <div class="card-header">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Последние доходы</h2>
            </div>
            <router-link to="/incomes" class="btn btn-success text-sm w-full sm:w-auto text-center">
              Все доходы
            </router-link>
          </div>
        </div>
        <div class="card-body p-0">
          <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-pulse flex space-x-4">
              <div class="rounded-full bg-gray-300 h-12 w-12"></div>
              <div class="flex-1 space-y-2 py-1">
                <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                <div class="h-4 bg-gray-300 rounded w-1/2"></div>
              </div>
            </div>
          </div>
          <div v-else-if="recentIncomes.length === 0" class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
            </div>
            <p class="text-gray-500 text-lg font-medium mb-2">Нет доходов</p>
            <p class="text-gray-400 text-sm mb-4">Добавьте первый доход, чтобы начать отслеживание</p>
            <router-link to="/incomes" class="btn btn-success">
              Добавить доход
            </router-link>
          </div>
          <div v-else class="divide-y divide-gray-100">
            <div 
              v-for="(income, index) in recentIncomes" 
              :key="income.id" 
              class="p-4 hover:bg-gray-50 transition-colors duration-200 group"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 min-w-0 flex-1">
                  <div class="w-10 h-10 bg-gradient-to-br from-green-50 to-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="text-lg">{{ income.category.icon || '💰' }}</span>
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-900 truncate">
                      {{ income.description || income.category.name }}
                    </p>
                    <p class="text-xs text-gray-500">{{ formatDate(income.date) }}</p>
                  </div>
                </div>
                <div class="text-right flex-shrink-0">
                  <p class="text-sm font-bold text-green-600">+{{ formatCurrency(income.amount) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
              <span class="text-gray-500 ml-1">стабильно</span>
            </div>
      


  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'Dashboard',
  setup() {
    const summary = ref({});
    const recentExpenses = ref([]);
    const recentIncomes = ref([]);
    const loading = ref(true);

    const formatCurrency = (amount) => {
      return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB'
      }).format(amount || 0);
    };

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('ru-RU');
    };

    const loadData = async () => {
      try {
        const [summaryResponse, expensesResponse, incomesResponse] = await Promise.all([
          axios.get('/api/summary'),
          axios.get('/api/expenses?limit=5'),
          axios.get('/api/incomes?limit=5')
        ]);
        
        summary.value = summaryResponse.data;
        recentExpenses.value = expensesResponse.data.data || expensesResponse.data;
        recentIncomes.value = incomesResponse.data.data || incomesResponse.data;
      } catch (error) {
        console.error('Error loading dashboard data:', error);
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      loadData();
    });

    return {
      summary,
      recentExpenses,
      recentIncomes,
      loading,
      formatCurrency,
      formatDate
    };
  }
};
</script>