<template>
    <div id="messages" class="messages-wrapper" :class="{ 'mud-dark-theme': isDark }">
        <main id="messages-main" class="messages-main">
            <!-- Заголовок -->
            <section id="messages-header" class="messages-header">
                <div class="container">
                    <h1 id="messages-title" class="messages-title animate-fade-in-up">
                        {{ $t('messages') }}
                    </h1>
                    <p id="messages-subtitle" class="messages-subtitle animate-fade-in-up">
                        {{ $t('messagesSubtitle') }}
                    </p>
                    <div id="messages-search" class="messages-search animate-fade-in-up">
                        <input
                            id="messages-search-input"
                            v-model="searchQuery"
                            type="text"
                            :placeholder="$t('searchChats')"
                            class="messages-search-input"
                            @input="filterChats"
                        />
                        <button id="messages-search-button" class="messages-search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Основной контент -->
            <section id="messages-content" class="messages-content">
                <div class="container flex flex-col lg:flex-row gap-6">
                    <!-- Список чатов -->
                    <aside id="messages-chats" class="messages-chats">
                        <div id="messages-chats-list" class="messages-chats-list">
                            <div
                                v-for="chat in filteredChats"
                                :key="chat.id"
                                :id="'messages-chat-' + chat.id"
                                class="messages-chat animate-fade-in-up"
                                :class="{ 'messages-chat-active': chat.id === selectedChat?.id }"
                                @click="selectChat(chat)"
                            >
                                <img :id="'messages-chat-avatar-' + chat.id" :src="chat.avatar" :alt="chat.name" class="messages-chat-avatar">
                                <div class="messages-chat-info">
                                    <h3 :id="'messages-chat-name-' + chat.id" class="messages-chat-name">{{ chat.name }}</h3>
                                    <p :id="'messages-chat-last-message-' + chat.id" class="messages-chat-last-message">{{ chat.lastMessage }}</p>
                                </div>
                                <span v-if="chat.unreadCount > 0" :id="'messages-chat-unread-' + chat.id" class="messages-chat-unread">{{ chat.unreadCount }}</span>
                            </div>
                        </div>
                        <div v-if="filteredChats.length === 0" id="messages-chats-empty" class="messages-chats-empty">
                            <p id="messages-chats-empty-message" class="messages-chats-empty-message">{{ $t('noChatsFound') }}</p>
                        </div>
                    </aside>

                    <!-- Окно чата -->
                    <div id="messages-chat-window" class="messages-chat-window">
                        <div v-if="selectedChat" id="messages-chat-header" class="messages-chat-header">
                            <img :id="'messages-chat-header-avatar-' + selectedChat.id" :src="selectedChat.avatar" :alt="selectedChat.name" class="messages-chat-header-avatar">
                            <h3 :id="'messages-chat-header-name-' + selectedChat.id" class="messages-chat-header-name">{{ selectedChat.name }}</h3>
                        </div>
                        <div v-if="selectedChat" id="messages-chat-messages" class="messages-chat-messages" ref="chatMessages">
                            <div
                                v-for="message in selectedChat.messages"
                                :key="message.id"
                                :id="'messages-message-' + message.id"
                                class="messages-message"
                                :class="{ 'messages-message-sent': message.sender === 'user', 'messages-message-received': message.sender !== 'user' }"
                            >
                                <div class="messages-message-content">
                                    <p :id="'messages-message-text-' + message.id" class="messages-message-text">{{ message.text }}</p>
                                    <span v-if="message.file" :id="'messages-message-file-' + message.id" class="messages-message-file">
                                        <a :href="message.file" target="_blank">{{ $t('viewFile') }}</a>
                                    </span>
                                    <span :id="'messages-message-time-' + message.id" class="messages-message-time">{{ message.time }}</span>
                                </div>
                            </div>
                        </div>
                        <div v-else id="messages-chat-empty" class="messages-chat-empty">
                            <p id="messages-chat-empty-message" class="messages-chat-empty-message">{{ $t('selectChat') }}</p>
                        </div>
                        <div v-if="selectedChat" id="messages-chat-input" class="messages-chat-input">
                            <button id="messages-emoji-button" class="messages-emoji-button" @click="toggleEmojiPicker">
                                <i class="fas fa-smile"></i>
                            </button>
                            <div v-if="showEmojiPicker" id="messages-emoji-picker" class="messages-emoji-picker">
                                <span v-for="emoji in emojis" :key="emoji" @click="addEmoji(emoji)" class="messages-emoji">{{ emoji }}</span>
                            </div>
                            <button id="messages-file-button" class="messages-file-button">
                                <label for="file-upload">
                                    <i class="fas fa-paperclip"></i>
                                </label>
                                <input id="file-upload" type="file" @change="handleFileUpload" class="hidden" />
                            </button>
                            <input
                                id="messages-input"
                                v-model="newMessage"
                                type="text"
                                :placeholder="$t('typeMessage')"
                                class="messages-input"
                                @keyup.enter="sendMessage"
                            />
                            <button id="messages-send-button" class="messages-send-button" @click="sendMessage">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
export default {
    name: 'MessagesPage',
    props: {
        isDark: Boolean,
    },
    data() {
        return {
            searchQuery: '',
            selectedChat: null,
            newMessage: '',
            showEmojiPicker: false,
            emojis: ['😀', '😂', '🥳', '💃', '🕺', '❤️', '🔥', '🎉'],
            chats: [
                { id: 1, name: 'Anna', avatar: '/assets/avatar-anna.jpg', lastMessage: 'Hey, how’s your dance practice going?', unreadCount: 2, messages: [
                    { id: 1, sender: 'user', text: 'Hey Anna! I’m practicing for the hip-hop class.', time: '10:30' },
                    { id: 2, sender: 'Anna', text: 'Nice! Let’s meet up later to practice together.', time: '10:32' },
                ]},
                { id: 2, name: 'Max', avatar: '/assets/avatar-max.jpg', lastMessage: 'Check out this new salsa move!', unreadCount: 0, messages: [
                    { id: 1, sender: 'Max', text: 'I found an awesome salsa move. Want to try it?', time: '09:15' },
                    { id: 2, sender: 'user', text: 'Looks great! Let’s try it tomorrow.', time: '09:20' },
                ]},
                { id: 3, name: 'Dance Group', avatar: '/assets/group-avatar.jpg', lastMessage: 'Group practice this Saturday!', unreadCount: 5, messages: [
                    { id: 1, sender: 'user', text: 'Hey everyone, who’s coming to practice on Saturday?', time: '08:00' },
                    { id: 2, sender: 'Katya', text: 'I’ll be there!', time: '08:05' },
                    { id: 3, sender: 'Alex', text: 'Me too! Let’s do some contemporary moves.', time: '08:10' },
                ]},
            ],
        };
    },
    computed: {
        filteredChats() {
            if (!this.searchQuery) return this.chats;
            return this.chats.filter(chat =>
                chat.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                chat.lastMessage.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },
    },
    methods: {
        selectChat(chat) {
            this.selectedChat = chat;
            chat.unreadCount = 0;
            this.$nextTick(() => {
                const chatMessages = this.$refs.chatMessages;
                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            });
        },
        toggleEmojiPicker() {
            this.showEmojiPicker = !this.showEmojiPicker;
        },
        addEmoji(emoji) {
            this.newMessage += emoji;
            this.showEmojiPicker = false;
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    this.sendMessage(reader.result);
                };
                reader.readAsDataURL(file);
            }
        },
        sendMessage(file = null) {
            if (!this.newMessage.trim() && !file) return;
            const newMessage = {
                id: this.selectedChat.messages.length + 1,
                sender: 'user',
                text: this.newMessage,
                file: file,
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            };
            this.selectedChat.messages.push(newMessage);
            this.newMessage = '';
            this.$nextTick(() => {
                const chatMessages = this.$refs.chatMessages;
                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            });
            // Симуляция ответа
            setTimeout(() => {
                const reply = {
                    id: this.selectedChat.messages.length + 1,
                    sender: this.selectedChat.name,
                    text: 'Looks great! Let’s keep practicing!',
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                };
                this.selectedChat.messages.push(reply);
                this.selectedChat.lastMessage = reply.text;
                if (this.selectedChat !== this.chats.find(chat => chat.id === this.selectedChat.id)) {
                    this.selectedChat.unreadCount += 1;
                }
                this.$nextTick(() => {
                    const chatMessages = this.$refs.chatMessages;
                    if (chatMessages) {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }
                });
            }, 1000);
        },
        filterChats() {
            // Фильтрация выполняется через computed property
        },
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