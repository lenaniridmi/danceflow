<template>
    <div class="register-container" :class="{ 'mud-dark-theme': isDark }">
      <div class="register-card">
        <h1 class="register-title">{{ $t('RegisterButton') }}</h1>
        
        <div class="register-social-buttons">
          <GoogleLogin :callback="handleGoogleLogin" class="register-social-button register-google cursor-pointer">
            <span class="register-google-text"><i class="fab fa-google mr-2"></i> {{ $t('RegisterWithGoogle') }}</span>
          </GoogleLogin>
          <button @click="registerWithFacebook" class="register-social-button register-facebook">
            <i class="fab fa-facebook-f mr-2"></i> {{ $t('RegisterWithFacebook') }}
          </button>
          <button @click="registerWithVK" class="register-social-button register-vk">
            <i class="fab fa-vk mr-2"></i> {{ $t('RegisterWithVK') }}
          </button>
        </div>
        
        <div class="register-divider">
          <span>{{ $t('Or') }}</span>
        </div>
        
        <form @submit.prevent="submitRegister" class="register-form">
          <div class="register-field">
            <input
              v-model="email"
              type="email"
              :placeholder="$t('EmailLabel')"
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
              :placeholder="$t('PasswordLabel')"
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
              :placeholder="$t('ConfirmPassword')"
              class="register-input"
              :class="{ 'register-input-error': confirmPasswordError }"
              @input="validateConfirmPassword"
            />
            <span v-if="confirmPasswordError" class="register-error">{{ confirmPasswordError }}</span>
          </div>
          <button type="submit" class="register-button" :disabled="!isFormValid">
            {{ $t('RegisterButton') }}
          </button>
        </form>
        
        <p class="register-link">
          {{ $t('AlreadyHaveAccount') }} 
          <router-link to="/login" class="register-link-text">{{ $t('LoginButton') }}</router-link>
        </p>
      </div>
    </div>
  </template>
  
  <script>
  import { GoogleLogin } from 'vue3-google-login';
  
  export default {
    name: 'Register',
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
        confirmPassword: '',
        emailError: '',
        passwordError: '',
        confirmPasswordError: ''
      };
    },
    computed: {
      isFormValid() {
        return this.email && this.password && this.confirmPassword && 
               !this.emailError && !this.passwordError && !this.confirmPasswordError;
      }
    },
    methods: {
      validateEmail() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        this.emailError = emailRegex.test(this.email) ? '' : this.$t('InvalidEmail');
      },
      validatePassword() {
        this.passwordError = this.password.length >= 6 ? '' : this.$t('PasswordRequired');
        this.validateConfirmPassword();
      },
      validateConfirmPassword() {
        this.confirmPasswordError = this.password === this.confirmPassword ? '' : this.$t('PasswordsDoNotMatch');
      },
      submitRegister() {
        if (this.isFormValid) {
          console.log('Register attempt:', { email: this.email, password: this.password });
          localStorage.setItem('isSignedIn', 'true');
          this.$router.push('/calendar');
        }
      },
      handleGoogleLogin(response) {
        console.log('Google login response:', response);
        localStorage.setItem('isSignedIn', 'true');
        this.$router.push('/calendar');
      },
      registerWithFacebook() {
        console.log('Register with Facebook clicked');
        localStorage.setItem('isSignedIn', 'true');
        this.$router.push('/calendar');
      },
      registerWithVK() {
        console.log('Register with VK clicked');
        localStorage.setItem('isSignedIn', 'true');
        this.$router.push('/calendar');
      }
    }
  };
  </script>