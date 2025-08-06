<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Категории</h1>
            <p class="mt-2 text-gray-600">Управление категориями доходов и расходов</p>
          </div>
          <button 
            @click="showAddModal = true" 
            class="btn btn-primary flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Добавить категорию</span>
          </button>
        </div>
      </div>

      <!-- Filter Tabs -->
      <div class="mb-6">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8">
            <button
              @click="activeTab = 'all'"
              :class="[
                activeTab === 'all'
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm'
              ]"
            >
              Все категории
            </button>
            <button
              @click="activeTab = 'income'"
              :class="[
                activeTab === 'income'
                  ? 'border-green-500 text-green-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm'
              ]"
            >
              Доходы
            </button>
            <button
              @click="activeTab = 'expense'"
              :class="[
                activeTab === 'expense'
                  ? 'border-red-500 text-red-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm'
              ]"
            >
              Расходы
            </button>
          </nav>
        </div>
      </div>

      <!-- Categories Grid -->
      <div v-if="loading" class="flex items-center justify-center py-12">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      </div>
      <div v-else-if="filteredCategories.length === 0" class="text-center py-12">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
        </div>
        <p class="text-gray-500 text-lg font-medium mb-2">Нет категорий</p>
        <p class="text-gray-400 text-sm mb-4">Добавьте первую категорию для начала работы</p>
        <button @click="showAddModal = true" class="btn btn-primary">
          Добавить категорию
        </button>
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div 
          v-for="category in filteredCategories" 
          :key="category.id" 
          class="card hover:shadow-lg transition-shadow duration-200 group"
        >
          <div class="card-body">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center space-x-3">
                <div 
                  :class="[
                    'w-12 h-12 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-200',
                    category.type === 'income' ? 'bg-gradient-to-br from-green-50 to-blue-50' : 'bg-gradient-to-br from-red-50 to-orange-50'
                  ]"
                >
                  {{ category.icon || (category.type === 'income' ? '💰' : '💸') }}
                </div>
                <div class="min-w-0 flex-1">
                  <h3 class="text-lg font-semibold text-gray-900 truncate">{{ category.name }}</h3>
                  <div class="flex items-center space-x-2 mt-1">
                    <span 
                      :class="[
                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                        category.type === 'income' 
                          ? 'bg-green-100 text-green-800' 
                          : 'bg-red-100 text-red-800'
                      ]"
                    >
                      {{ category.type === 'income' ? 'Доход' : 'Расход' }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <button 
                  @click="editCategory(category)" 
                  class="p-2 text-gray-400 hover:text-blue-600 transition-colors duration-200"
                  title="Редактировать"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
                <button 
                  @click="deleteCategory(category)" 
                  class="p-2 text-gray-400 hover:text-red-600 transition-colors duration-200"
                  title="Удалить"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </div>
            <div v-if="category.description" class="text-sm text-gray-600 mb-3">
              {{ category.description }}
            </div>
            <div class="flex items-center justify-between text-sm text-gray-500">
              <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                {{ category.transactions_count || 0 }} транзакций
              </span>
              <span>{{ formatDate(category.created_at) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click="closeModal">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" @click.stop>
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">
              {{ editingCategory ? 'Редактировать категорию' : 'Добавить категорию' }}
            </h3>
            <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <form @submit.prevent="saveCategory">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Название *</label>
                <input 
                  v-model="form.name" 
                  type="text" 
                  required 
                  class="form-input"
                  placeholder="Название категории"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Тип *</label>
                <select v-model="form.type" required class="form-select">
                  <option value="">Выберите тип</option>
                  <option value="income">Доход</option>
                  <option value="expense">Расход</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Иконка</label>
                <div class="grid grid-cols-8 gap-2 mb-3">
                  <button
                    v-for="icon in availableIcons"
                    :key="icon"
                    type="button"
                    @click="form.icon = icon"
                    :class="[
                      'p-2 text-xl border rounded-lg hover:bg-gray-50 transition-colors duration-200',
                      form.icon === icon ? 'border-blue-500 bg-blue-50' : 'border-gray-300'
                    ]"
                  >
                    {{ icon }}
                  </button>
                </div>
                <input 
                  v-model="form.icon" 
                  type="text" 
                  class="form-input"
                  placeholder="Или введите свою иконку"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                <textarea 
                  v-model="form.description" 
                  rows="3" 
                  class="form-input"
                  placeholder="Описание категории"
                ></textarea>
              </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button type="button" @click="closeModal" class="btn btn-secondary">
                Отмена
              </button>
              <button type="submit" :disabled="saving" class="btn btn-primary">
                {{ saving ? 'Сохранение...' : (editingCategory ? 'Обновить' : 'Добавить') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

export default {
  name: 'Categories',
  setup() {
    const categories = ref([])
    const loading = ref(false)
    const saving = ref(false)
    const showAddModal = ref(false)
    const editingCategory = ref(null)
    const activeTab = ref('all')
    
    const form = ref({
      name: '',
      type: '',
      icon: '',
      description: ''
    })
    
    const availableIcons = [
      '💰', '💸', '🏠', '🚗', '🍔', '🛒', '💊', '🎬',
      '✈️', '📱', '💻', '👕', '⚡', '🎓', '🏥', '🎯',
      '💼', '🎵', '🏋️', '🍕', '☕', '🚌', '⛽', '🎁',
      '📚', '🧾', '💳', '🏦', '📊', '💡', '🔧', '🎪'
    ]
    
    const filteredCategories = computed(() => {
      if (activeTab.value === 'all') {
        return categories.value
      }
      return categories.value.filter(category => category.type === activeTab.value)
    })
    
    const loadCategories = async () => {
      loading.value = true
      try {
        const response = await axios.get('/api/categories')
        categories.value = response.data
      } catch (error) {
        console.error('Error loading categories:', error)
        alert('Ошибка при загрузке категорий')
      } finally {
        loading.value = false
      }
    }
    
    const resetForm = () => {
      form.value = {
        name: '',
        type: '',
        icon: '',
        description: ''
      }
      editingCategory.value = null
    }
    
    const closeModal = () => {
      showAddModal.value = false
      resetForm()
    }
    
    const editCategory = (category) => {
      editingCategory.value = category
      form.value = {
        name: category.name,
        type: category.type,
        icon: category.icon || '',
        description: category.description || ''
      }
      showAddModal.value = true
    }
    
    const saveCategory = async () => {
      saving.value = true
      try {
        if (editingCategory.value) {
          await axios.put(`/api/categories/${editingCategory.value.id}`, form.value)
        } else {
          await axios.post('/api/categories', form.value)
        }
        
        closeModal()
        loadCategories()
      } catch (error) {
        console.error('Error saving category:', error)
        alert('Ошибка при сохранении категории')
      } finally {
        saving.value = false
      }
    }
    
    const deleteCategory = async (category) => {
      if (confirm('Вы уверены, что хотите удалить эту категорию? Все связанные транзакции также будут удалены.')) {
        try {
          await axios.delete(`/api/categories/${category.id}`)
          loadCategories()
        } catch (error) {
          console.error('Error deleting category:', error)
          alert('Ошибка при удалении категории')
        }
      }
    }
    
    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('ru-RU')
    }
    
    onMounted(() => {
      loadCategories()
    })
    
    return {
      categories,
      loading,
      saving,
      showAddModal,
      editingCategory,
      activeTab,
      form,
      availableIcons,
      filteredCategories,
      closeModal,
      editCategory,
      saveCategory,
      deleteCategory,
      formatDate
    }
  }
}
</script>