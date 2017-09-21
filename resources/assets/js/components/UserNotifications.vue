<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-envelope-o"></i> <span class="caret"></span>
        </a>

        <ul class="dropdown-menu" role="menu">
            <li v-for="notification in notifications">
                <a @click="markAsRead(notification)" :href="notification.data.link" class="level">
                    <strong class="flex" v-text="notification.data.message"></strong>
                    <span>{{ notification.created_at | ago }}</span>
                </a>
            </li>
        </ul>
    </li>
</template>

<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                notifications: false,
            }
        },

        created() {
            axios.get("/profiles/" + window.App.user.name + "/notifications")
                .then(({data}) => this.notifications = data);
        },

        filters: {
            ago: function (date) {
                return moment(date).fromNow() + '...';
            }
        },

        methods: {
            markAsRead(notification) {
                axios.delete("/profiles/" + window.App.user.name + "/notifications/" + notification.id);
            }
        }
    }
</script>
