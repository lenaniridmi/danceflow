<template>
    <div id="video-player" class="video-player">
        <video
            id="video-player-element"
            ref="video"
            class="video-player-element"
            :src="videoSource"
            @timeupdate="updateTimestamp"
            controls
        >
            <source :src="videoSource" type="video/mp4">
            {{ $t('videoNotSupported') }}
        </video>
        <div id="video-player-controls" class="video-player-controls">
            <select id="video-player-speed-select" class="video-player-speed-select" @change="setPlaybackRate">
                <option value="0.5">0.5x</option>
                <option value="1" selected>1x</option>
                <option value="1.5">1.5x</option>
                <option value="2">2x</option>
            </select>
            <button id="video-player-loop-button" class="video-player-button" @click="toggleLoop">
                {{ isLooping ? $t('stopLoop') : $t('loopSegment') }}
            </button>
            <button v-if="showNotes" id="video-player-add-note" class="video-player-button" @click="addNote">
                {{ $t('addNote') }}
            </button>
        </div>
        <div v-if="showNotes" id="video-player-notes" class="video-player-notes">
            <h2 id="video-player-notes-title" class="video-player-notes-title">{{ $t('notes') }}</h2>
            <ul id="video-player-notes-list" class="video-player-notes-list">
                <li
                    v-for="note in notes"
                    :key="note.id"
                    id="video-player-note-item"
                    class="video-player-note-item"
                >
                    <span
                        id="video-player-note-timestamp"
                        class="video-player-note-timestamp"
                        @click="seekTo(note.timestamp)"
                    >
                        {{ formatTime(note.timestamp) }}
                    </span>: {{ note.text }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        lesson: {
            type: Object,
            default: null,
        },
        videoUrl: {
            type: String,
            default: null,
        },
        showNotes: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            currentTime: 0,
            notes: [],
            isLooping: false,
            loopStart: null,
            loopEnd: null,
        };
    },
    computed: {
        videoSource() {
            return this.lesson ? this.lesson.video_url : this.videoUrl;
        },
    },
    methods: {
        updateTimestamp(event) {
            this.currentTime = event.target.currentTime;
            if (this.isLooping && this.loopStart && this.loopEnd) {
                if (this.currentTime >= this.loopEnd) {
                    this.$refs.video.currentTime = this.loopStart;
                }
            }
        },
        addNote() {
            const text = prompt(this.$t('addNote'));
            if (text) {
                this.notes.push({
                    id: this.notes.length + 1,
                    timestamp: Math.floor(this.currentTime),
                    text: text,
                });
            }
        },
        setPlaybackRate(event) {
            this.$refs.video.playbackRate = parseFloat(event.target.value);
        },
        toggleLoop() {
            if (!this.isLooping) {
                this.loopStart = this.currentTime;
                this.loopEnd = this.currentTime + 10;
                this.isLooping = true;
            } else {
                this.isLooping = false;
                this.loopStart = null;
                this.loopEnd = null;
            }
        },
        seekTo(timestamp) {
            this.$refs.video.currentTime = timestamp;
        },
        formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
        },
    },
};
</script>