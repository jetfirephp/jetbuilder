<template>
    <div class="reset-password-page">
        <loading></loading>
        <response></response>
        <form>
            <div class="row header">
                <h1>Admin Webzy</h1>
                <h2>Générer un nouveau mot de passe</h2>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="password" type="password" required v-model="password" class="validate">
                    <label for="password">Mot de passe</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="confirm_pass" type="password" required v-model="confirm_pass" class="validate">
                    <label for="confirm_pass">Confirmez votre mot de passe</label>
                </div>
            </div>
            <div class="row center-align">
                <div class="col s12">
                    <a @click="submit()" class="waves-effect waves-light btn-large">Valider</a>
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
                account: '',
                token: '',
                password: '',
                confirm_pass: ''
            }
        },
        mounted (){
            this.account = this.$route.query.id;
            this.token = this.$route.query.token;
        },
        methods : {
            ...mapActions([
                'resetPassword', 'clearResponse'
            ]),
            submit() {
                if(this.password == this.confirm_pass)
                    this.resetPassword({account: this.account, token: this.token, password: this.password, confirm_pass:this.confirm_pass});
                else{
                    this.clearResponse();
                    this.setResponse({
                        visible: true,
                        type: 'string',
                        status: 'error',
                        message: 'Les deux mots de passe ne sont pas identiques'
                    });
                }
            }
        }

    }
</script>
