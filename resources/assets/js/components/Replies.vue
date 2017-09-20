<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply"
                   @deleted="remove(index)"
            ></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <reply-form :endpoint="url"
                    @created="add"
        ></reply-form>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import ReplyForm from './ReplyForm.vue';
    import collection from '../mixins/collection';

    export default {
        name: 'replies',

        props: {
            thread: {required: true}
        },

        mixins: [collection],

        components: {Reply, ReplyForm},

        data() {
            return {
                dataSet: false,
                url: '/threads/' + this.thread.channel.slug + '/' + this.thread.id + '/replies'
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }

                axios.get(this.url + '?page=' + page).then(this.refresh);
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
            },
        },
    }
</script>
