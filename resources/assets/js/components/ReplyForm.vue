<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea placeholder="Leave your reply"
                          name="body"
                          class="form-control"
                          rows="5"
                          v-model="body"
                          required
                ></textarea>
            </div>

            <button class="btn btn-default" @click="submit">Post</button>
        </div>
        <p v-else class="text-center">Please <a href="/login">sign in</a> to participate in this discussion.</p>
    </div>
</template>

<script>
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

        methods: {
            submit() {
                axios.post(this.endpoint, {body: this.body})
                    .then(({data}) => {
                        this.body = '';

                        this.$emit('created', data);
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
        },
    }
</script>
