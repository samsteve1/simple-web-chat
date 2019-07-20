<template>
    <div class="chat px-2">
       <chat-messages></chat-messages>

       <form action="" class="chat__form">
           <textarea 
                id="body" 
                cols="30" 
                rows="10"
                class="chat__form-input"
                v-model="body"
                @keydown="handleMessageInput"
                ></textarea>
                <span class="chat__form-helptext">
                    Hit return to send or Shift + Return for a new line.
                </span>
       </form>
    </div>
</template>

<script>
import Bus from '../../bus'
import moment from 'moment'

export default {
    data() {
        return {
            body: null,
            bodyBackedUp: this.body
        }
    },
    methods: {
        handleMessageInput(e) {
            this.bodyBackedUp = this.body

            if(e.keyCode === 13 && !e.shiftKey) {
                e.preventDefault();
                this.send();
            }
        },

        buildTempMessage() {
            let tempId = Date.now();

            return {
                id: tempId,
                body: this.body,
                'created_at': moment.utc(Date.now()).format('YYYY-MM-DD HH:mm:ss'),
                selfOwned: true,
                user: {
                    name: AuthUser.user.name
                }
            }
        },

        send() {
            if (!this.body || this.body.trim() === '') {
                return
            }

            let tempMessage = this.buildTempMessage();

            Bus.$emit('message.added', tempMessage)

            axios.post('/chat/messages', {
                body: this.body.trim()
            }).then((response) => {
                
            })
            .catch(() => {
                this.body = this.bodyBackedUp
                
                Bus.$emit('message.removed', tempMessage)
                
            })
            this.body = null
        }
    },
}
</script>

<style lang="scss">

    .chat {
        background-color: #fff;
        border: 1px solid #d3e0e9;
        border-radius: 3px;

        &__form {
            border-top: 1px solid #d3e0e9;
            padding: 10px;

            &-input {
                width: 100%;
                border: 1px solid #d3e0e9;
                padding: 5px 10px;
                outline: none;
            }

            &-helptext {
                color: #aaa;
            }
        }
    }
    
</style>
