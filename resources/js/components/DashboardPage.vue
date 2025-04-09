<template>
    <div id="dashboard" class="dashboard-wrapper" :class="{ 'mud-dark-theme': isDark }">
        <main id="dashboard-main" class="dashboard-main">
            <!-- Приветствие -->
            <section id="dashboard-welcome" class="dashboard-welcome">
                <div class="container">
                    <h1 id="dashboard-welcome-title" class="dashboard-welcome-title animate-fade-in-up">
                        {{ $t('welcomeDashboard') }}
                    </h1>
                    <p id="dashboard-welcome-subtitle" class="dashboard-welcome-subtitle animate-fade-in-up">
                        {{ $t('dashboardSubtitle') }}
                    </p>
                </div>
            </section>

            <!-- Статистика -->
            <section id="dashboard-stats" class="dashboard-stats">
                <div class="container">
                    <h2 id="dashboard-stats-title" class="dashboard-stats-title animate-fade-in-up">
                        {{ $t('yourStats') }}
                    </h2>
                    <div id="dashboard-stats-grid" class="dashboard-stats-grid">
                        <div id="dashboard-stats-courses" class="dashboard-stats-card animate-slide-in-left">
                            <h3 id="dashboard-stats-courses-title" class="dashboard-stats-card-title">{{ $t('coursesEnrolled') }}</h3>
                            <p id="dashboard-stats-courses-value" class="dashboard-stats-card-value">{{ stats.coursesEnrolled }}</p>
                        </div>
                        <div id="dashboard-stats-progress" class="dashboard-stats-card animate-slide-in-up">
                            <h3 id="dashboard-stats-progress-title" class="dashboard-stats-card-title">{{ $t('progress') }}</h3>
                            <p id="dashboard-stats-progress-value" class="dashboard-stats-card-value">{{ stats.progress }}%</p>
                        </div>
                        <div id="dashboard-stats-lessons" class="dashboard-stats-card animate-slide-in-right">
                            <h3 id="dashboard-stats-lessons-title" class="dashboard-stats-card-title">{{ $t('lessonsCompleted') }}</h3>
                            <p id="dashboard-stats-lessons-value" class="dashboard-stats-card-value">{{ stats.lessonsCompleted }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Последние курсы -->
            <section id="dashboard-courses" class="dashboard-courses">
                <div class="container">
                    <h2 id="dashboard-courses-title" class="dashboard-courses-title animate-fade-in-up">
                        {{ $t('recentCourses') }}
                    </h2>
                    <div id="dashboard-courses-grid" class="dashboard-courses-grid">
                        <div
                            v-for="course in recentCourses"
                            :key="course.id"
                            id="dashboard-course-card"
                            class="dashboard-course-card animate-fade-in-up"
                        >
                            <img :id="'dashboard-course-image-' + course.id" :src="course.image" :alt="course.title" class="dashboard-course-image">
                            <h3 :id="'dashboard-course-title-' + course.id" class="dashboard-course-title">{{ course.title }}</h3>
                            <p :id="'dashboard-course-desc-' + course.id" class="dashboard-course-desc">{{ course.description }}</p>
                            <router-link :id="'dashboard-course-link-' + course.id" :to="{ name: 'courses', params: { id: course.id } }" class="dashboard-course-link">
                                {{ $t('viewCourse') }}
                            </router-link>
                        </div>
                    </div>
                    <div id="dashboard-courses-cta" class="dashboard-courses-cta">
                        <router-link id="dashboard-courses-cta-button" :to="{ name: 'courses' }" class="dashboard-courses-cta-button animate-pulse">
                            {{ $t('exploreAllCourses') }}
                        </router-link>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
export default {
    name: 'DashboardPage',
    props: {
        isDark: Boolean,
    },
    data() {
        return {
            stats: {
                coursesEnrolled: 5,
                progress: 75,
                lessonsCompleted: 20,
            },
            recentCourses: [
                { id: 1, title: 'Hip-Hop Basics', description: 'Learn the fundamentals of hip-hop dance.', image: '/assets/hip-hop.jpg' },
                { id: 2, title: 'Salsa for Beginners', description: 'Master the basics of salsa dancing.', image: '/assets/salsa.jpg' },
                { id: 3, title: 'Contemporary Dance', description: 'Explore the art of contemporary dance.', image: '/assets/contemporary.jpg' },
            ],
        };
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