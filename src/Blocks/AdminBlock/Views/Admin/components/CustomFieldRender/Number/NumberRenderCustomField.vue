<template>
    <div class="form-group floating-label">
        <input type="text" class="form-control" :id="'spinner-' + id" :value="content[content_key]"/>
    </div>
</template>

<script type="text/babel">

    import '@admin/libs/jquery-ui/jquery-ui-theme.css'

    import '@admin/libs/jquery-ui/jquery-ui.min'
    import '@admin/libs/spinjs/spin.min'

    export default{
        name: 'number-render-custom-field',
        props: {
            field: {
                type: Object,
                required: true
            },
            id: {
                default: 'default'
            },
            content: {
                default: ''
            },
            content_key: {
                default: 'value'
            }
        },
        data() {
            return {
                config: {}
            }
        },
        mounted(){
            let o = this;
            if (this.field.data.step !== undefined) this.config['step'] = this.field.data.step;
            if (this.field.data.min !== undefined) this.config['min'] = this.field.data.min;
            if (this.field.data.max !== undefined && this.field.data.max != '') this.config['max'] = this.field.data.max;
            $("#spinner-" + this.id).spinner(this.config);
            $("#spinner-" + this.id).on("spinchange", (event, ui) => {
               o.$set(this.content, this.content_key, $("#spinner-" + this.id).val());
            });
        }
    }
</script>