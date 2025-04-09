<template>
    <div id="courses" class="courses-wrapper" :class="{ 'mud-dark-theme': isDark }">
        <main id="courses-main" class="courses-main">
            <!-- Заголовок и фильтр -->
            <section id="courses-header" class="courses-header">
                <div class="container">
                    <h1 id="courses-title" class="courses-title animate-fade-in-up">
                        {{ $t('courses') }}
                    </h1>
                    <p id="courses-subtitle" class="courses-subtitle animate-fade-in-up">
                        {{ $t('coursesSubtitle') }}
                    </p>
                    <div id="courses-filter" class="courses-filter animate-fade-in-up">
                        <select id="courses-filter-select" v-model="selectedCategory" @change="filterCourses" class="courses-filter-select">
                            <option value="all">{{ $t('allCategories') }}</option>
                            <option value="hip-hop">{{ $t('hipHop') }}</option>
                            <option value="salsa">{{ $t('salsa') }}</option>
                            <option value="contemporary">{{ $t('contemporary') }}</option>
                            <option value="ballroom">{{ $t('ballroom') }}</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- Список курсов -->
            <section id="courses-list" class="courses-list">
                <div class="container">
                    <div id="courses-grid" class="courses-grid">
                        <div
                            v-for="course in filteredCourses"
                            :key="course.id"
                            id="courses-card"
                            class="courses-card animate-fade-in-up"
                        >
                            <img :id="'courses-card-image-' + course.id" :src="course.image" :alt="course.title" class="courses-card-image">
                            <h3 :id="'courses-card-title-' + course.id" class="courses-card-title">{{ course.title }}</h3>
                            <p :id="'courses-card-desc-' + course.id" class="courses-card-desc">{{ course.description }}</p>
                            <div :id="'courses-card-actions-' + course.id" class="courses-card-actions">
                                <button
                                    :id="'courses-card-favorite-' + course.id"
                                    class="courses-card-favorite"
                                    @click="toggleFavorite(course.id)"
                                >
                                    <i :class="course.isFavorite ? 'fas fa-heart' : 'far fa-heart'"></i>
                                </button>
                                <router-link
                                    :id="'courses-card-link-' + course.id"
                                    :to="{ name: 'courses', params: { id: course.id } }"
                                    class="courses-card-link"
                                >
                                    {{ $t('viewCourse') }}
                                </router-link>
                            </div>
                        </div>
                    </div>
                    <div v-if="filteredCourses.length === 0" id="courses-empty" class="courses-empty">
                        <p id="courses-empty-message" class="courses-empty-message">{{ $t('noCoursesFound') }}</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
export default {
    name: 'CoursesPage',
    props: {
        isDark: Boolean,
    },
    data() {
        return {
            selectedCategory: 'all',
            courses: [
                { id: 1, title: 'Hip-Hop Basics', description: 'Learn the fundamentals of hip-hop dance.', image: '/assets/hip-hop.jpg', category: 'hip-hop', isFavorite: false },
                { id: 2, title: 'Salsa for Beginners', description: 'Master the basics of salsa dancing.', image: '/assets/salsa.jpg', category: 'salsa', isFavorite: true },
                { id: 3, title: 'Contemporary Dance', description: 'Explore the art of contemporary dance.', image: '/assets/contemporary.jpg', category: 'contemporary', isFavorite: false },
                { id: 4, title: 'Ballroom Essentials', description: 'Learn the elegance of ballroom dancing.', image: '/assets/ballroom.jpg', category: 'ballroom', isFavorite: false },
                { id: 5, title: 'Advanced Hip-Hop', description: 'Take your hip-hop skills to the next level.', image: '/assets/hip-hop-advanced.jpg', category: 'hip-hop', isFavorite: false },
            ],
        };
    },
    computed: {
        filteredCourses() {
            if (this.selectedCategory === 'all') {
                return this.courses;
            }
            return this.courses.filter(course => course.category === this.selectedCategory);
        },
    },
    methods: {
        filterCourses() {
            // Фильтрация уже выполняется через computed property
        },
        toggleFavorite(courseId) {
            const course = this.courses.find(c => c.id === courseId);
            if (course) {
                course.isFavorite = !course.isFavorite;
            }
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