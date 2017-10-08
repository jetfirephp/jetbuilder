<template>
    <div class="login-page">
        <loading></loading>
        <response></response>
        <form id="login-form" @keyup.enter="submit">
            <div class="row header">
                <h1>Admin {{app_name}}</h1>
                <h2>{{ $t("message.auth.login") }}</h2>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="email" type="email" v-model="auth.email" required class="validate">
                    <label for="email" data-error="Format incorrect">{{ $t("message.form.email") }}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="password" type="password" v-model="auth.password" required class="validate">
                    <label for="password">{{ $t("message.form.password") }}</label>
                </div>
            </div>
            <div class="row center-align">
                <div class="col s6">
                    <p>
                        <input type="checkbox" v-model="auth.remember" id="remember" />
                        <label for="remember">{{ $t("message.auth.stay_connect") }}</label>
                    </p>
                </div>
                <div class="col s6">
                    <a @click="submit" class="waves-effect waves-light btn-large">{{ $t("message.form.submit") }}</a>
                </div>
            </div>
            <div class="row">
                <div class="col s12 right-align">
                    <router-link to="/lost-password">{{ $t("message.auth.forgot_password") }}</router-link>
                </div><!--end .col -->
            </div>

        </form>
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
                },
                app_name : APP_NAME
            }
        },
        methods : {
            ...mapActions([
                'login', 'setResponse', 'clearResponse'
            ]),
            submit() {
                if (document.getElementById('email').classList.contains('valid') && document.getElementById('password').classList.contains('valid')) {
                    this.login(this.auth);
                } else {
                    this.clearResponse();
                    this.setResponse({status: 'error', message: this.$t('message.form.required_nbr_fields', {'nbr': "2"})});
                }
            }
        }
    }
</script>
