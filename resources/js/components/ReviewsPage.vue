<template>
    <div id="reviews" class="reviews-wrapper" :class="{ 'mud-dark-theme': isDark }">
        <main id="reviews-main" class="reviews-main">
            <!-- Заголовок и форма -->
            <section id="reviews-header" class="reviews-header">
                <div class="container">
                    <h1 id="reviews-title" class="reviews-title animate-fade-in-up">
                        {{ $t('reviews') }}
                    </h1>
                    <p id="reviews-subtitle" class="reviews-subtitle animate-fade-in-up">
                        {{ $t('reviewsSubtitle') }}
                    </p>
                    <div id="reviews-add-form" class="reviews-add-form animate-fade-in-up">
                        <h2 id="reviews-add-title" class="reviews-add-title">{{ $t('addReview') }}</h2>
                        <select id="reviews-add-course" v-model="newReview.course_id" class="reviews-add-course">
                            <option v-for="course in courses" :key="course.id" :value="course.id">
                                {{ course.title_ru }}
                            </option>
                        </select>
                        <select id="reviews-add-rating" v-model="newReview.rating" class="reviews-add-rating">
                            <option value="5">5 {{ $t('stars') }}</option>
                            <option value="4">4 {{ $t('stars') }}</option>
                            <option value="3">3 {{ $t('stars') }}</option>
                            <option value="2">2 {{ $t('stars') }}</option>
                            <option value="1">1 {{ $t('star') }}</option>
                        </select>
                        <textarea
                            id="reviews-add-text"
                            v-model="newReview.text"
                            :placeholder="$t('writeReview')"
                            class="reviews-add-text"
                        ></textarea>
                        <button id="reviews-add-button" @click="addReview" class="reviews-add-button">
                            {{ $t('submitReview') }}
                        </button>
                    </div>
                    <div id="reviews-filter" class="reviews-filter animate-fade-in-up">
                        <select id="reviews-filter-select" v-model="selectedRating" @change="filterReviews" class="reviews-filter-select">
                            <option value="all">{{ $t('allRatings') }}</option>
                            <option value="5">5 {{ $t('stars') }}</option>
                            <option value="4">4 {{ $t('stars') }}</option>
                            <option value="3">3 {{ $t('stars') }}</option>
                            <option value="2">2 {{ $t('stars') }}</option>
                            <option value="1">1 {{ $t('star') }}</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- Список отзывов -->
            <section id="reviews-list" class="reviews-list">
                <div class="container">
                    <div id="reviews-grid" class="reviews-grid">
                        <div
                            v-for="review in filteredReviews"
                            :key="review.id"
                            :id="'reviews-card-' + review.id"
                            class="reviews-card animate-fade-in-up"
                        >
                            <div class="reviews-card-header">
                                <img :id="'reviews-card-avatar-' + review.id" :src="review.avatar" :alt="review.author" class="reviews-card-avatar">
                                <div class="reviews-card-info">
                                    <h3 :id="'reviews-card-author-' + review.id" class="reviews-card-author">{{ review.author }}</h3>
                                    <div :id="'reviews-card-rating-' + review.id" class="reviews-card-rating">
                                        <i v-for="n in 5" :key="n" :class="n <= review.rating ? 'fas fa-star text-yellow-400' : 'far fa-star text-gray-400'"></i>
                                    </div>
                                    <p :id="'reviews-card-course-' + review.id" class="reviews-card-course">
                                        {{ $t('course') }}: {{ review.course_title }}
                                    </p>
                                    <p :id="'reviews-card-date-' + review.id" class="reviews-card-date">
                                        {{ review.created_at }}
                                    </p>
                                </div>
                            </div>
                            <p :id="'reviews-card-text-' + review.id" class="reviews-card-text">{{ review.text }}</p>
                            <div :id="'reviews-card-actions-' + review.id" class="reviews-card-actions">
                                <button
                                    :id="'reviews-card-like-' + review.id"
                                    class="reviews-card-like"
                                    @click="toggleLike(review.id)"
                                >
                                    <i :class="review.liked ? 'fas fa-thumbs-up text-noble-purple' : 'far fa-thumbs-up'"></i>
                                    <span>{{ review.likes }}</span>
                                </button>
                                <button
                                    :id="'reviews-card-dislike-' + review.id"
                                    class="reviews-card-dislike"
                                    @click="toggleDislike(review.id)"
                                >
                                    <i :class="review.disliked ? 'fas fa-thumbs-down text-red-500' : 'far fa-thumbs-down'"></i>
                                    <span>{{ review.dislikes }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="filteredReviews.length === 0" id="reviews-empty" class="reviews-empty">
                        <p id="reviews-empty-message" class="reviews-empty-message">{{ $t('noReviews') }}</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
export default {
    name: 'ReviewsPage',
    props: {
        isDark: Boolean,
    },
    data() {
        return {
            selectedRating: 'all',
            newReview: {
                course_id: null,
                rating: 5,
                text: '',
            },
            reviews: [],
            courses: [],
        };
    },
    computed: {
        filteredReviews() {
            if (this.selectedRating === 'all') return this.reviews;
            return this.reviews.filter(review => review.rating === parseInt(this.selectedRating));
        },
    },
    methods: {
        async fetchReviews() {
            try {
                const response = await window.axios.get('/api/reviews');
                this.reviews = response.data;
            } catch (error) {
                console.error('Failed to fetch reviews:', error);
            }
        },
        async fetchCourses() {
            try {
                const response = await window.axios.get('/api/courses');
                this.courses = response.data;
                if (this.courses.length > 0) {
                    this.newReview.course_id = this.courses[0].id; // Устанавливаем курс по умолчанию
                }
            } catch (error) {
                console.error('Failed to fetch courses:', error);
            }
        },
        async addReview() {
            if (!this.newReview.text.trim() || !this.newReview.course_id) return;
            try {
                const response = await window.axios.post('/api/reviews', {
                    course_id: this.newReview.course_id,
                    rating: parseInt(this.newReview.rating),
                    comment: this.newReview.text,
                });
                this.reviews.push(response.data.review);
                this.newReview.text = '';
                this.newReview.rating = 5;
            } catch (error) {
                console.error('Failed to add review:', error);
            }
        },
        toggleLike(reviewId) {
            const review = this.reviews.find(r => r.id === reviewId);
            if (review) {
                if (review.liked) {
                    review.liked = false;
                    review.likes -= 1;
                } else {
                    review.liked = true;
                    review.likes += 1;
                    if (review.disliked) {
                        review.disliked = false;
                        review.dislikes -= 1;
                    }
                }
            }
        },
        toggleDislike(reviewId) {
            const review = this.reviews.find(r => r.id === reviewId);
            if (review) {
                if (review.disliked) {
                    review.disliked = false;
                    review.dislikes -= 1;
                } else {
                    review.disliked = true;
                    review.dislikes += 1;
                    if (review.liked) {
                        review.liked = false;
                        review.likes -= 1;
                    }
                }
            }
        },
        filterReviews() {
            // Фильтрация выполняется через computed property
        },
    },
    mounted() {
        this.fetchCourses();
        this.fetchReviews();

        // Анимации при скролле
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.animate-fade-in-up').forEach(el => {
            observer.observe(el);
        });
    },
};
</script>