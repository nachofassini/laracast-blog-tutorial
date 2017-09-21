<script>
    import Replies from '../components/Replies.vue';

    export default {
        props: {
            'data': {required: true},
        },

        components: {
            'replies': Replies,
        },

        data() {
            return {
                thread: this.data,
                repliesCount: this.data.replies_count,
            }
        },

        methods: {
            subscribe() {
                flash('You are now subscribed!');
            },

            unsubscribe() {
                flash('You are now unsubscribed!');
            },
        },
    }
</script>
