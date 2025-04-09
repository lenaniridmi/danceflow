<template>
    <div id="progress" class="progress-wrapper" :class="{ 'mud-dark-theme': isDark }">
        <main id="progress-main" class="progress-main">
            <!-- Заголовок -->
            <section id="progress-header" class="progress-header">
                <div class="container">
                    <h1 id="progress-title" class="progress-title animate-fade-in-up">
                        {{ $t('progress') }}
                    </h1>
                    <p id="progress-subtitle" class="progress-subtitle animate-fade-in-up">
                        {{ $t('progressSubtitle') }}
                    </p>
                </div>
            </section>

            <!-- Общий прогресс -->
            <section id="progress-overview" class="progress-overview">
                <div class="container">
                    <h2 id="progress-overview-title" class="progress-overview-title animate-fade-in-up">
                        {{ $t('overallProgress') }}
                    </h2>
                    <div id="progress-bar-container" class="progress-bar-container animate-fade-in-up">
                        <div id="progress-bar" class="progress-bar" :style="{ width: overallProgress + '%' }">
                            <span id="progress-bar-label" class="progress-bar-label">{{ overallProgress }}%</span>
                        </div>
                    </div>
                    <div id="progress-stats" class="progress-stats">
                        <div id="progress-stats-courses" class="progress-stats-item animate-slide-in-left">
                            <h3 id="progress-stats-courses-title" class="progress-stats-title">{{ $t('coursesCompleted') }}</h3>
                            <p id="progress-stats-courses-value" class="progress-stats-value">{{ stats.coursesCompleted }}</p>
                        </div>
                        <div id="progress-stats-lessons" class="progress-stats-item animate-slide-in-right">
                            <h3 id="progress-stats-lessons-title" class="progress-stats-title">{{ $t('lessonsCompleted') }}</h3>
                            <p id="progress-stats-lessons-value" class="progress-stats-value">{{ stats.lessonsCompleted }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Завершенные уроки и курсы -->
            <section id="progress-completed" class="progress-completed">
                <div class="container">
                    <h2 id="progress-completed-title" class="progress-completed-title animate-fade-in-up">
                        {{ $t('completedItems') }}
                    </h2>
                    <div id="progress-completed-grid" class="progress-completed-grid">
                        <div
                            v-for="item in completedItems"
                            :key="item.id"
                            id="progress-item-card"
                            class="progress-item-card animate-fade-in-up"
                        >
                            <img :id="'progress-item-image-' + item.id" :src="item.image" :alt="item.title" class="progress-item-image">
                            <div id="progress-item-details" class="progress-item-details">
                                <h3 :id="'progress-item-title-' + item.id" class="progress-item-title">{{ item.title }}</h3>
                                <p :id="'progress-item-type-' + item.id" class="progress-item-type">{{ item.type === 'course' ? $t('course') : $t('lesson') }}</p>
                                <p :id="'progress-item-date-' + item.id" class="progress-item-date">{{ $t('completedOn') }} {{ item.completedDate }}</p>
                            </div>
                        </div>
                    </div>
                    <div v-if="completedItems.length === 0" id="progress-empty" class="progress-empty">
                        <p id="progress-empty-message" class="progress-empty-message">{{ $t('noCompletedItems') }}</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
export default {
    name: 'ProgressPage',
    props: {
        isDark: Boolean,
    },
    data() {
        return {
            overallProgress: 75,
            stats: {
                coursesCompleted: 3,
                lessonsCompleted: 15,
            },
            completedItems: [
                { id: 1, title: 'Hip-Hop Basics', type: 'course', image: '/assets/hip-hop.jpg', completedDate: '2025-04-01' },
                { id: 2, title: 'Salsa Lesson 1', type: 'lesson', image: '/assets/salsa.jpg', completedDate: '2025-03-28' },
                { id: 3, title: 'Contemporary Dance', type: 'course', image: '/assets/contemporary.jpg', completedDate: '2025-03-15' },
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
                    entry.target.classList.add('animate-slide-in-right');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.animate-fade-in-up, .animate-slide-in-left, .animate-slide-in-right').forEach(el => {
            observer.observe(el);
        });
    },
};
</script>