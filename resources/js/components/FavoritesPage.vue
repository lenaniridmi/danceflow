<template>
    <div id="favorites" class="favorites-wrapper" :class="{ 'mud-dark-theme': isDark }">
        <main id="favorites-main" class="favorites-main">
            <!-- Заголовок -->
            <section id="favorites-header" class="favorites-header">
                <div class="container">
                    <h1 id="favorites-title" class="favorites-title animate-fade-in-up">
                        {{ $t('favorites') }}
                    </h1>
                    <p id="favorites-subtitle" class="favorites-subtitle animate-fade-in-up">
                        {{ $t('favoritesSubtitle') }}
                    </p>
                </div>
            </section>

            <!-- Список избранных курсов -->
            <section id="favorites-list" class="favorites-list">
                <div class="container">
                    <div id="favorites-grid" class="favorites-grid">
                        <div
                            v-for="course in favoriteCourses"
                            :key="course.id"
                            :id="'favorites-card-' + course.id"
                            class="favorites-card animate-fade-in-up"
                        >
                            <img :id="'favorites-card-image-' + course.id" :src="course.image" :alt="course.title" class="favorites-card-image">
                            <h3 :id="'favorites-card-title-' + course.id" class="favorites-card-title">{{ course.title }}</h3>
                            <p :id="'favorites-card-desc-' + course.id" class="favorites-card-desc">{{ course.description }}</p>
                            <div :id="'favorites-card-actions-' + course.id" class="favorites-card-actions">
                                <button
                                    :id="'favorites-card-remove-' + course.id"
                                    class="favorites-card-remove"
                                    @click="removeFromFavorites(course.id)"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <router-link
                                    :id="'favorites-card-link-' + course.id"
                                    :to="{ name: 'courses', params: { id: course.id } }"
                                    class="favorites-card-link"
                                >
                                    {{ $t('viewCourse') }}
                                </router-link>
                            </div>
                        </div>
                    </div>
                    <div v-if="favoriteCourses.length === 0" id="favorites-empty" class="favorites-empty">
                        <p id="favorites-empty-message" class="favorites-empty-message">{{ $t('noFavorites') }}</p>
                        <router-link id="favorites-empty-cta" :to="{ name: 'courses' }" class="favorites-empty-cta animate-pulse">
                            {{ $t('exploreCourses') }}
                        </router-link>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
export default {
    name: 'FavoritesPage',
    props: {
        isDark: Boolean,
    },
    data() {
        return {
            favoriteCourses: [
                { id: 2, title: 'Salsa for Beginners', description: 'Master the basics of salsa dancing.', image: '/assets/salsa.jpg' },
                { id: 5, title: 'Advanced Hip-Hop', description: 'Take your hip-hop skills to the next level.', image: '/assets/hip-hop-advanced.jpg' },
            ],
        };
    },
    methods: {
        removeFromFavorites(courseId) {
            this.favoriteCourses = this.favoriteCourses.filter(course => course.id !== courseId);
        },
    },
    mounted() {
        // Анимации при скролле
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                    entry.target.classList.add('animate-pulse');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.animate-fade-in-up, .animate-pulse').forEach(el => {
            observer.observe(el);
        });
    },
};
</script>