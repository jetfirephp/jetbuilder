<template>
    <div class="lost-password-page">
        <loading></loading>
        <response></response>
        <form>
            <div class="row header">
                <h1>Admin Webzy</h1>
                <h2>Réinitialiser votre mot de passe</h2>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="email" type="email" v-model="email" required class="validate">
                    <label for="email" data-error="Format incorrect">Votre adresse e-mail</label>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <a @keyup.enter="submit()" @click="submit()" class="full-width waves-effect waves-light btn-large">Valider</a>
                </div>
            </div>
            <div class="row">
                <div class="col s12 left-align">
                    <router-link to="/login">Se connecter</router-link>
                </div>
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
                email: ''
            }
        },
        methods : {
            ...mapActions([
                'lostPassword'
            ]),
            submit() {
                if(document.getElementById('email').classList.contains('valid'))
                    this.lostPassword(this.email);
            }
        }

    }
</script>
