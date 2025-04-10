<template>
  <div class="register-container" :class="{ 'mud-dark-theme': isDark }">
      <div class="register-card">
          <h1 class="register-title">{{ $t('registerButton') }}</h1>

          <div class="register-social-buttons">
              <GoogleLogin :callback="handleGoogleLogin" class="register-social-button register-google cursor-pointer">
                  <span class="register-google-text"><i class="fab fa-google mr-2"></i> {{ $t('registerWithGoogle') }}</span>
              </GoogleLogin>
              <button @click="registerWithYandex" class="register-social-button register-yandex">
                  <i class="fab fa-yandex mr-2"></i> {{ $t('registerWithYandex') }}
              </button>
              <button @click="registerWithMailru" class="register-social-button register-mailru">
                  <i class="fab fa-mail-ru mr-2"></i> {{ $t('registerWithMailru') }}
              </button>
          </div>

          <div class="register-divider">
              <span>{{ $t('or') }}</span>
          </div>

          <form @submit.prevent="submitRegister" class="register-form">
              <div class="register-field">
                  <input
                      v-model="email"
                      type="email"
                      :placeholder="$t('emailLabel')"
                      class="register-input"
                      :class="{ 'register-input-error': emailError }"
                      @input="validateEmail"
                  />
                  <span v-if="emailError" class="register-error">{{ emailError }}</span>
              </div>
              <div class="register-field">
                  <input
                      v-model="password"
                      type="password"
                      :placeholder="$t('passwordLabel')"
                      class="register-input"
                      :class="{ 'register-input-error': passwordError }"
                      @input="validatePassword"
                  />
                  <span v-if="passwordError" class="register-error">{{ passwordError }}</span>
              </div>
              <div class="register-field">
                  <input
                      v-model="confirmPassword"
                      type="password"
                      :placeholder="$t('confirmPassword')"
                      class="register-input"
                      :class="{ 'register-input-error': confirmPasswordError }"
                      @input="validateConfirmPassword"
                  />
                  <span v-if="confirmPasswordError" class="register-error">{{ confirmPasswordError }}</span>
              </div>
              <button type="submit" class="register-button" :disabled="!isFormValid">
                  {{ $t('registerButton') }}
              </button>
          </form>

          <p class="register-link">
              {{ $t('alreadyHaveAccount') }}
              <router-link to="/login" class="register-link-text">{{ $t('loginButton') }}</router-link>
          </p>
      </div>
  </div>
</template>

<script>
import { GoogleLogin } from 'vue3-google-login';

export default {
  name: 'Register',
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
          confirmPassword: '',
          emailError: '',
          passwordError: '',
          confirmPasswordError: '',
      };
  },
  computed: {
      isFormValid() {
          return this.email && this.password && this.confirmPassword &&
                 !this.emailError && !this.passwordError && !this.confirmPasswordError;
      },
  },
  methods: {
      validateEmail() {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          this.emailError = emailRegex.test(this.email) ? '' : this.$t('invalidEmail');
      },
      validatePassword() {
          this.passwordError = this.password.length >= 6 ? '' : this.$t('passwordRequired');
          this.validateConfirmPassword();
      },
      validateConfirmPassword() {
          this.confirmPasswordError = this.password === this.confirmPassword ? '' : this.$t('passwordsDoNotMatch');
      },
      async submitRegister() {
          if (this.isFormValid) {
              try {
                  const response = await window.axios.post('/api/auth/register', {
                      email: this.email,
                      password: this.password,
                  });
                  console.log('Register successful:', response.data);
                  localStorage.setItem('isSignedIn', 'true');
                  this.$router.push('/dashboard');
              } catch (error) {
                  console.error('Register failed:', error);
                  this.emailError = 'Ошибка регистрации';
                  this.passwordError = 'Ошибка регистрации';
              }
          }
      },
      handleGoogleLogin(response) {
          console.log('Google login response:', response);
          window.axios.post('/api/auth/google/callback', { credential: response.credential })
              .then(response => {
                  console.log('Google register successful:', response.data);
                  localStorage.setItem('isSignedIn', 'true');
                  this.$router.push('/dashboard');
              })
              .catch(error => {
                  console.error('Google register failed:', error);
                  this.emailError = 'Ошибка авторизации через Google';
              });
      },
      registerWithYandex() {
          const clientId = '1a559d3253024b65b5f46e22fe24c0ac';
          const redirectUri = 'http://127.0.0.1:8000/api/auth/yandex/callback'; // Используем 127.0.0.1
          const yandexAuthUrl = `https://oauth.yandex.com/authorize?response_type=code&client_id=${clientId}&redirect_uri=${redirectUri}`;
          window.location.href = yandexAuthUrl;
      },
      registerWithMailru() {
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