<template>
    <div class="login-container" :class="{ 'mud-dark-theme': isDark }">
      <div class="login-card">
        <h1 class="login-title">{{ $t('LoginTitle') }}</h1>
        
        <div class="login-social-buttons">
          <GoogleLogin :callback="handleGoogleLogin" class="login-social-button login-google cursor-pointer">
            <span class="login-google-text"><i class="fab fa-google mr-2"></i> {{ $t('LoginWithGoogle') }}</span>
          </GoogleLogin>
          <button @click="loginWithFacebook" class="login-social-button login-facebook">
            <i class="fab fa-facebook-f mr-2"></i> {{ $t('LoginWithFacebook') }}
          </button>
          <button @click="loginWithVK" class="login-social-button login-vk">
            <i class="fab fa-vk mr-2"></i> {{ $t('LoginWithVK') }}
          </button>
        </div>
        
        <div class="login-divider">
          <span>{{ $t('Or') }}</span>
        </div>
        
        <form @submit.prevent="submitLogin" class="login-form">
          <div class="login-field">
            <input
              v-model="email"
              type="email"
              :placeholder="$t('EmailLabel')"
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
              :placeholder="$t('PasswordLabel')"
              class="login-input"
              :class="{ 'login-input-error': passwordError }"
              @input="validatePassword"
            />
            <span v-if="passwordError" class="login-error">{{ passwordError }}</span>
          </div>
          <button type="submit" class="login-button" :disabled="!isFormValid">
            {{ $t('LoginButton') }}
          </button>
        </form>
        
        <p class="login-link">
          {{ $t('PleaseLoginOrRegister') }} 
          <router-link to="/register" class="login-link-text">{{ $t('RegisterLink') }}</router-link>
        </p>
      </div>
    </div>
  </template>
  
  <script>
  import { GoogleLogin } from 'vue3-google-login';
  
  export default {
    name: 'Login',
    components: {
      GoogleLogin
    },
    props: {
      isDark: Boolean,
      isSignedIn: Boolean
    },
    data() {
      return {
        email: '',
        password: '',
        emailError: '',
        passwordError: ''
      };
    },
    computed: {
      isFormValid() {
        return this.email && this.password && !this.emailError && !this.passwordError;
      }
    },
    methods: {
      validateEmail() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        this.emailError = emailRegex.test(this.email) ? '' : this.$t('InvalidEmail');
      },
      validatePassword() {
        this.passwordError = this.password.length >= 6 ? '' : this.$t('PasswordRequired');
      },
      submitLogin() {
        if (this.isFormValid) {
          console.log('Login attempt:', { email: this.email, password: this.password });
          localStorage.setItem('isSignedIn', 'true');
          this.$router.push('/calendar');
        }
      },
      handleGoogleLogin(response) {
        console.log('Google login response:', response);
        localStorage.setItem('isSignedIn', 'true');
        this.$router.push('/calendar');
      },
      loginWithFacebook() {
        console.log('Login with Facebook clicked');
        localStorage.setItem('isSignedIn', 'true');
        this.$router.push('/calendar');
      },
      loginWithVK() {
        console.log('Login with VK clicked');
        localStorage.setItem('isSignedIn', 'true');
        this.$router.push('/calendar');
      }
    }
  };
  </script>