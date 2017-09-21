<template>
    <div v-if="signedIn">
        <button v-if="!subscribed"
                type="button"
                class="btn btn-default center-block"
                @click="subscribe"
        >Subscribe</button>
        <button v-else
                type="button"
                class="btn btn-primary center-block"
                @click="unsubscribe"
        >Unsubscribe</button>
    </div>
</template>

<script>
    export default {
        props: {
            endpoint: {required: true},
            isSubscribed: false,
        },

        data() {
            return {
                subscribed: this.isSubscribed,
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },
        },

        methods: {
            subscribe() {
                axios.post(this.endpoint)
                    .then(response => {
                        this.subscribed = true;

                        this.$emit('subscribed');
                    });
            },

            unsubscribe() {
                axios.delete(this.endpoint)
                    .then(response => {
                        this.subscribed = false;

                        this.$emit('unsubscribed');
                    });
            },
        },
    }
</script>
