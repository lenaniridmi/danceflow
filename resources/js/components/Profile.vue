<template>
  <div id="profile" class="profile-wrapper" :class="{ 'mud-dark-theme': isDark }">
      <main id="profile-main" class="profile-main">
          <!-- Заголовок -->
          <section id="profile-header" class="profile-header">
              <div class="container">
                  <h1 id="profile-title" class="profile-title animate-fade-in-up">
                      {{ $t('profile') }}
                  </h1>
                  <p id="profile-subtitle" class="profile-subtitle animate-fade-in-up">
                      {{ $t('profileSubtitle') }}
                  </p>
              </div>
          </section>

          <!-- Личная информация -->
          <section id="profile-info" class="profile-section">
              <div class="profile-card">
                  <h2 id="profile-info-title" class="profile-card-title">{{ $t('personalInfo') }}</h2>
                  <div id="profile-info-details" class="profile-info-details">
                      <img id="profile-avatar" :src="userAvatar" alt="Avatar" class="profile-avatar" />
                      <div id="profile-details" class="profile-details">
                          <p><strong>{{ $t('name') }}:</strong> {{ userName }}</p>
                          <p><strong>{{ $t('email') }}:</strong> {{ userEmail }}</p>
                          <p><strong>{{ $t('danceLevel') }}:</strong> {{ userDanceLevel }}</p>
                          <p><strong>{{ $t('joinedDate') }}:</strong> {{ userJoinedDate }}</p>
                      </div>
                  </div>
                  <button id="profile-edit-button" @click="editProfile" class="profile-button animate-pulse">
                      {{ $t('editProfile') }}
                  </button>
              </div>
          </section>

          <!-- Танцевальная статистика -->
          <section id="profile-stats" class="profile-section">
              <div class="profile-card">
                  <h2 id="profile-stats-title" class="profile-card-title">{{ $t('danceStats') }}</h2>
                  <div id="profile-stats-grid" class="profile-stats-grid">
                      <div id="profile-stats-courses" class="profile-stats-item animate-slide-in-left">
                          <h3 id="profile-stats-courses-title" class="profile-stats-title">{{ $t('coursesEnrolled') }}</h3>
                          <p id="profile-stats-courses-value" class="profile-stats-value">{{ stats.coursesEnrolled }}</p>
                      </div>
                      <div id="profile-stats-lessons" class="profile-stats-item animate-slide-in-up">
                          <h3 id="profile-stats-lessons-title" class="profile-stats-title">{{ $t('lessonsCompleted') }}</h3>
                          <p id="profile-stats-lessons-value" class="profile-stats-value">{{ stats.lessonsCompleted }}</p>
                      </div>
                      <div id="profile-stats-hours" class="profile-stats-item animate-slide-in-right">
                          <h3 id="profile-stats-hours-title" class="profile-stats-title">{{ $t('hoursDanced') }}</h3>
                          <p id="profile-stats-hours-value" class="profile-stats-value">{{ stats.hoursDanced }}</p>
                      </div>
                  </div>
              </div>
          </section>

          <!-- Любимые стили танцев -->
          <section id="profile-favorite-styles" class="profile-section">
              <div class="profile-card">
                  <h2 id="profile-favorite-styles-title" class="profile-card-title">{{ $t('favoriteDanceStyles') }}</h2>
                  <div id="profile-favorite-styles-list" class="profile-favorite-styles-list">
                      <div
                          v-for="style in favoriteStyles"
                          :key="style"
                          :id="'profile-favorite-style-' + style"
                          class="profile-favorite-style animate-fade-in-up"
                      >
                          <span>{{ style }}</span>
                          <button
                              :id="'profile-favorite-style-remove-' + style"
                              class="profile-favorite-style-remove"
                              @click="removeFavoriteStyle(style)"
                          >
                              <i class="fas fa-times"></i>
                          </button>
                      </div>
                  </div>
                  <div id="profile-favorite-styles-add" class="profile-favorite-styles-add">
                      <select id="profile-favorite-styles-select" v-model="newStyle" class="profile-select">
                          <option value="" disabled>{{ $t('selectStyle') }}</option>
                          <option value="Hip-Hop">{{ $t('hipHop') }}</option>
                          <option value="Salsa">{{ $t('salsa') }}</option>
                          <option value="Contemporary">{{ $t('contemporary') }}</option>
                          <option value="Ballroom">{{ $t('ballroom') }}</option>
                      </select>
                      <button id="profile-favorite-styles-add-button" @click="addFavoriteStyle" class="profile-button">
                          {{ $t('addStyle') }}
                      </button>
                  </div>
              </div>
          </section>

          <!-- Настройки аккаунта -->
          <section id="profile-settings" class="profile-section">
              <div class="profile-card">
                  <h2 id="profile-settings-title" class="profile-card-title">{{ $t('accountSettings') }}</h2>
                  <div id="profile-settings-content" class="profile-settings-content">
                      <label id="profile-settings-language-label" class="profile-setting-label">{{ $t('language') }}</label>
                      <select id="profile-settings-language" v-model="localLanguage" @change="$emit('change-language', localLanguage)" class="profile-select">
                          <option value="en">{{ $t('english') }}</option>
                          <option value="ru">{{ $t('russian') }}</option>
                      </select>
                      <label id="profile-settings-theme-label" class="profile-setting-label">{{ $t('darkTheme') }}</label>
                      <input id="profile-settings-theme" type="checkbox" :checked="isDark" @change="$emit('toggle-dark')" class="profile-checkbox" />
                      <label id="profile-settings-notifications-label" class="profile-setting-label">{{ $t('notifications') }}</label>
                      <div id="profile-settings-notifications" class="profile-settings-notifications">
                          <label class="flex items-center space-x-2">
                              <input type="checkbox" v-model="emailNotifications" class="profile-checkbox" />
                              <span>{{ $t('emailNotifications') }}</span>
                          </label>
                          <label class="flex items-center space-x-2">
                              <input type="checkbox" v-model="pushNotifications" class="profile-checkbox" />
                              <span>{{ $t('pushNotifications') }}</span>
                          </label>
                      </div>
                  </div>
                  <button id="profile-settings-save" @click="saveSettings" class="profile-button">{{ $t('saveChanges') }}</button>
              </div>
          </section>

          <!-- Конфиденциальность -->
          <section id="profile-privacy" class="profile-section">
              <div class="profile-card">
                  <h2 id="profile-privacy-title" class="profile-card-title">{{ $t('privacySettings') }}</h2>
                  <div id="profile-privacy-content" class="profile-settings-content">
                      <label id="profile-privacy-visibility-label" class="profile-setting-label">{{ $t('profileVisibility') }}</label>
                      <select id="profile-privacy-visibility" v-model="privacyLevel" class="profile-select">
                          <option value="only-me">{{ $t('onlyMe') }}</option>
                          <option value="friends">{{ $t('friends') }}</option>
                          <option value="everyone">{{ $t('everyone') }}</option>
                      </select>
                      <label id="profile-privacy-share-label" class="profile-setting-label">{{ $t('shareProgress') }}</label>
                      <input id="profile-privacy-share" type="checkbox" v-model="shareProgress" class="profile-checkbox" />
                  </div>
                  <button id="profile-privacy-save" @click="savePrivacy" class="profile-button">{{ $t('savePrivacy') }}</button>
              </div>
          </section>

          <!-- Действия с аккаунтом -->
          <section id="profile-actions" class="profile-section">
              <div class="profile-card">
                  <h2 id="profile-actions-title" class="profile-card-title">{{ $t('accountActions') }}</h2>
                  <button id="profile-actions-password" @click="changePassword" class="profile-action-button">{{ $t('changePassword') }}</button>
                  <button id="profile-actions-delete" @click="deleteAccount" class="profile-action-button profile-danger">{{ $t('deleteAccount') }}</button>
              </div>
          </section>
      </main>
  </div>
</template>

<script>
export default {
  name: 'Profile',
  props: {
      isDark: Boolean,
      currentLanguage: String,
      isSignedIn: Boolean,
  },
  data() {
      return {
          userName: 'Test User',
          userEmail: 'testuser@example.com',
          userAvatar: '/assets/avatar-placeholder.png',
          userDanceLevel: 'Intermediate',
          userJoinedDate: '2025-01-15',
          localLanguage: this.currentLanguage,
          emailNotifications: true,
          pushNotifications: false,
          privacyLevel: 'only-me',
          shareProgress: false,
          favoriteStyles: ['Hip-Hop', 'Salsa'],
          newStyle: '',
          stats: {
              coursesEnrolled: 5,
              lessonsCompleted: 20,
              hoursDanced: 50,
          },
      };
  },
  methods: {
      editProfile() {
          console.log('Edit profile clicked');
          // TODO: Модальное окно для редактирования профиля
      },
      addFavoriteStyle() {
          if (this.newStyle && !this.favoriteStyles.includes(this.newStyle)) {
              this.favoriteStyles.push(this.newStyle);
              this.newStyle = '';
          }
      },
      removeFavoriteStyle(style) {
          this.favoriteStyles = this.favoriteStyles.filter(s => s !== style);
      },
      saveSettings() {
          localStorage.setItem('emailNotifications', this.emailNotifications);
          localStorage.setItem('pushNotifications', this.pushNotifications);
          console.log('Settings saved');
      },
      savePrivacy() {
          localStorage.setItem('privacyLevel', this.privacyLevel);
          localStorage.setItem('shareProgress', this.shareProgress);
          console.log('Privacy settings saved');
      },
      changePassword() {
          console.log('Change password clicked');
          // TODO: Форма смены пароля
      },
      deleteAccount() {
          if (confirm(this.$t('confirmDeleteAccount'))) {
              localStorage.removeItem('isSignedIn');
              this.$emit('logout');
          }
      },
  },
  created() {
      if (!this.isSignedIn) this.$router.push('/login');
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
                  entry.target.classList.add('animate-pulse');
              }
          });
      }, { threshold: 0.1 });

      document.querySelectorAll('.animate-fade-in-up, .animate-slide-in-left, .animate-slide-in-up, .animate-slide-in-right, .animate-pulse').forEach(el => {
          observer.observe(el);
      });
  },
};
</script>