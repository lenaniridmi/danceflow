<template>
  <div id="home" class="home-wrapper" :class="{ 'mud-dark-theme': isDark }">
      <main id="home-main" class="home-main">
          <!-- Герой-секция -->
          <section id="home-hero" class="home-hero">
              <div id="home-hero-overlay" class="home-hero-overlay"></div>
              <div id="home-hero-content" class="home-hero-content">
                  <h1 id="home-hero-title" class="home-hero-title animate-fade-in-up">
                      {{ $t('welcome') }}
                  </h1>
                  <p id="home-hero-subtitle" class="home-hero-subtitle animate-fade-in-up">
                      {{ $t('joinDanceExperience') }}
                  </p>
                  <div id="home-hero-cta" class="home-hero-cta animate-fade-in-up">
                      <router-link id="home-hero-cta-register" class="home-hero-cta-button" :to="{ name: 'register' }">
                          {{ $t('register') }}
                      </router-link>
                      <router-link id="home-hero-cta-login" class="home-hero-cta-button home-hero-cta-secondary" :to="{ name: 'login' }">
                          {{ $t('login') }}
                      </router-link>
                      <a id="home-hero-cta-google" class="home-hero-cta-button home-hero-cta-google" href="/auth/google">
                          {{ $t('loginGoogle') }}
                      </a>
                  </div>
                  <div id="home-hero-image-wrapper" class="home-hero-image-wrapper animate-slide-in-right">
                      <img id="home-hero-image" class="home-hero-image" src="/assets/hero-dance.jpg" alt="DanceFlow Hero">
                  </div>
              </div>
          </section>

          <!-- Видео-секция -->
          <section id="home-video" class="home-video">
              <div class="container text-center">
                  <h2 id="home-video-title" class="home-video-title animate-fade-in-up">
                      {{ $t('watchIntro') }}
                  </h2>
                  <div id="home-video-container" class="home-video-container animate-fade-in-up">
                      <video-player :video-url="'/assets/intro-video.mp4'" :show-notes="false" />
                  </div>
              </div>
          </section>

          <!-- Преимущества -->
          <section id="home-features" class="home-features">
              <div class="container">
                  <h2 id="home-features-title" class="home-features-title animate-fade-in-up">
                      {{ $t('whyChooseUs') }}
                  </h2>
                  <div id="home-features-grid" class="home-features-grid">
                      <div id="home-feature-card-1" class="home-feature-card animate-slide-in-left">
                          <svg id="home-feature-icon-1" class="home-feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          <h3 id="home-feature-title-1" class="home-feature-title">{{ $t('learnAnytime') }}</h3>
                          <p id="home-feature-desc-1" class="home-feature-desc">{{ $t('learnAnytimeDesc') }}</p>
                      </div>
                      <div id="home-feature-card-2" class="home-feature-card animate-slide-in-up">
                          <svg id="home-feature-icon-2" class="home-feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                          </svg>
                          <h3 id="home-feature-title-2" class="home-feature-title">{{ $t('expertInstructors') }}</h3>
                          <p id="home-feature-desc-2" class="home-feature-desc">{{ $t('expertInstructorsDesc') }}</p>
                      </div>
                      <div id="home-feature-card-3" class="home-feature-card animate-slide-in-right">
                          <svg id="home-feature-icon-3" class="home-feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                          </svg>
                          <h3 id="home-feature-title-3" class="home-feature-title">{{ $t('community') }}</h3>
                          <p id="home-feature-desc-3" class="home-feature-desc">{{ $t('communityDesc') }}</p>
                      </div>
                  </div>
              </div>
          </section>

          <!-- Отзывы -->
          <section id="home-testimonials" class="home-testimonials">
              <div class="container">
                  <h2 id="home-testimonials-title" class="home-testimonials-title animate-fade-in-up">
                      {{ $t('testimonials') }}
                  </h2>
                  <div id="home-testimonials-grid" class="home-testimonials-grid">
                      <div id="home-testimonial-1" class="home-testimonial-card animate-slide-in-left">
                          <p id="home-testimonial-quote-1" class="home-testimonial-quote">{{ $t('testimonial1') }}</p>
                          <span id="home-testimonial-author-1" class="home-testimonial-author">{{ $t('testimonialAuthor1') }}</span>
                      </div>
                      <div id="home-testimonial-2" class="home-testimonial-card animate-slide-in-up">
                          <p id="home-testimonial-quote-2" class="home-testimonial-quote">{{ $t('testimonial2') }}</p>
                          <span id="home-testimonial-author-2" class="home-testimonial-author">{{ $t('testimonialAuthor2') }}</span>
                      </div>
                      <div id="home-testimonial-3" class="home-testimonial-card animate-slide-in-right">
                          <p id="home-testimonial-quote-3" class="home-testimonial-quote">{{ $t('testimonial3') }}</p>
                          <span id="home-testimonial-author-3" class="home-testimonial-author">{{ $t('testimonialAuthor3') }}</span>
                      </div>
                  </div>
              </div>
          </section>

          <!-- Контакты -->
          <section id="home-contact" class="home-contact">
              <div class="container">
                  <h2 id="home-contact-title" class="home-contact-title animate-fade-in-up">
                      {{ $t('contactUs') }}
                  </h2>
                  <div id="home-contact-grid" class="home-contact-grid">
                      <div id="home-contact-card" class="home-contact-card">
                          <p id="home-contact-desc" class="home-contact-desc">{{ $t('contactDescription') }}</p>
                          <input
                              id="home-contact-email"
                              v-model="email"
                              type="email"
                              :placeholder="$t('email')"
                              class="home-contact-input"
                          />
                          <textarea
                              id="home-contact-message"
                              v-model="message"
                              :placeholder="$t('message')"
                              class="home-contact-textarea"
                          ></textarea>
                          <button id="home-contact-button" @click="sendMessage" class="home-contact-button">
                              {{ $t('sendMessage') }}
                          </button>
                      </div>
                  </div>
              </div>
          </section>
      </main>
  </div>
</template>

<script>
import VideoPlayer from './VideoPlayer.vue';

export default {
  name: 'Home',
  props: {
      isDark: Boolean,
      isSignedIn: Boolean,
  },
  components: {
      VideoPlayer,
  },
  data() {
      return {
          email: '',
          message: '',
      };
  },
  methods: {
      navigateToLogin() {
          this.$router.push('/login');
      },
      navigateToRegister() {
          this.$router.push('/register');
      },
      navigateToCalendar() {
          this.$router.push('/dashboard');
      },
      navigateToContact() {
          this.$router.push('/contact');
      },
      sendMessage() {
          console.log(`Email: ${this.email}, Message: ${this.message}`);
          this.email = '';
          this.message = '';
      },
  },
  mounted() {
      // Анимации при скролле
      const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
              if (entry.isIntersecting) {
                  entry.target.classList.add('animate-fade-in-up');
                  entry.target.classList.add('animate-slide-in-left');
                  entry.target.classList.add('animate-slide-in-up');
                  entry.target.classList.add('animate-slide-in-right');
              }
          });
      }, { threshold: 0.1 });

      document.querySelectorAll('.animate-fade-in-up, .animate-slide-in-left, .animate-slide-in-up, .animate-slide-in-right').forEach(el => {
          observer.observe(el);
      });
  },
};
</script>