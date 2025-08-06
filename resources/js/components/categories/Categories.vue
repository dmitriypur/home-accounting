<template>
  <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Заголовок -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
      <div class="mb-4 sm:mb-0">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2">
          Категории расходов 🏷️
        </h1>
        <p class="text-gray-600 text-lg">Организуйте свои расходы по категориям</p>
      </div>
      <button @click="openModal()" class="btn btn-primary flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Добавить категорию</span>
      </button>
    </div>

    <!-- Список категорий -->
    <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div v-for="i in 8" :key="i" class="animate-pulse">
        <div class="bg-white rounded-xl p-6 shadow-sm">
          <div class="flex items-center space-x-4 mb-4">
            <div class="w-12 h-12 bg-gray-300 rounded-xl"></div>
            <div class="flex-1">
              <div class="h-4 bg-gray-300 rounded w-3/4 mb-2"></div>
              <div class="h-3 bg-gray-300 rounded w-1/2"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else-if="categories.length === 0" class="text-center py-16">
      <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
        </svg>
      </div>
      <h3 class="text-xl font-semibold text-gray-900 mb-2">Нет категорий</h3>
      <p class="text-gray-500 mb-6">Создайте первую категорию для организации расходов</p>
      <button @click="openModal()" class="btn btn-primary">
        Создать категорию
      </button>
    </div>
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div 
        v-for="(category, index) in categories" 
        :key="category.id" 
        class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden animate-scale-in"
        :style="{ animationDelay: `${index * 0.1}s` }"
      >
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200" :style="{ backgroundColor: category.color + '20' }">
                <span class="text-2xl">{{ category.icon || '📁' }}</span>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                  {{ category.name }}
                </h3>
                <div class="flex items-center space-x-2 mt-1">
                  <div class="w-3 h-3 rounded-full" :style="{ backgroundColor: category.color }"></div>
                  <span class="text-xs text-gray-500 font-medium">{{ category.color }}</span>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Статистика категории -->
          <div class="mb-4 p-3 bg-gray-50 rounded-lg">
            <div class="flex items-center justify-between text-sm">
              <span class="text-gray-600">Расходов в этом месяце:</span>
              <span class="font-semibold text-gray-900">{{ category.expenses_count || 0 }}</span>
            </div>
            <div class="flex items-center justify-between text-sm mt-1">
              <span class="text-gray-600">Общая сумма:</span>
              <span class="font-semibold text-gray-900">{{ formatCurrency(category.total_amount || 0) }}</span>
            </div>
          </div>
          
          <!-- Действия -->
          <div class="flex items-center space-x-2">
            <button 
              @click="openModal(category)" 
              class="flex-1 btn btn-secondary text-sm py-2 flex items-center justify-center space-x-1"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
              <span>Изменить</span>
            </button>
            <button 
              @click="deleteCategory(category)" 
              class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
            </button>
          </div>
        </div>
        
        <!-- Цветная полоска снизу -->
        <div class="h-1" :style="{ backgroundColor: category.color }"></div>
      </div>
    </div>
   

    <!-- Модальное окно -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-hidden transform transition-all duration-300 animate-scale-in">
        <div class="p-6 max-h-[90vh] overflow-y-auto">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-900">
                {{ editingCategory ? 'Редактировать категорию' : 'Новая категория' }}
              </h3>
            </div>
            <button @click="closeModal" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <form @submit.prevent="saveCategory" class="space-y-6">
            <!-- Предварительный просмотр -->
            <div class="bg-gray-50 rounded-xl p-4">
              <p class="text-sm font-medium text-gray-700 mb-3">Предварительный просмотр:</p>
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" :style="{ backgroundColor: (form.color || '#3B82F6') + '20' }">
                  <span class="text-2xl">{{ form.icon || '📁' }}</span>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900">{{ form.name || 'Название категории' }}</h4>
                  <div class="flex items-center space-x-2 mt-1">
                    <div class="w-3 h-3 rounded-full" :style="{ backgroundColor: form.color || '#3B82F6' }"></div>
                    <span class="text-xs text-gray-500">{{ form.color || '#3B82F6' }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div>
              <label class="form-label">Название категории</label>
              <input 
                v-model="form.name" 
                type="text" 
                required 
                class="form-input" 
                placeholder="Например: Продукты, Транспорт, Развлечения"
              >
            </div>
            
            <div>
              <label class="form-label">Иконка</label>
              <div class="grid grid-cols-8 gap-2 mb-3">
                <button 
                  v-for="emoji in popularEmojis" 
                  :key="emoji"
                  type="button"
                  @click="form.icon = emoji"
                  :class="[
                    'p-2 text-xl rounded-lg border-2 transition-all duration-200 hover:scale-110',
                    form.icon === emoji ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'
                  ]"
                >
                  {{ emoji }}
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
              <label class="form-label">Цвет</label>
              <div class="grid grid-cols-8 gap-2 mb-3">
                <button 
                  v-for="color in popularColors" 
                  :key="color"
                  type="button"
                  @click="form.color = color"
                  :class="[
                    'w-8 h-8 rounded-lg border-2 transition-all duration-200 hover:scale-110',
                    form.color === color ? 'border-gray-800 ring-2 ring-gray-300' : 'border-gray-200'
                  ]"
                  :style="{ backgroundColor: color }"
                ></button>
              </div>
              <input 
                v-model="form.color" 
                type="color" 
                class="form-input h-12"
              >
            </div>
            
            <div v-if="error" class="mb-4 text-red-600 text-sm">
              {{ error }}
            </div>
            
            <div class="flex space-x-3 pt-4">
              <button type="button" @click="closeModal" class="flex-1 btn btn-secondary">
                Отмена
              </button>
              <button type="submit" class="flex-1 btn btn-primary" :disabled="!form.name || saving">
                {{ saving ? 'Сохранение...' : (editingCategory ? 'Обновить' : 'Создать') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'Categories',
  setup() {
    const categories = ref([]);
    const loading = ref(true);
    const showModal = ref(false);
    const editingCategory = ref(null);
    const saving = ref(false);
    const error = ref('');
    
    const form = ref({
      name: '',
      icon: '',
      color: '#3B82F6'
    });

    const popularEmojis = ref([
      '🛒', '🚗', '🎬', '🏠', '👕', '💊', '📚', '🍽️',
      '⚡', '📱', '🎁', '✈️', '🏋️', '🐕', '🔧', '💰'
    ]);

    const popularColors = ref([
      '#3B82F6', '#EF4444', '#10B981', '#F59E0B',
      '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16',
      '#F97316', '#6366F1', '#14B8A6', '#F43F5E',
      '#8B5A2B', '#6B7280', '#1F2937', '#7C3AED'
    ]);

    const formatCurrency = (amount) => {
      return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB'
      }).format(amount);
    };

    const loadCategories = async () => {
      try {
        const response = await axios.get('/api/categories');
        categories.value = response.data;
      } catch (error) {
        console.error('Error loading categories:', error);
      } finally {
        loading.value = false;
      }
    };

    const openModal = (category = null) => {
      editingCategory.value = category;
      if (category) {
        form.value = {
          name: category.name,
          icon: category.icon || '',
          color: category.color || '#3B82F6'
        };
      } else {
        form.value = {
          name: '',
          icon: '',
          color: '#3B82F6'
        };
      }
      error.value = '';
      showModal.value = true;
    };

    const closeModal = () => {
      showModal.value = false;
      editingCategory.value = null;
      error.value = '';
    };

    const saveCategory = async () => {
      saving.value = true;
      error.value = '';
      
      try {
        if (editingCategory.value) {
          await axios.put(`/api/categories/${editingCategory.value.id}`, form.value);
        } else {
          await axios.post('/api/categories', form.value);
        }
        
        await loadCategories();
        closeModal();
      } catch (err) {
        error.value = err.response?.data?.message || 'Ошибка сохранения';
      } finally {
        saving.value = false;
      }
    };

    const deleteCategory = async (category) => {
      if (!confirm(`Удалить категорию "${category.name}"?`)) {
        return;
      }
      
      try {
        await axios.delete(`/api/categories/${category.id}`);
        await loadCategories();
      } catch (error) {
        alert('Ошибка удаления категории');
      }
    };

    onMounted(() => {
      loadCategories();
    });

    return {
      categories,
      loading,
      showModal,
      editingCategory,
      saving,
      error,
      form,
      popularEmojis,
      popularColors,
      formatCurrency,
      openModal,
      closeModal,
      saveCategory,
      deleteCategory
    };
  }
};
</script>