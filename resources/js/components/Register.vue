<template>
  <div class="register-container">
    <el-card class="register-card" shadow="always">
      <h2 class="register-title">Register</h2>
      <el-form @submit.prevent="register" class="register-form">
        <el-form-item>
          <el-input
            v-model="name"
            type="text"
            placeholder="Name"
            prefix-icon="el-icon-user"
            size="large"
            clearable
          />
        </el-form-item>
        <el-form-item>
          <el-input
            v-model="email"
            type="email"
            placeholder="Email"
            prefix-icon="el-icon-mail"
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
          <el-input
            v-model="password_confirmation"
            type="password"
            placeholder="Confirm Password"
            prefix-icon="el-icon-lock"
            size="large"
            clearable
          />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" size="large" @click="register" class="register-button">Register</el-button>
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
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
    };
  },
  methods: {
    async register() {
      try {
        await axios.post('/api/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        });
        this.$router.push('/login');
      } catch (error) {
        console.error(error);
      }
    },
  },
};
</script>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f5f5f5;
}

.register-card {
  width: 100%;
  max-width: 400px;
  padding: 20px;
}

.register-title {
  text-align: center;
  margin-bottom: 20px;
  font-size: 1.5rem;
  color: #333;
}

.register-form {
  width: 100%;
}

.el-form-item {
  margin-bottom: 20px;
}

.register-button {
  width: 100%;
}
</style>
