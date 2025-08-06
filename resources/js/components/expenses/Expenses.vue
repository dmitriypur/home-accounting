<template>
  <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Заголовок -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div class="mb-4 sm:mb-0">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2">
          Управление расходами 💰
        </h1>
        <p class="text-gray-600 text-lg">Отслеживайте и анализируйте свои траты</p>
      </div>
      <button @click="openModal()" class="btn btn-primary flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Добавить расход</span>
      </button>
    </div>

    <!-- Фильтры -->
    <div class="card mb-8 animate-slide-in">
      <div class="card-header">
        <div class="flex items-center space-x-2">
          <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
          </div>
          <h2 class="text-xl font-semibold text-gray-900">Фильтры</h2>
        </div>
      </div>
      <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label class="form-label">Категория</label>
            <select v-model="filters.category_id" @change="loadExpenses" class="form-select">
              <option value="">🏷️ Все категории</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.icon }} {{ category.name }}
              </option>
            </select>
          </div>
          <div>
            <label class="form-label">Дата от</label>
            <input v-model="filters.date_from" @change="loadExpenses" type="date" class="form-input">
          </div>
          <div>
            <label class="form-label">Дата до</label>
            <input v-model="filters.date_to" @change="loadExpenses" type="date" class="form-input">
          </div>
        </div>
        <div class="mt-6 flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3">
          <button @click="loadExpenses" class="btn btn-primary flex items-center justify-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <span>Применить</span>
          </button>
          <button @click="clearFilters" class="btn btn-secondary flex items-center justify-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <span>Очистить</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Список расходов -->
    <div class="card animate-slide-in" style="animation-delay: 0.2s">
      <div class="card-header">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-500 rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">Список расходов</h2>
          </div>
          <div class="text-sm text-gray-500">
            Всего: {{ expenses.length }} записей
          </div>
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
        <div v-else-if="expenses.length === 0" class="text-center py-12">
          <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
          </div>
          <p class="text-gray-500 text-lg font-medium mb-2">Нет расходов</p>
          <p class="text-gray-400 text-sm mb-4">Добавьте первый расход, чтобы начать отслеживание</p>
          <button @click="openModal()" class="btn btn-primary">
            Добавить расход
          </button>
        </div>
        <div v-else class="divide-y divide-gray-100">
          <div 
            v-for="(expense, index) in expenses" 
            :key="expense.id" 
            class="p-6 hover:bg-gray-50 transition-all duration-200 group"
            :style="{ animationDelay: `${0.3 + index * 0.05}s` }"
          >
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
              <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                  <span class="text-xl">{{ expense.category.icon || '📁' }}</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-base font-semibold text-gray-900 mb-1 truncate">
                    {{ expense.description || expense.category.name }}
                  </p>
                  <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 space-y-1 sm:space-y-0 text-sm text-gray-500">
                    <span class="flex items-center">
                      <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0l-1 12a2 2 0 002 2h6a2 2 0 002-2L16 7"></path>
                      </svg>
                      {{ formatDate(expense.date) }}
                    </span>
                    <span class="flex items-center">
                      <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                      </svg>
                      {{ expense.category.name }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="flex items-center justify-between sm:justify-end sm:space-x-4">
                <div class="text-left sm:text-right">
                  <p class="text-xl font-bold text-gray-900 mb-1">{{ formatCurrency(expense.amount) }}</p>
                  <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Расход
                  </div>
                </div>
                <div class="flex items-center space-x-2 flex-shrink-0">
                  <button @click="openModal(expense)" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button @click="deleteExpense(expense)" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Пагинация -->
      <div v-if="pagination.last_page > 1" class="card-body border-t border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
          <div class="text-sm text-gray-600">
            Показано <span class="font-medium">{{ pagination.from }}</span> - <span class="font-medium">{{ pagination.to }}</span> из <span class="font-medium">{{ pagination.total }}</span> записей
          </div>
          <div class="flex items-center space-x-2">
            <button 
              @click="changePage(pagination.current_page - 1)"
              :disabled="pagination.current_page === 1"
              class="p-2 text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <div class="flex space-x-1">
              <button 
                v-for="page in visiblePages" 
                :key="page"
                @click="changePage(page)"
                :class="[
                  'px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200',
                  page === pagination.current_page 
                    ? 'bg-blue-500 text-white shadow-md' 
                    : 'text-gray-700 hover:bg-gray-100'
                ]"
              >
                {{ page }}
              </button>
            </div>
            <button 
              @click="changePage(pagination.current_page + 1)"
              :disabled="pagination.current_page === pagination.last_page"
              class="p-2 text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Модальное окно -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ editingExpense ? 'Редактировать расход' : 'Новый расход' }}
          </h3>
          <form @submit.prevent="saveExpense">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Категория</label>
              <select
                v-model="form.category_id"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">Выберите категорию</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.icon }} {{ category.name }}
                </option>
              </select>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Сумма</label>
              <input
                v-model="form.amount"
                type="number"
                step="0.01"
                min="0.01"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                placeholder="0.00"
              />
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
              <input
                v-model="form.description"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                placeholder="Описание расхода"
              />
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Дата</label>
              <input
                v-model="form.date"
                type="date"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <div v-if="error" class="mb-4 text-red-600 text-sm">
              {{ error }}
            </div>
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
              >
                Отмена
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50"
              >
                {{ saving ? 'Сохранение...' : 'Сохранить' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'Expenses',
  setup() {
    const expenses = ref([]);
    const categories = ref([]);
    const loading = ref(true);
    const showModal = ref(false);
    const editingExpense = ref(null);
    const saving = ref(false);
    const error = ref('');
    const pagination = ref({});
    
    const filters = ref({
      category_id: '',
      date_from: '',
      date_to: ''
    });
    
    const form = ref({
      category_id: '',
      amount: '',
      description: '',
      date: new Date().toISOString().split('T')[0]
    });

    const visiblePages = computed(() => {
      const current = pagination.value.current_page || 1;
      const last = pagination.value.last_page || 1;
      const pages = [];
      
      for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
        pages.push(i);
      }
      
      return pages;
    });

    const formatCurrency = (amount) => {
      return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB'
      }).format(amount || 0);
    };

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('ru-RU');
    };

    const loadCategories = async () => {
      try {
        const response = await axios.get('/api/categories');
        categories.value = response.data;
      } catch (error) {
        console.error('Error loading categories:', error);
      }
    };

    const loadExpenses = async (page = 1) => {
      try {
        const params = { page, ...filters.value };
        Object.keys(params).forEach(key => {
          if (params[key] === '') delete params[key];
        });
        
        const response = await axios.get('/api/expenses', { params });
        expenses.value = response.data.data;
        pagination.value = {
          current_page: response.data.current_page,
          last_page: response.data.last_page,
          total: response.data.total
        };
      } catch (error) {
        console.error('Error loading expenses:', error);
      } finally {
        loading.value = false;
      }
    };

    const changePage = (page) => {
      loadExpenses(page);
    };

    const clearFilters = () => {
      filters.value = {
        category_id: '',
        date_from: '',
        date_to: ''
      };
      loadExpenses();
    };

    const openModal = (expense = null) => {
      editingExpense.value = expense;
      if (expense) {
        form.value = {
          category_id: expense.category_id,
          amount: expense.amount,
          description: expense.description || '',
          date: expense.date
        };
      } else {
        form.value = {
          category_id: '',
          amount: '',
          description: '',
          date: new Date().toISOString().split('T')[0]
        };
      }
      error.value = '';
      showModal.value = true;
    };

    const closeModal = () => {
      showModal.value = false;
      editingExpense.value = null;
      error.value = '';
    };

    const saveExpense = async () => {
      saving.value = true;
      error.value = '';
      
      try {
        if (editingExpense.value) {
          await axios.put(`/api/expenses/${editingExpense.value.id}`, form.value);
        } else {
          await axios.post('/api/expenses', form.value);
        }
        
        await loadExpenses();
        closeModal();
      } catch (err) {
        error.value = err.response?.data?.message || 'Ошибка сохранения';
      } finally {
        saving.value = false;
      }
    };

    const deleteExpense = async (expense) => {
      if (!confirm(`Удалить расход "${expense.description || expense.category.name}"?`)) {
        return;
      }
      
      try {
        await axios.delete(`/api/expenses/${expense.id}`);
        await loadExpenses();
      } catch (error) {
        alert('Ошибка удаления расхода');
      }
    };

    onMounted(async () => {
      await loadCategories();
      await loadExpenses();
    });

    return {
      expenses,
      categories,
      loading,
      showModal,
      editingExpense,
      saving,
      error,
      pagination,
      filters,
      form,
      visiblePages,
      formatCurrency,
      formatDate,
      loadExpenses,
      changePage,
      clearFilters,
      openModal,
      closeModal,
      saveExpense,
      deleteExpense
    };
  }
};
</script>