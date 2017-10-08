<style>
    .datepicker{
        z-index: 9999;
    }
</style>

<template>
    <div class="datepicker-bloc">
        <div class="input-group date-picker date" :id="'date-picker-' + id">
            <div class="input-group-content">
                <label :for="'date-picker-value-' + id">{{label}}</label>
                <input :id="'date-picker-value-' + id" type="text" :value="value" class="form-control">
                <p class="help-block">{{options.format}}</p>
            </div>
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
</template>

<script type="text/babel">

    import '@admin/libs/bootstrap-datepicker/datepicker3.css'

    /* JS*/
    import '@admin/libs/bootstrap-datepicker/bootstrap-datepicker'
    import '@admin/libs/bootstrap-datepicker/datepicker-locale'

    export default{
        name: 'datepicker',
        props: {
            id: {
                default: 'default'
            },
            label: {
                type: String,
                default: 'Date'
            },
            options: {
                type: Object,
                default: () => {
                    return {
                        language: 'fr',
                        autoclose: true,
                        todayHighlight: true,
                        format: 'dd/mm/yyyy'
                    }
                }
            },
            value: {}
        },
        mounted(){
            let o = this;
            $('#date-picker-' + this.id).datepicker(Object.assign({}, {
                language: 'fr',
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy'
            }, this.options)).on('changeDate', (e) => {
                o.$emit('updateDatepicker',e.format(e.date.o, o.options.format))
            })
        }
    }
</script>
