<script>
    export default {
        props: {
            attributes: {},
        },

        data() {
            return {
                form: this.attributes,
                editing: false,
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
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.form.body
                }).then(response => {
                    this.clean();
                    this.form = response.data;
                    flash('Updated!');
                }).catch(error => alert(error.response));
            },

            destroy() {
                axios.delete('/replies/' + this.attributes.id)
                    .then(response => {
                        $(this.$el).fadeOut(300, () => {
                            flash('Your reply has been deleted!')
                        });
                    });
            },
        },
    }
</script>
