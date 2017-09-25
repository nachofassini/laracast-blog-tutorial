<template>
    <div :id="'reply-' + id" class="panel panel-default">
        <div class="panel-heading level">
            <div class="flex">
                <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name"></a>
                said {{ data.created_at | ago }}
            </div>

            <div v-if="signedIn">
                <favorite :reply="data"></favorite>
            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea name="body" id="body" v-model="form.body" class="form-control" rows="5" required></textarea>
                    </div>

                    <button class="btn btn-xs btn-primary">Update</button>
                    <button type="button" class="btn btn-xs btn-link" @click="clean">Cancel</button>
                </form>
            </div>
            <div v-else v-html="form.body"></div>
        </div>

        <div class="panel-footer" v-if="canUpdate">
            <button type="button" class="btn btn-primary btn-xs" @click="edit">Edit</button>
            <button type="button" class="btn btn-danger btn-xs" @click="destroy">Delete</button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';

    export default {
        components: {Favorite},

        props: {
            data: {},
        },

        data() {
            return {
                form: this.data,
                editing: false,
                id: this.data.id,
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },

            canUpdate() {
                return this.authorize(user => this.data.user_id == user.id);
            },
        },

        filters: {
            ago: function (date) {
                return moment(date).fromNow() + '...';
            }
        },

        methods: {
            clean() {
                this.editing = false;
            },

            edit() {
                this.editing = true;
            },

            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.form.body
                }).then(response => {
                    this.clean();
                    this.form = response.data;
                    flash('Updated!');
                }).catch(error => {
                    flash(error.response.data, 'danger');
                });
            },

            destroy() {
                axios.delete('/replies/' + this.data.id)
                    .then(response => {
                        this.$emit('deleted', this.data.id);
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
            },
        },
    }
</script>
