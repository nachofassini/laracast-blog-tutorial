<template>
    <div :id="'reply-' + id" class="panel panel-default">
        <div class="panel-heading level">
            <div class="flex">
                <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name"></a>
                said {{ data.created_at }}...
            </div>

            <div v-if="signedIn">
                <favorite :reply="data"></favorite>
            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea name="body" id="body" v-model="form.body" class="form-control" rows="5"></textarea>
                </div>

                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="clean">Cancel</button>
            </div>
            <div v-else v-text="form.body"></div>
        </div>

        <div class="panel-footer" v-if="canUpdate">
            <button type="button" class="btn btn-primary btn-xs" @click="edit">Edit</button>
            <button type="button" class="btn btn-danger btn-xs" @click="destroy">Delete</button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';

    export default {
        components: { Favorite },

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
                }).catch(error => alert(error.response));
            },

            destroy() {
                axios.delete('/replies/' + this.data.id)
                    .then(response => {
                        this.$emit('deleted', this.data.id);
                    });
            },
        },
    }
</script>
