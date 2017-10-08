<template>
    <div class="date-custom-field">
        <div class="form-group">
            <div class="col-md-3">
                <h4>Format de la date</h4>
                <p>Spécifier le format de la data à afficher dans la partie publique du site</p>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <input type="text" class="form-control" id="format" v-model="date_format">
                    <label for="format">Format</label>
                </div>
            </div><!--end .col -->
        </div>
    </div>
</template>

<script type="text/babel">
    export default{
        name: 'date-custom-field',
        props: {
            field: {
                type: Object,
                required: true
            },
            line: {
                default: 'default'
            }
        },
        computed: {
            date_format: {
                get() {
                    return (this.field.data.date_format !== undefined)
                            ? this.field.data.date_format : 'dd/mm/yyyy'
                },
                set(val){
                    this.$set(this.field.data, 'date_format', val)
                }
            }
        },
        mounted(){
            if (this.field.data.date_format === undefined) {
                this.field.data = {
                    date_format: 'dd/mm/yyyy'
                };
            }
        }
    }
</script>