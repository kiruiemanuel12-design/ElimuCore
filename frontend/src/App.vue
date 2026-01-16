<template>
  <div id="app">
    <nav class="bg-blue-600 text-white p-4">
      <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">ElimuCore SMIS</h1>
        <div v-if="isAuthenticated" class="flex gap-4">
          <router-link to="/dashboard" class="hover:bg-blue-700 px-3 py-2 rounded">Dashboard</router-link>
          <router-link to="/students" class="hover:bg-blue-700 px-3 py-2 rounded">Students</router-link>
          <router-link to="/staff" class="hover:bg-blue-700 px-3 py-2 rounded">Staff</router-link>
          <button @click="logout" class="hover:bg-blue-700 px-3 py-2 rounded">Logout</button>
        </div>
      </div>
    </nav>

    <main class="container mx-auto p-4">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from './stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const isAuthenticated = ref(false)

onMounted(() => {
  isAuthenticated.value = authStore.isAuthenticated
})

const logout = () => {
  authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
#app {
  min-height: 100vh;
  background-color: #f5f5f5;
}
</style>
