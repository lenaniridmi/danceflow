<template>
  <header id="header" class="header">
      <div id="header-container" class="header-container">
          <!-- Логотип -->
          <router-link id="header-logo-link" to="/" class="header-logo animate-slide-in-left">
              <span id="header-logo-text" class="header-logo-text">DanceFlow</span>
          </router-link>

          <!-- Навигация -->
          <nav id="header-nav" class="header-nav">
              <router-link v-if="$route.name !== 'home'" id="header-nav-home" class="header-nav-link" :to="{ name: 'home' }">{{ $t('home') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-dashboard" class="header-nav-link" :to="{ name: 'dashboard' }">{{ $t('dashboard') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-courses" class="header-nav-link" :to="{ name: 'courses' }">{{ $t('courses') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-progress" class="header-nav-link" :to="{ name: 'progress' }">{{ $t('progress') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-messages" class="header-nav-link" :to="{ name: 'messages' }">{{ $t('messages') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-favorites" class="header-nav-link" :to="{ name: 'favorites' }">{{ $t('favorites') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-reviews" class="header-nav-link" :to="{ name: 'reviews' }">{{ $t('reviews') }}</router-link>
              <router-link v-if="isSignedIn" id="header-nav-profile" class="header-nav-link" :to="{ name: 'profile' }">{{ $t('profile') }}</router-link>
              <a v-if="isSignedIn" id="header-nav-logout" class="header-nav-link" href="#" @click.prevent="$emit('logout')">{{ $t('logout') }}</a>
              <router-link v-else id="header-nav-login" class="header-nav-link" :to="{ name: 'login' }">{{ $t('login') }}</router-link>
              <router-link v-else id="header-nav-register" class="header-nav-link" :to="{ name: 'register' }">{{ $t('register') }}</router-link>
          </nav>

          <!-- Выбор языка и переключение темы -->
          <div id="header-actions" class="header-actions">
              <select id="header-language-select" class="header-language-select animate-slide-in-right" v-model="localLanguage" @change="$emit('change-language', localLanguage)">
                  <option value="en">{{ $t('english') }}</option>
                  <option value="ru">{{ $t('russian') }}</option>
              </select>
              <div id="header-theme-switch" class="header-theme-switch">
                  <label id="theme-switch-label" class="theme-switch-label">
                      <input id="theme-switch-input" type="checkbox" class="theme-switch-input" :checked="isDark" @change="$emit('toggle-dark')">
                      <span id="theme-switch-slider" class="theme-switch-slider">
                          <span id="theme-switch-icon-sun" class="theme-switch-icon sun">☀️</span>
                          <span id="theme-switch-icon-moon" class="theme-switch-icon moon">🌙</span>
                      </span>
                  </label>
              </div>
          </div>

          <!-- Мобильное меню -->
          <button id="header-menu-toggle" class="header-menu-toggle lg:hidden" @click="toggleMenu">
              <svg id="header-menu-icon" class="header-menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
              </svg>
          </button>
          <nav id="header-nav-open" v-if="isMenuOpen" class="header-nav-open lg:hidden">
              <router-link v-if="$route.name !== 'home'" id="header-nav-open-home" class="header-nav-link" :to="{ name: 'home' }">{{ $t('home') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-open-dashboard" class="header-nav-link" :to="{ name: 'dashboard' }">{{ $t('dashboard') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-open-courses" class="header-nav-link" :to="{ name: 'courses' }">{{ $t('courses') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-open-progress" class="header-nav-link" :to="{ name: 'progress' }">{{ $t('progress') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-open-messages" class="header-nav-link" :to="{ name: 'messages' }">{{ $t('messages') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-open-favorites" class="header-nav-link" :to="{ name: 'favorites' }">{{ $t('favorites') }}</router-link>
              <router-link v-if="$route.name !== 'home'" id="header-nav-open-reviews" class="header-nav-link" :to="{ name: 'reviews' }">{{ $t('reviews') }}</router-link>
              <router-link v-if="isSignedIn" id="header-nav-open-profile" class="header-nav-link" :to="{ name: 'profile' }">{{ $t('profile') }}</router-link>
              <a v-if="isSignedIn" id="header-nav-open-logout" class="header-nav-link" href="#" @click.prevent="$emit('logout')">{{ $t('logout') }}</a>
              <router-link v-else id="header-nav-open-login" class="header-nav-link" :to="{ name: 'login' }">{{ $t('login') }}</router-link>
              <router-link v-else id="header-nav-open-register" class="header-nav-link" :to="{ name: 'register' }">{{ $t('register') }}</router-link>
          </nav>
      </div>
  </header>
</template>

<script>
export default {
  name: 'Header',
  props: {
      isDark: Boolean,
      currentLanguage: String,
      isSignedIn: Boolean,
  },
  data() {
      return {
          localLanguage: this.currentLanguage,
          isMenuOpen: false,
      };
  },
  methods: {
      toggleMenu() {
          this.isMenuOpen = !this.isMenuOpen;
      },
  },
  watch: {
      currentLanguage(newLang) {
          this.localLanguage = newLang;
      },
  },
};
</script>