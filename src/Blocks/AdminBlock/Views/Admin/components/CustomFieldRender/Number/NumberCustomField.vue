<template>
    <div class="number-custom-field">
        <div class="form-group row">
            <div class="col-md-3">
                <h4>Valeur d'incrémentation</h4>
                <p>Spécifier la valeur d'incrémentation</p>
            </div>
            <div class="col-md-9 form-group">
                <input type="number" v-model="step" :id="'step-' + line" class="form-control">
                <label :for="'step-' + line">Pas d'incrémentation</label>
            </div><!--end .col -->
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <h4>Min</h4>
                <p>Spécifier la valeur minimum</p>
            </div>
            <div class="col-md-9 form-group">
                <input type="text" v-model="min" :id="'min-' + line" class="form-control">
                <label :for="'min-' + line">Min</label>
            </div><!--end .col -->
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <h4>Max</h4>
                <p>Spécifier la valeur maximum</p>
            </div>
            <div class="col-md-9 form-group">
                <input type="text" v-model="max" :id="'max-' + line" class="form-control">
                <label :for="'max-' + line">Max</label>
            </div><!--end .col -->
        </div>
    </div>
</template>

<script type="text/babel">
    export default{
        name: 'number-custom-field',
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
            step: {
                get() {
                    return (this.field.data.step !== undefined) ? this.field.data.step : 1
                },
                set(val){
                    this.$set(this.field.data, 'step', val)
                }
            },
            min: {
                get() {
                    return (this.field.data.min !== undefined) ? this.field.data.min : 0
                },
                set(val){
                    this.$set(this.field.data, 'min', parseFloat(val))
                }
            },
            max: {
                get() {
                    return (this.field.data.max !== undefined) ? this.field.data.max : ''
                },
                set(val){
                    this.$set(this.field.data, 'max', parseFloat(val))
                }
            }
        },
    }
</script>