<template>
    <div class="alert alert-flash"
         :class="'alert-' + level"
         role="alert"
         v-show="this.show"
         v-text="body"
    ></div>
</template>

<script>
    export default {
        props: {
            'message': {required: true},
            'initialLevel': null,
        },

        data() {
            return {
                body: '',
                level: 'success',
                show: false,
            }
        },

        created() {
            if (this.message) {
                this.flash({
                    'message': this.message,
                    'level': this.initialLevel ? this.initialLevel : this.level,
                });
            }

            window.events.$on('flash', data => this.flash(data));
        },

        methods: {
            flash(data) {
                this.body = data.message;
                this.level =  data.level;
                this.show = true;
                this.hide();
            },

            hide() {
                setTimeout(() => {
                    this.show = false;
                    this.body = '';
                }, 3000);
            }
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        bottom: 25px;
        right: 25px;
    }
</style>
