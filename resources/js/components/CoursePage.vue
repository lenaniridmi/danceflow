<template>
    <div id="course" class="course-wrapper" :class="{ 'mud-dark-theme': isDark }">
        <main id="course-main" class="course-main">
            <section id="course-header" class="course-header">
                <div class="container">
                    <h1 id="course-title" class="course-title animate-fade-in-up">
                        {{ course ? course.title : 'Курс не найден' }}
                    </h1>
                    <p id="course-description" class="course-description animate-fade-in-up">
                        {{ course ? course.description : '' }}
                    </p>
                    <p id="course-level" class="course-level animate-fade-in-up">
                        {{ $t('level') }}: {{ course ? course.level : '' }}
                    </p>
                    <p id="course-teacher" class="course-teacher animate-fade-in-up">
                        {{ $t('teacher') }}: {{ course ? course.teacher : '' }}
                    </p>
                </div>
            </section>

            <section id="course-content" class="course-content">
                <div class="container">
                    <img :src="course ? course.image : '/assets/course-placeholder.jpg'" alt="Course Image" class="course-image">
                    <!-- Здесь можно добавить дополнительные данные о курсе, например, список уроков -->
                </div>
            </section>
        </main>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'CoursePage',
    props: {
        isDark: Boolean,
    },
    data() {
        return {
            course: null,
        };
    },
    methods: {
        async fetchCourse() {
            try {
                const courseId = this.$route.params.id;
                const response = await axios.get(`/api/courses/${courseId}`);
                this.course = response.data;
            } catch (error) {
                console.error('Failed to fetch course:', error);
            }
        },
    },
    mounted() {
        this.fetchCourse();
    },
};
</script>

<style scoped>
.course-wrapper {
    @apply flex flex-col min-h-screen;
}

.course-main {
    @apply flex-1;
}

.course-header {
    @apply py-16 bg-gradient-to-b from-noble-purple/10 to-background-light;
}

.mud-dark-theme .course-header {
    @apply bg-gradient-to-b from-noble-purple/20 to-background-dark;
}

.course-title {
    @apply text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-800 text-center;
}

.mud-dark-theme .course-title {
    @apply text-white;
}

.course-description {
    @apply text-xl sm:text-2xl text-gray-600 mt-4 text-center;
}

.mud-dark-theme .course-description {
    @apply text-gray-300;
}

.course-level,
.course-teacher {
    @apply text-lg text-gray-600 mt-2 text-center;
}

.mud-dark-theme .course-level,
.mud-dark-theme .course-teacher {
    @apply text-gray-300;
}

.course-content {
    @apply py-16;
    background: linear-gradient(to bottom, #fff, #f9f9f9);
}

.mud-dark-theme .course-content {
    background: linear-gradient(to bottom, #222, #2c2c38);
}

.course-image {
    @apply w-full max-w-2xl mx-auto rounded-lg shadow-md;
}
</style>