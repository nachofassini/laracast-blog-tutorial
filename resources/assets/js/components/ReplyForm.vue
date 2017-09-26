<template>
    <div>
        <div v-if="signedIn">
            <form @submit.prevent="submit">
                <div class="form-group">
                    <textarea placeholder="Leave your reply"
                              name="body"
                              id="body"
                              class="form-control"
                              rows="5"
                              v-model="body"
                              required
                    ></textarea>
                </div>

                <button class="btn btn-default">Post</button>
            </form>
        </div>
        <p v-else class="text-center">Please <a href="/login">sign in</a> to participate in this discussion.</p>
    </div>
</template>

<script>
    import 'jquery.caret';
    import 'at.js';

    export default {
        props: {
            endpoint: {required: true},
        },

        data() {
            return {
                body: '',
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },
        },

        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON("/users", {name: query}, function (usernames) {
                            callback(usernames)
                        });
                    }
                }
            });
        },

        methods: {
            submit() {
                axios.post(this.endpoint, {body: this.body})
                    .then(({data}) => {
                        this.body = '';

                        this.$emit('created', data);
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
            },
        },
    }
</script>
