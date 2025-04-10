<template>
  <div class="login-container" :class="{ 'mud-dark-theme': isDark }">
      <div class="login-card">
          <h1 class="login-title">{{ $t('loginTitle') }}</h1>

          <div class="login-social-buttons">
              <GoogleLogin :callback="handleGoogleLogin" class="login-social-button login-google cursor-pointer">
                  <span class="login-google-text"><i class="fab fa-google mr-2"></i> {{ $t('loginWithGoogle') }}</span>
              </GoogleLogin>
              <button @click="loginWithYandex" class="login-social-button login-yandex">
                  <i class="fab fa-yandex mr-2"></i> {{ $t('loginWithYandex') }}
              </button>
              <button @click="loginWithMailru" class="login-social-button login-mailru">
                  <i class="fab fa-mail-ru mr-2"></i> {{ $t('loginWithMailru') }}
              </button>
          </div>

          <div class="login-divider">
              <span>{{ $t('or') }}</span>
          </div>

          <form @submit.prevent="submitLogin" class="login-form">
              <div class="login-field">
                  <input
                      v-model="email"
                      type="email"
                      :placeholder="$t('emailLabel')"
                      class="login-input"
                      :class="{ 'login-input-error': emailError }"
                      @input="validateEmail"
                  />
                  <span v-if="emailError" class="login-error">{{ emailError }}</span>
              </div>
              <div class="login-field">
                  <input
                      v-model="password"
                      type="password"
                      :placeholder="$t('passwordLabel')"
                      class="login-input"
                      :class="{ 'login-input-error': passwordError }"
                      @input="validatePassword"
                  />
                  <span v-if="passwordError" class="login-error">{{ passwordError }}</span>
              </div>
              <button type="submit" class="login-button" :disabled="!isFormValid">
                  {{ $t('loginButton') }}
              </button>
          </form>

          <p class="login-link">
              {{ $t('pleaseLoginOrRegister') }}
              <router-link to="/register" class="login-link-text">{{ $t('registerLink') }}</router-link>
          </p>
      </div>
  </div>
</template>

<script>
import { GoogleLogin } from 'vue3-google-login';

export default {
  name: 'Login',
  components: {
      GoogleLogin,
  },
  props: {
      isDark: Boolean,
      isSignedIn: Boolean,
  },
  data() {
      return {
          email: '',
          password: '',
          emailError: '',
          passwordError: '',
      };
  },
  computed: {
      isFormValid() {
          return this.email && this.password && !this.emailError && !this.passwordError;
      },
  },
  methods: {
      validateEmail() {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          this.emailError = emailRegex.test(this.email) ? '' : this.$t('invalidEmail');
      },
      validatePassword() {
          this.passwordError = this.password.length >= 6 ? '' : this.$t('passwordRequired');
      },
      async submitLogin() {
          if (this.isFormValid) {
              try {
                  const response = await window.axios.post('/api/auth/login', {
                      email: this.email,
                      password: this.password,
                  });
                  console.log('Login successful:', response.data);
                  localStorage.setItem('isSignedIn', 'true');
                  this.$router.push('/dashboard');
              } catch (error) {
                  console.error('Login failed:', error);
                  this.emailError = 'Неверный email или пароль';
                  this.passwordError = 'Неверный email или пароль';
              }
          }
      },
      handleGoogleLogin(response) {
          console.log('Google login response:', response);
          window.axios.post('/api/auth/google/callback', { credential: response.credential })
              .then(response => {
                  console.log('Google login successful:', response.data);
                  localStorage.setItem('isSignedIn', 'true');
                  this.$router.push('/dashboard');
              })
              .catch(error => {
                  console.error('Google login failed:', error);
                  this.emailError = 'Ошибка авторизации через Google';
              });
      },
      loginWithYandex() {
          const clientId = '1a559d3253024b65b5f46e22fe24c0ac';
          const redirectUri = 'http://127.0.0.1:8000/api/auth/yandex/callback'; // Используем 127.0.0.1
          const yandexAuthUrl = `https://oauth.yandex.com/authorize?response_type=code&client_id=${clientId}&redirect_uri=${redirectUri}`;
          window.location.href = yandexAuthUrl;
      },
      loginWithMailru() {
          const clientId = 'b2f2212a2a7d4fb9b5ca2153ebbef3ff';
          const redirectUri = 'http://127.0.0.1:8000/api/auth/mailru/callback'; // Используем 127.0.0.1
          const mailruAuthUrl = `https://oauth.mail.ru/login?client_id=${clientId}&response_type=code&redirect_uri=${redirectUri}`;
          window.location.href = mailruAuthUrl;
      },
  },
  created() {
      if (this.isSignedIn) {
          this.$router.push('/dashboard');
      }
  },
};
</script>