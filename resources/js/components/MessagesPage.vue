<template>
    <div id="messages" class="messages-wrapper" :class="{ 'mud-dark-theme': isDark }">
        <main id="messages-main" class="messages-main">
            <!-- Уведомление о новом сообщении -->
            <transition name="fade">
                <div v-if="newMessageNotification" id="messages-notification" class="messages-notification">
                    <p>{{ $t('newMessageFrom') }} {{ newMessageNotification.name }}</p>
                    <button @click="selectChat(newMessageNotification)" class="messages-notification-button">
                        {{ $t('view') }}
                    </button>
                </div>
            </transition>

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
                                <div class="relative">
                                    <img :id="'messages-chat-avatar-' + chat.id" :src="chat.avatar" :alt="chat.name" class="messages-chat-avatar">
                                    <span v-if="chat.isOnline" class="messages-chat-online"></span>
                                </div>
                                <div class="messages-chat-info">
                                    <h3 :id="'messages-chat-name-' + chat.id" class="messages-chat-name">
                                        {{ chat.name }}
                                        <i v-if="chat.isGroup" class="fas fa-users ml-1 text-gray-500"></i>
                                    </h3>
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
                            <div class="relative">
                                <img :id="'messages-chat-header-avatar-' + selectedChat.id" :src="selectedChat.avatar" :alt="selectedChat.name" class="messages-chat-header-avatar">
                                <span v-if="selectedChat.isOnline" class="messages-chat-header-online"></span>
                            </div>
                            <h3 :id="'messages-chat-header-name-' + selectedChat.id" class="messages-chat-header-name">{{ selectedChat.name }}</h3>
                        </div>
                        <div v-if="selectedChat" id="messages-chat-messages" class="messages-chat-messages" ref="chatMessages" @scroll="handleScroll">
                            <div
                                v-for="(message, index) in selectedChat.messages"
                                :key="message.id"
                                :id="'messages-message-' + message.id"
                                class="messages-message"
                                :class="{
                                    'messages-message-sent': message.sender === 'user',
                                    'messages-message-received': message.sender !== 'user',
                                    'messages-message-new': index >= selectedChat.newMessageIndex
                                }"
                            >
                                <div class="messages-message-container">
                                    <img v-if="message.sender !== 'user'" :src="message.avatar" :alt="message.sender" class="messages-message-avatar">
                                    <div class="messages-message-content" @contextmenu.prevent="showContextMenu($event, message)">
                                        <p v-if="selectedChat.isGroup && message.sender !== 'user'" class="messages-message-sender">{{ message.sender }}</p>
                                        <p :id="'messages-message-text-' + message.id" class="messages-message-text" v-html="formatMessage(message.text)"></p>
                                        <span v-if="message.file" :id="'messages-message-file-' + message.id" class="messages-message-file">
                                            <a :href="message.file" target="_blank">{{ $t('viewFile') }}</a>
                                        </span>
                                        <span :id="'messages-message-time-' + message.id" class="messages-message-time">
                                            {{ message.time }}
                                            <i v-if="message.sender === 'user' && message.read" class="fas fa-check-double text-blue-500 ml-1"></i>
                                        </span>
                                        <div v-if="message.reactions && message.reactions.length" class="messages-message-reactions">
                                            <span v-for="reaction in message.reactions" :key="reaction" class="messages-message-reaction">{{ reaction }}</span>
                                        </div>
                                    </div>
                                    <img v-if="message.sender === 'user'" :src="currentUser.avatar" :alt="currentUser.name" class="messages-message-avatar">
                                </div>
                                <!-- Контекстное меню -->
                                <div v-if="message.showContextMenu" class="messages-context-menu" :style="{ top: message.contextMenuY + 'px', left: message.contextMenuX + 'px' }">
                                    <button v-if="message.sender === 'user'" @click="editMessage(message)">{{ $t('edit') }}</button>
                                    <button @click="deleteMessage(message)">{{ $t('delete') }}</button>
                                    <button @click="reactToMessage(message, '❤️')">{{ $t('reactHeart') }}</button>
                                    <button @click="reactToMessage(message, '👍')">{{ $t('reactLike') }}</button>
                                </div>
                            </div>
                            <!-- Индикатор "пользователь печатает" -->
                            <transition name="fade">
                                <div v-if="isTyping" id="messages-typing" class="messages-typing">
                                    <span>{{ selectedChat.name }} {{ $t('isTyping') }}...</span>
                                </div>
                            </transition>
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
                                ref="messageInput"
                                v-model="newMessage"
                                type="text"
                                :placeholder="$t('typeMessage')"
                                class="messages-input"
                                @keyup.enter="sendMessage"
                                @keyup.ctrl.enter="sendMessage"
                                @input="handleTyping"
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
            isTyping: false,
            typingTimeout: null,
            newMessageNotification: null,
            editingMessage: null,
            currentUser: {
                name: 'You',
                avatar: '/assets/avatar-placeholder.png',
            },
            emojis: ['😀', '😂', '🥳', '💃', '🕺', '❤️', '🔥', '🎉'],
            chats: [
                {
                    id: 1,
                    name: 'Anna',
                    avatar: '/assets/avatar-anna.jpg',
                    lastMessage: 'Hey, how’s your dance practice going?',
                    unreadCount: 2,
                    isGroup: false,
                    isOnline: true,
                    messages: [
                        { id: 1, sender: 'user', text: 'Hey Anna! I’m practicing for the hip-hop class.', time: '10:30', read: true, avatar: '/assets/avatar-placeholder.png' },
                        { id: 2, sender: 'Anna', text: 'Nice! Let’s meet up later to practice together.', time: '10:32', read: true, avatar: '/assets/avatar-anna.jpg' },
                    ],
                    newMessageIndex: 0,
                },
                {
                    id: 2,
                    name: 'Max',
                    avatar: '/assets/avatar-max.jpg',
                    lastMessage: 'Check out this new salsa move!',
                    unreadCount: 0,
                    isGroup: false,
                    isOnline: false,
                    messages: [
                        { id: 1, sender: 'Max', text: 'I found an awesome salsa move. Want to try it?', time: '09:15', read: true, avatar: '/assets/avatar-max.jpg' },
                        { id: 2, sender: 'user', text: 'Looks great! Let’s try it tomorrow.', time: '09:20', read: true, avatar: '/assets/avatar-placeholder.png' },
                    ],
                    newMessageIndex: 0,
                },
                {
                    id: 3,
                    name: 'Dance Group',
                    avatar: '/assets/group-avatar.jpg',
                    lastMessage: 'Group practice this Saturday!',
                    unreadCount: 5,
                    isGroup: true,
                    isOnline: true,
                    messages: [
                        { id: 1, sender: 'user', text: 'Hey everyone, who’s coming to practice on Saturday?', time: '08:00', read: true, avatar: '/assets/avatar-placeholder.png' },
                        { id: 2, sender: 'Katya', text: 'I’ll be there!', time: '08:05', read: true, avatar: '/assets/avatar-katya.jpg' },
                        { id: 3, sender: 'Alex', text: 'Me too! Let’s do some contemporary moves.', time: '08:10', read: true, avatar: '/assets/avatar-alex.jpg' },
                    ],
                    newMessageIndex: 0,
                },
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
            chat.newMessageIndex = chat.messages.length;
            this.$nextTick(() => {
                const chatMessages = this.$refs.chatMessages;
                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
                this.$refs.messageInput.focus();
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
        handleTyping() {
            if (!this.isTyping) {
                this.isTyping = true;
                setTimeout(() => {
                    this.isTyping = false;
                }, 2000);
            }
            if (this.typingTimeout) clearTimeout(this.typingTimeout);
            this.typingTimeout = setTimeout(() => {
                this.isTyping = false;
            }, 2000);
        },
        sendMessage(file = null) {
            if (!this.newMessage.trim() && !file) return;
            if (this.editingMessage) {
                this.editingMessage.text = this.newMessage;
                this.editingMessage = null;
            } else {
                const newMessage = {
                    id: this.selectedChat.messages.length + 1,
                    sender: 'user',
                    text: this.newMessage,
                    file: file,
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                    read: false,
                    showContextMenu: false,
                    contextMenuX: 0,
                    contextMenuY: 0,
                    reactions: [],
                    avatar: this.currentUser.avatar,
                };
                this.selectedChat.messages.push(newMessage);
                this.selectedChat.lastMessage = newMessage.text;
            }
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
                    sender: this.selectedChat.isGroup ? (Math.random() > 0.5 ? 'Katya' : 'Alex') : this.selectedChat.name,
                    text: 'Looks great! Let’s keep practicing!',
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                    read: false,
                    showContextMenu: false,
                    contextMenuX: 0,
                    contextMenuY: 0,
                    reactions: [],
                    avatar: this.selectedChat.isGroup ? (Math.random() > 0.5 ? '/assets/avatar-katya.jpg' : '/assets/avatar-alex.jpg') : this.selectedChat.avatar,
                };
                this.selectedChat.messages.push(reply);
                this.selectedChat.lastMessage = reply.text;
                if (this.selectedChat !== this.chats.find(chat => chat.id === this.selectedChat.id)) {
                    this.selectedChat.unreadCount += 1;
                    this.newMessageNotification = this.selectedChat;
                }
                this.$nextTick(() => {
                    const chatMessages = this.$refs.chatMessages;
                    if (chatMessages) {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }
                });
                // Симуляция прочтения
                setTimeout(() => {
                    this.selectedChat.messages.forEach(msg => {
                        if (msg.sender === 'user') msg.read = true;
                    });
                }, 1500);
            }, 1000);
        },
        handleScroll() {
            const chatMessages = this.$refs.chatMessages;
            if (chatMessages.scrollTop === 0) {
                this.loadMoreMessages();
            }
        },
        loadMoreMessages() {
            const newMessages = [
                { id: this.selectedChat.messages.length + 1, sender: 'user', text: 'This is an older message.', time: 'Yesterday', read: true, avatar: this.currentUser.avatar },
                { id: this.selectedChat.messages.length + 2, sender: this.selectedChat.isGroup ? 'Katya' : this.selectedChat.name, text: 'Cool, let’s keep practicing!', time: 'Yesterday', read: true, avatar: this.selectedChat.isGroup ? '/assets/avatar-katya.jpg' : this.selectedChat.avatar },
            ];
            this.selectedChat.messages.unshift(...newMessages);
            this.$nextTick(() => {
                const chatMessages = this.$refs.chatMessages;
                chatMessages.scrollTop = 50;
            });
        },
        showContextMenu(event, message) {
            message.showContextMenu = true;
            message.contextMenuX = event.clientX;
            message.contextMenuY = event.clientY;
            document.addEventListener('click', this.hideContextMenu);
        },
        hideContextMenu() {
            this.selectedChat.messages.forEach(msg => {
                msg.showContextMenu = false;
            });
            document.removeEventListener('click', this.hideContextMenu);
        },
        editMessage(message) {
            this.editingMessage = message;
            this.newMessage = message.text;
            this.$refs.messageInput.focus();
        },
        deleteMessage(message) {
            this.selectedChat.messages = this.selectedChat.messages.filter(msg => msg.id !== message.id);
            this.selectedChat.lastMessage = this.selectedChat.messages.length ? this.selectedChat.messages[this.selectedChat.messages.length - 1].text : '';
        },
        reactToMessage(message, reaction) {
            if (!message.reactions) message.reactions = [];
            if (!message.reactions.includes(reaction)) {
                message.reactions.push(reaction);
            }
        },
        formatMessage(text) {
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" class="text-blue-500 underline">$1</a>');
        },
        filterChats() {
            // Фильтрация выполняется через computed property
        },
    },
    mounted() {
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
    beforeDestroy() {
        document.removeEventListener('click', this.hideContextMenu);
    },
};
</script>