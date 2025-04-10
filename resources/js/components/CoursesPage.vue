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
                        <select id="courses-filter-select" v-model="selectedCategory" @change="fetchCourses(1)" class="courses-filter-select">
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
                    <div v-if="loading" class="text-center py-8">
                        <p>Загрузка курсов...</p>
                    </div>
                    <div v-else-if="error" class="text-center py-8 text-red-500">
                        <p>Ошибка загрузки курсов: {{ error }}</p>
                    </div>
                    <div v-else id="courses-grid" class="courses-grid">
                        <div
                            v-for="course in courses"
                            :key="course.id"
                            :id="'courses-card-' + course.id"
                            class="courses-card animate-fade-in-up"
                            v-if="course && course.id"
                        >
                            <img :id="'courses-card-image-' + course.id" :src="course.image || '/assets/course-placeholder.jpg'" :alt="course.title || 'Без названия'" class="courses-card-image">
                            <h3 :id="'courses-card-title-' + course.id" class="courses-card-title">{{ course.title || 'Без названия' }}</h3>
                            <p :id="'courses-card-desc-' + course.id" class="courses-card-desc">{{ course.description || 'Без описания' }}</p>
                            <p :id="'courses-card-level-' + course.id" class="courses-card-level">{{ $t('level') }}: {{ course.level || 'Не указан' }}</p>
                            <p :id="'courses-card-teacher-' + course.id" class="courses-card-teacher">{{ $t('teacher') }}: {{ course.teacher || 'Не указан' }}</p>
                            <div :id="'courses-card-actions-' + course.id" class="courses-card-actions">
                                <button
                                    :id="'courses-card-favorite-' + course.id"
                                    class="courses-card-favorite"
                                    @click="toggleFavorite(course.id)"
                                >
                                    <i :class="course.isFavorite ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-400'"></i>
                                </button>
                                <router-link
                                    :id="'courses-card-link-' + course.id"
                                    :to="{ name: 'course', params: { id: course.id } }"
                                    class="courses-card-link"
                                >
                                    {{ $t('viewCourse') }}
                                </router-link>
                            </div>
                        </div>
                    </div>
                    <div v-if="!loading && courses.length === 0 && !error" id="courses-empty" class="courses-empty">
                        <p id="courses-empty-message" class="courses-empty-message">{{ $t('noCoursesFound') }}</p>
                    </div>
                    <!-- Элементы пагинации -->
                    <div v-if="totalCourses > 0" class="pagination mt-8 flex justify-center space-x-2">
                        <button
                            @click="fetchCourses(currentPage - 1)"
                            :disabled="currentPage === 1 || loading"
                            class="px-4 py-2 bg-gray-300 rounded-lg"
                            :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 || loading }"
                        >
                            Предыдущая
                        </button>
                        <span class="px-4 py-2">Страница {{ currentPage }} из {{ lastPage }}</span>
                        <button
                            @click="fetchCourses(currentPage + 1)"
                            :disabled="currentPage === lastPage || loading"
                            class="px-4 py-2 bg-gray-300 rounded-lg"
                            :class="{ 'opacity-50 cursor-not-allowed': currentPage === lastPage || loading }"
                        >
                            Следующая
                        </button>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'CoursesPage',
    props: {
        isDark: Boolean,
    },
    data() {
        return {
            selectedCategory: 'all',
            courses: [],
            currentPage: 1,
            lastPage: 1,
            totalCourses: 0,
            loading: false,
            error: null,
        };
    },
    methods: {
        async fetchCourses(page = 1) {
            this.loading = true;
            this.error = null;
            try {
                const params = {
                    page: page,
                    category: this.selectedCategory,
                };
                const response = await axios.get('/api/courses', { params }); 
                console.log('Courses fetched:', response.data);
                if (response.data && response.data.data) {
                    this.courses = response.data.data;
                    this.currentPage = response.data.current_page || 1;
                    this.lastPage = response.data.last_page || 1;
                    this.totalCourses = response.data.total || 0;
                } else {
                    throw new Error('Некорректный формат ответа API');
                }
            } catch (error) {
                console.error('Failed to fetch courses:', error);
                this.error = error.message || 'Неизвестная ошибка';
                this.courses = [];
                this.currentPage = 1;
                this.lastPage = 1;
                this.totalCourses = 0;
            } finally {
                this.loading = false;
            }
        },
        async toggleFavorite(courseId) {
            const course = this.courses.find(c => c.id === courseId);
            if (!course) return;

            try {
                if (course.isFavorite) {
                    await axios.delete(`http://127.0.0.1:8000/api/favorites/${courseId}`); // Явно указываем URL
                    course.isFavorite = false;
                } else {
                    await axios.post('http://127.0.0.1:8000/api/favorites', { course_id: courseId }); // Явно указываем URL
                    course.isFavorite = true;
                }
            } catch (error) {
                console.error('Failed to toggle favorite:', error);
            }
        },
    },
    mounted() {
        this.fetchCourses();
    },
};
</script>
