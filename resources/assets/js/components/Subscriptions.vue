<template>
    <div v-if="signedIn">
        <button type="button"
                :class="classes"
                @click="toggle"
                v-text="subscribed ? 'Unsubscribe' : 'Subscribe'"
        ></button>
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

            classes() {
                return ['btn', this.subscribed ? 'btn-primary' : 'btn-default'];
            }
        },

        methods: {
            toggle() {
                return this.subscribed ? this.unsubscribe() : this.subscribe();
            },

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
