<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :data="reply"
                   @deleted="remove(index)"
            ></reply>
        </div>

        <reply-form :endpoint="'/threads/' + this.thread.channel.slug + '/' + thread.id + '/replies'"
                    @created="add"
        ></reply-form>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import ReplyForm from './ReplyForm.vue';

    export default {
        name: 'replies',

        props: {
            data: {},
            thread: {required: true}
        },

        components: {Reply, ReplyForm},

        data() {
            return {
                items: this.data,
            }
        },

        methods: {
            add(item) {
                this.items.push(item);

                flash('Your reply has been left!');

                this.$emit('added');
            },

            remove(index) {
                this.items.splice(index, 1);

                flash('Your reply was deleted!');

                this.$emit('removed');
            }
        },
    }
</script>
