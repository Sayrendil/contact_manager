<template>
  <div class="container">
    <h1 class="title">Welcome to Contact Management System</h1>
    <div class="button-group">
      <!-- Отобразить кнопки для входа и регистрации только если пользователь не авторизован -->
      <el-button v-if="!isLoggedIn" type="primary" @click="goToLogin">Вход</el-button>
      <el-button v-if="!isLoggedIn" type="success" @click="goToRegister">Регистрация</el-button>
      <!-- Отобразить кнопку для перехода к контактам всегда -->
      <el-button type="info" @click="goToContacts">Контакты</el-button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isLoggedIn: false, // Статус авторизации пользователя
    };
  },
  created() {
    this.checkAuthStatus(); // Проверяем статус авторизации при создании компонента
  },
  methods: {
    checkAuthStatus() {
      // Логика для проверки, авторизован ли пользователь
      // Например, проверяем наличие токена в localStorage
      this.isLoggedIn = !!localStorage.getItem('token'); // Проверяем наличие токена
    },
    goToLogin() {
      this.$router.push('/login');
    },
    goToRegister() {
      this.$router.push('/register');
    },
    goToContacts() {
      this.$router.push('/contacts');
    },
  },
};
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  background-color: #f5f5f5;
  padding: 20px;
}

.title {
  font-size: 2rem;
  color: #333;
  margin-bottom: 20px;
}

.button-group {
  display: flex;
  gap: 15px;
}

.el-button {
  font-size: 1rem;
  padding: 10px 20px;
}
</style>
