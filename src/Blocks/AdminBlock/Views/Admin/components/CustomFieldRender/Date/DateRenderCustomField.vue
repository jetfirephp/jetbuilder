<template>
    <div class="form-group">
        <datepicker :value="value" :options="options" :id="id" @updateDatepicker="updateValue"></datepicker>
    </div>
</template>

<script type="text/babel">

    export default{
        name: 'date-render-custom-field',
        components: {
            Datepicker: resolve => { require(['../../Helper/Datepicker.vue'], resolve) }
        },
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
                options: {
                    format:'dd/mm/yyyy'
                }
            }
        },
        computed: {
            value(){
                return this.content[this.content_key]
            }
        },
        methods: {
           updateValue(value){
               this.$set(this.content, this.content_key, value);
           }
        },
        mounted(){
            if (this.field.data.date_format !== undefined) this.options.format = this.field.data.date_format;
        }
    }
</script>