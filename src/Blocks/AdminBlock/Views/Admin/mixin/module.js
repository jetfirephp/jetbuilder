export default {
    methods: {
        updateContent(){
            if (this.content.template.id !== undefined && this.content.template.id != '') {
                this.$emit('updateContent', this.content);
                this.closeModal();
            } else
                this.setResponse({status: 'error', message: 'Veuillez choisir le template'});
        },
        closeModal(){
            $("#editContentModal" + this.line).modal("hide")
        }
    }
};
