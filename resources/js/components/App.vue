<template>
  <div class="app-container" :class="{ 'mud-dark-theme': isDark }">
    <Header :isDark="isDark" :currentLanguage="currentLanguage" :isSignedIn="isSignedIn" 
            @toggle-dark="toggleDark" @change-language="changeLanguage" @logout="logout" />
    <main class="main-content">
      <router-view :isDark="isDark" :isSignedIn="isSignedIn"></router-view>
    </main>
    <Footer />
  </div>
</template>

<script>
import Header from './Header.vue';
import Footer from './Footer.vue';

export default {
  name: 'App',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      isDark: false,
      currentLanguage: 'en',
      isSignedIn: false
    };
  },
  methods: {
    toggleDark() {
      this.isDark = !this.isDark;
      localStorage.setItem('darkMode', this.isDark);
      if (this.isDark) {
        document.body.classList.add('mud-dark-theme');
      } else {
        document.body.classList.remove('mud-dark-theme');
      }
    },
    changeLanguage(lang) {
      this.currentLanguage = lang;
      this.$i18n.locale = lang;
      localStorage.setItem('language', lang);
    },
    logout() {
      this.isSignedIn = false;
      localStorage.setItem('isSignedIn', 'false');
      this.$router.push('/login');
    }
  },
  created() {
    const savedDarkMode = localStorage.getItem('darkMode');
    if (savedDarkMode) {
      this.isDark = savedDarkMode === 'true';
      if (this.isDark) document.body.classList.add('mud-dark-theme');
    }
    const savedLanguage = localStorage.getItem('language');
    if (savedLanguage) {
      this.currentLanguage = savedLanguage;
      this.$i18n.locale = savedLanguage;
    }
    const signedIn = localStorage.getItem('isSignedIn');
    if (signedIn === 'true') this.isSignedIn = true;
  }
};
</script>