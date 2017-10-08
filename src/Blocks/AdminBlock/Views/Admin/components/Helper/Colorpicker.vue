<style>
    .colorpicker{
        z-index: 2999999 !important;
    }
</style>

<template>
    <div class="colorpicker-bloc">
        <div class="card">
            <div class="card-body style-primary">
                <form class="form form-inverse">
                    <div class="form-group">
                        <div class="row">
                            <input type="text" :id="'color-picker' + id" class="form-control" :value="value">
                        </div>
                    </div><!--end .form-group -->
                </form>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">

    import '@admin/libs/bootstrap-colorpicker/bootstrap-colorpicker.css'

    /* JS*/
    import '@admin/libs/bootstrap-colorpicker/bootstrap-colorpicker.min'


    export default{
        name: 'colorpicker',
        props: {
            id: {
                default: 'default'
            },
            value: {
                default: '#fff'
            }
        },
        mounted(){
            let o = this;
            this.$nextTick(function () {
                $('#color-picker' + o.id).colorpicker().on('changeColor', function (ev) {
                    o.$emit('updateColorpicker', ev.color.toHex());
                    $(ev.currentTarget).closest('.card-body').css('background', ev.color.toHex());
                });
            });
        }
    }
</script>
