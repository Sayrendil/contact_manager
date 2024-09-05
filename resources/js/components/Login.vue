<template>
  <div class="login-container">
    <el-card class="login-card" shadow="always">
      <h2 class="login-title">Login</h2>
      <el-form @submit.prevent="login" class="login-form">
        <el-form-item>
          <el-input
            v-model="email"
            type="email"
            placeholder="Email"
            prefix-icon="el-icon-user"
            size="large"
            clearable
          />
        </el-form-item>
        <el-form-item>
          <el-input
            v-model="password"
            type="password"
            placeholder="Password"
            prefix-icon="el-icon-lock"
            size="large"
            clearable
          />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" size="large" @click="login" class="login-button">Login</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '',
      password: '',
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('/api/login', {
          email: this.email,
          password: this.password,
        });
        localStorage.setItem('token', response.data.token);
        this.$router.push('/contacts');
      } catch (error) {
        console.error(error);
      }
    },
  },
};
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f5f5f5;
}

.login-card {
  width: 100%;
  max-width: 400px;
  padding: 20px;
}

.login-title {
  text-align: center;
  margin-bottom: 20px;
  font-size: 1.5rem;
  color: #333;
}

.login-form {
  width: 100%;
}

.el-form-item {
  margin-bottom: 20px;
}

.login-button {
  width: 100%;
}
</style>
