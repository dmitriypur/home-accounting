import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => {
        const token = localStorage.getItem('token');
        // Устанавливаем заголовок Authorization при инициализации, если токен есть
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        }
        return {
            user: null,
            token: token,
            isAuthenticated: !!token
        };
    },

    actions: {
        async login(credentials) {
            try {
                const response = await axios.post('/api/login', credentials);
                const { user, token } = response.data;
                
                this.user = user;
                this.token = token;
                this.isAuthenticated = true;
                
                localStorage.setItem('token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                
                return { success: true };
            } catch (error) {
                return { 
                    success: false, 
                    message: error.response?.data?.message || 'Ошибка входа' 
                };
            }
        },

        async register(userData) {
            try {
                const response = await axios.post('/api/register', userData);
                const { user, token } = response.data;
                
                this.user = user;
                this.token = token;
                this.isAuthenticated = true;
                
                localStorage.setItem('token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                
                return { success: true };
            } catch (error) {
                return { 
                    success: false, 
                    message: error.response?.data?.message || 'Ошибка регистрации' 
                };
            }
        },

        async logout() {
            try {
                if (this.token) {
                    await axios.post('/api/logout');
                }
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                this.user = null;
                this.token = null;
                this.isAuthenticated = false;
                
                localStorage.removeItem('token');
                delete axios.defaults.headers.common['Authorization'];
            }
        },

        async checkAuth() {
            if (this.token) {
                try {
                    axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                    const response = await axios.get('/api/user');
                    this.user = response.data;
                    this.isAuthenticated = true;
                } catch (error) {
                    // Токен недействителен, очищаем состояние
                    this.user = null;
                    this.token = null;
                    this.isAuthenticated = false;
                    localStorage.removeItem('token');
                    delete axios.defaults.headers.common['Authorization'];
                }
            } else {
                // Нет токена, убеждаемся что состояние очищено
                this.isAuthenticated = false;
                this.user = null;
            }
        }
    }
});