<script>
    import ImageUpload from '../components/ImageUpload.vue';

    export default {
        props: {
            user: {required: true},
        },

        components: {
            'image-upload': ImageUpload,
        },

        data() {
            return {
                avatar: this.user.avatar,
            };
        },

        methods: {
            updateAvatar(avatar) {
                let data = new FormData();
                data.append('avatar', avatar.file);

                axios.post('/profile/avatar', data)
                    .then(response => {
                        this.avatar = avatar.src;

                        flash('Avatar image updated!');
                    })
                    .catch(reseponse => {
                        flash('Fail updating profile image', 'danger');
                    });
            }
        }
    }
</script>
