<template>
    <button :class="this.classes" @click="toggle"
            :title="count">
        <i class="fa fa-star" v-if="isFavorited"></i>
        <i class="fa fa-star-o" v-else></i>
        <span v-text="count"></span>
    </button>
</template>

<script>
    export default {
        props: {
            reply: {required: true}
        },

        data() {
            return {
                count: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited,
                endpoint: '/replies/' + this.reply.id + '/favorites',
            }
        },

        computed: {
            classes() {
                return ['btn', this.isFavorited ? 'btn-primary' : 'btn-link'];
            }
        },

        methods: {
            toggle() {
                return this.isFavorited ? this.unFavorite() : this.favorite();
            },

            favorite() {
                axios.post(this.endpoint)
                    .then(response => {
                        this.isFavorited = true;
                        this.count++;
                    });
            },

            unFavorite() {
                axios.delete(this.endpoint)
                    .then(response => {
                        this.isFavorited = false;
                        this.count--;
                    });
            }
        }
    }
</script>
