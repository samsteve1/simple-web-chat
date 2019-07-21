<template>
    <div class="users" v-if="users.length">
       <div class="users__header">{{ users.length }} user{{ users.length > 1 ? 's' : '' }} online</div>

       <div class="users__user" v-for="user in users" :key="user.id">
           <a href="#"><span class="bull">&bull;</span> {{ user.name }}</a>
       </div>
    </div>
</template>
<script>
import Bus from '../../bus'

export default {
    data() {
        return {
            users: []
        }
    },
    methods: {
        removeUser(id) {
            this.users = this.users.filter((user) => {
                return user.id !== id              
            })
                
        },

        addUser(user)
        {
            this.users.unshift(user)
        }
    },
    mounted() {
        Bus.$on('users.here', (users) => {
            this.users = users
        })
        .$on('user.joined', (user) => {
           this.addUser(user)
        })
        .$on('user.left', (user) => {
            this.removeUser(user.id)
        })
    },
}
</script>

<style lang="scss">
    .bull{
        color:green;
    }
    .users {
        background-color: #ffffff;
        border: 1px solid #323333;
        border-radius: 3px;

        &__header {
            padding: 15px;
            font-weight: 800;
            margin: 0;
        }

        &__user {
            padding: 0 15px;
        }
        &:last-child {
            padding-bottom: 15px;
        }
    }
</style>
