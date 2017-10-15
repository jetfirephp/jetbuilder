<template>
    <div class="lost-password-page col-12">

        <loading></loading>
        <response class="mb20 w-400px mx-auto"></response>

        <div class="card card-shadowed px-50 py-30 w-400px mx-auto" style="max-width: 100%">
            <h5 class="text-uppercase">{{ $t("message.auth.recover_password") }}</h5>
            <br>

            <form data-provide="validation" class="form-type-material">
                <div class="form-group">
                    <input type="email" class="form-control validate" v-model="email" required id="email">
                    <label for="email">{{ $t("message.form.email") }}</label>
                    <div class="invalid-feedback"></div>
                </div>

                <br>
                <a @click="submit()" class="btn btn-bold btn-block btn-primary">{{ $t("message.form.submit") }}</a>

                <router-link to="/login">Se connecter</router-link>

            </form>
        </div>
    </div>
</template>


<script type="text/babel">

    import { mapActions } from 'vuex'

    export default
    {
        components: {
            Response: resolve => { require(['@admin/components/Helper/Response.vue'], resolve) },
            Loading: resolve => { require(['@admin/components/Helper/Loading.vue'], resolve) }
        },
        data () {
            return {
                email: ''
            }
        },
        methods : {
            ...mapActions([
                'lostPassword'
            ]),
            submit() {
                if(!document.getElementById('email').classList.contains('is-invalid')) {
                    this.lostPassword(this.email);
                }
            }
        }

    }
</script>
