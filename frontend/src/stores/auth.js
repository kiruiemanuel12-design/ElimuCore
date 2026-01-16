import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('api_token'))
  const isAuthenticated = ref(!!token.value)

  const login = async (email, password) => {
    try {
      const response = await api.post('/auth/login', { email, password })
      token.value = response.data.data.token
      user.value = response.data.data.user
      localStorage.setItem('api_token', token.value)
      isAuthenticated.value = true
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  }

  const getUser = async () => {
    try {
      const response = await api.get('/auth/user')
      user.value = response.data.data
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  }

  const logout = async () => {
    try {
      await api.post('/auth/logout')
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('api_token')
      isAuthenticated.value = false
    }
  }

  const register = async (data) => {
    try {
      const response = await api.post('/auth/register', data)
      return response.data
    } catch (error) {
      throw error.response?.data || error
    }
  }

  return {
    user,
    token,
    isAuthenticated,
    login,
    logout,
    getUser,
    register
  }
})
