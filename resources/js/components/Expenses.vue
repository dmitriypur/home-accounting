<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Расходы</h1>
            <p class="mt-2 text-gray-600">Управление вашими расходами</p>
          </div>
          <button 
            @click="showAddModal = true" 
            class="btn btn-primary flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Добавить расход</span>
          </button>
        </div>
      </div>

      <!-- Filters -->
      <div class="card mb-6">
        <div class="card-body">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Категория</label>
              <select v-model="filters.category_id" class="form-select">
                <option value="">Все категории</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.icon }} {{ category.name }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Дата от</label>
              <input v-model="filters.date_from" type="date" class="form-input">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Дата до</label>
              <input v-model="filters.date_to" type="date" class="form-input">
            </div>
            <div class="flex items-end">
              <button @click="applyFilters" class="btn btn-secondary w-full">
                Применить
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Expenses List -->
      <div class="card">
        <div class="card-header">
          <h2 class="text-xl font-semibold text-gray-900">Список расходов</h2>
        </div>
        <div class="card-body p-0">
          <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
          </div>
          <div v-else-if="expenses.length === 0" class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
            </div>
            <p class="text-gray-500 text-lg font-medium mb-2">Нет расходов</p>
            <p class="text-gray-400 text-sm mb-4">Добавьте первый расход, чтобы начать отслеживание</p>
            <button @click="showAddModal = true" class="btn btn-primary">
              Добавить расход
            </button>
          </div>
          <div v-else class="divide-y divide-gray-100">
            <div 
              v-for="expense in expenses" 
              :key="expense.id" 
              class="p-6 hover:bg-gray-50 transition-colors duration-200 group"
            >
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="flex items-center space-x-4 min-w-0 flex-1">
                  <div class="w-12 h-12 bg-gradient-to-br from-red-50 to-orange-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200 flex-shrink-0">
                    <span class="text-xl">{{ expense.category.icon || '💸' }}</span>
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-base font-semibold text-gray-900 mb-1 truncate">
                      {{ expense.description || expense.category.name }}
                    </p>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 space-y-1 sm:space-y-0 text-sm text-gray-500">
                      <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span class="truncate">{{ expense.category.name }}</span>
                      </span>
                      <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0l-1 12a2 2 0 002 2h6a2 2 0 002-2L16 7"></path>
                        </svg>
                        {{ formatDate(expense.date) }}
                      </span>
                    </div>
                  </div>
                </div>
                <div class="flex items-center space-x-3">
                  <div class="text-left sm:text-right flex-shrink-0">
                    <p class="text-lg font-bold text-red-600 mb-1">-{{ formatCurrency(expense.amount) }}</p>
                    <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                      Расход
                    </div>
                  </div>
                  <div class="flex items-center space-x-2">
                    <button 
                      @click="editExpense(expense)" 
                      class="p-2 text-gray-400 hover:text-blue-600 transition-colors duration-200"
                      title="Редактировать"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </button>
                    <button 
                      @click="deleteExpense(expense)" 
                      class="p-2 text-gray-400 hover:text-red-600 transition-colors duration-200"
                      title="Удалить"
                    >
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
      </div>

      <!-- Pagination -->
      <div v-if="pagination.total > pagination.per_page" class="mt-6 flex justify-center">
        <nav class="flex items-center space-x-2">
          <button 
            @click="changePage(pagination.current_page - 1)"
            :disabled="pagination.current_page === 1"
            class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Назад
          </button>
          <span class="px-3 py-2 text-sm text-gray-700">
            Страница {{ pagination.current_page }} из {{ pagination.last_page }}
          </span>
          <button 
            @click="changePage(pagination.current_page + 1)"
            :disabled="pagination.current_page === pagination.last_page"
            class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Вперед
          </button>
        </nav>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click="closeModal">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" @click.stop>
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">
              {{ editingExpense ? 'Редактировать расход' : 'Добавить расход' }}
            </h3>
            <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <form @submit.prevent="saveExpense">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Категория *</label>
                <select v-model="form.category_id" required class="form-select">
                  <option value="">Выберите категорию</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.icon }} {{ category.name }}
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Сумма *</label>
                <input 
                  v-model="form.amount" 
                  type="number" 
                  step="0.01" 
                  min="0" 
                  required 
                  class="form-input"
                  placeholder="0.00"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                <input 
                  v-model="form.description" 
                  type="text" 
                  class="form-input"
                  placeholder="Описание расхода"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Дата *</label>
                <input 
                  v-model="form.date" 
                  type="date" 
                  required 
                  class="form-input"
                >
              </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button type="button" @click="closeModal" class="btn btn-secondary">
                Отмена
              </button>
              <button type="submit" :disabled="saving" class="btn btn-primary">
                {{ saving ? 'Сохранение...' : (editingExpense ? 'Обновить' : 'Добавить') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

export default {
  name: 'Expenses',
  setup() {
    const expenses = ref([])
    const categories = ref([])
    const loading = ref(false)
    const saving = ref(false)
    const showAddModal = ref(false)
    const editingExpense = ref(null)
    
    const filters = ref({
      category_id: '',
      date_from: '',
      date_to: ''
    })
    
    const pagination = ref({
      current_page: 1,
      last_page: 1,
      per_page: 15,
      total: 0
    })
    
    const form = ref({
      category_id: '',
      amount: '',
      description: '',
      date: new Date().toISOString().split('T')[0]
    })
    
    const loadExpenses = async (page = 1) => {
      loading.value = true
      try {
        const params = {
          page,
          ...filters.value
        }
        
        // Remove empty filters
        Object.keys(params).forEach(key => {
          if (params[key] === '' || params[key] === null) {
            delete params[key]
          }
        })
        
        const response = await axios.get('/api/expenses', { params })
        expenses.value = response.data.data
        pagination.value = {
          current_page: response.data.current_page,
          last_page: response.data.last_page,
          per_page: response.data.per_page,
          total: response.data.total
        }
      } catch (error) {
        console.error('Error loading expenses:', error)
        alert('Ошибка при загрузке расходов')
      } finally {
        loading.value = false
      }
    }
    
    const loadCategories = async () => {
      try {
        const response = await axios.get('/api/categories?type=expense')
        categories.value = response.data
      } catch (error) {
        console.error('Error loading categories:', error)
        alert('Ошибка при загрузке категорий')
      }
    }
    
    const applyFilters = () => {
      pagination.value.current_page = 1
      loadExpenses(1)
    }
    
    const changePage = (page) => {
      if (page >= 1 && page <= pagination.value.last_page) {
        loadExpenses(page)
      }
    }
    
    const resetForm = () => {
      form.value = {
        category_id: '',
        amount: '',
        description: '',
        date: new Date().toISOString().split('T')[0]
      }
      editingExpense.value = null
    }
    
    const closeModal = () => {
      showAddModal.value = false
      resetForm()
    }
    
    const editExpense = (expense) => {
      editingExpense.value = expense
      form.value = {
        category_id: expense.category_id,
        amount: expense.amount,
        description: expense.description || '',
        date: expense.date
      }
      showAddModal.value = true
    }
    
    const saveExpense = async () => {
      saving.value = true
      try {
        if (editingExpense.value) {
          await axios.put(`/api/expenses/${editingExpense.value.id}`, form.value)
        } else {
          await axios.post('/api/expenses', form.value)
        }
        
        closeModal()
        loadExpenses(pagination.value.current_page)
      } catch (error) {
        console.error('Error saving expense:', error)
        alert('Ошибка при сохранении расхода')
      } finally {
        saving.value = false
      }
    }
    
    const deleteExpense = async (expense) => {
      if (confirm('Вы уверены, что хотите удалить этот расход?')) {
        try {
          await axios.delete(`/api/expenses/${expense.id}`)
          loadExpenses(pagination.value.current_page)
        } catch (error) {
          console.error('Error deleting expense:', error)
          alert('Ошибка при удалении расхода')
        }
      }
    }
    
    const formatCurrency = (amount) => {
      return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB'
      }).format(amount)
    }
    
    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('ru-RU')
    }
    
    onMounted(() => {
      loadExpenses()
      loadCategories()
    })
    
    return {
      expenses,
      categories,
      loading,
      saving,
      showAddModal,
      editingExpense,
      filters,
      pagination,
      form,
      loadExpenses,
      applyFilters,
      changePage,
      closeModal,
      editExpense,
      saveExpense,
      deleteExpense,
      formatCurrency,
      formatDate
    }
  }
}
</script>