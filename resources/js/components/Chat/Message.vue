<template>
    <div class="chat__message chat__message--own mt-1">
        <strong class="chat__message-user">Alex</strong>
        <span class="chat__message-timestamp">1234567</span>
        <p class="chat__message-body">Message</p>

    </div>
</template>
<script>
export default {
    
}
</script>

<style lang="scss">
    .chat {
        &__message {
            padding: 15px;
            border-bottom: 1px solid #eee;

            &--own {
                background-color: #f0f0f0;
            }

            &-user {
                font-weight: 800;
            }

            &-timestamp {
                color: #aaa;
            }

            &-body {
                margin-bottom: 0;
                white-space: pre-wrap;
            }
        }
    }
</style>
