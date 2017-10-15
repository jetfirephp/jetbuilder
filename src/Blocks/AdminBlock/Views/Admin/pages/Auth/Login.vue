<template>

    <div class="login-page col-12">

        <loading></loading>
        <response class="mb20 w-400px mx-auto"></response>

        <div class="card card-shadowed px-50 py-30 w-400px mx-auto" style="max-width: 100%">
            <h5 class="text-uppercase">{{ $t("message.auth.login") }}</h5>
            <br>

            <form data-provide="validation" class="form-type-material" id="login-form" @keyup.enter="submit">
                <div class="form-group">
                    <input id="email" type="email" v-model="auth.email" required class="form-control">
                    <label class="require" for="email">{{ $t("message.form.email") }}</label>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <input id="password" type="password" v-model="auth.password" required class="form-control validate">
                    <label class="require" for="password">{{ $t("message.form.password") }}</label>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group flexbox flex-column flex-md-row">
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" v-model="auth.remember">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">{{ $t("message.auth.stay_connect") }}</span>
                    </label>

                    <router-link class="text-muted hover-primary fs-13 mt-2 mt-md-0" to="/lost-password">{{ $t("message.auth.forgot_password") }}</router-link>
                </div>

                <div class="form-group">
                    <a class="btn btn-bold btn-block btn-primary" @click="submit">{{ $t("message.form.submit") }}</a>
                </div>

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
                auth: {
                    email: '',
                    password: '',
                    remember: false
                }
            }
        },
        methods : {
            ...mapActions([
                'login', 'setResponse', 'clearResponse'
            ]),
            submit() {
                if (!document.getElementById('email').classList.contains('is-invalid') && !document.getElementById('password').classList.contains('is-invalid')) {
                    this.login(this.auth);
                } else {
                    this.clearResponse();
                    this.setResponse({status: 'error', message: this.$t('message.form.required_nbr_fields', {'nbr': "2"})});
                }
            }
        }
    }
</script>
