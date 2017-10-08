<template>
    <div class="card">
        <div class="card-body style-primary">
            <form class="form form-inverse">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" :id="'color-picker' + id" class="form-control" :value="value">
                    </div>
                </div><!--end .form-group -->
            </form>
        </div><!--end .card-body -->
    </div><!--end .card -->
</template>

<script type="text/babel">

    import '@admin/libs/bootstrap-colorpicker/bootstrap-colorpicker.css'

    /* JS*/
    import '@admin/libs/bootstrap-colorpicker/bootstrap-colorpicker.min'

    export default{
        name: 'color-render-custom-field',
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
        computed: {
            value(){
                return this.content[this.content_key]
            }
        },
        mounted(){
            let o = this;
            this.$nextTick(() => {
                $('#color-picker' + o.id).colorpicker().on('changeColor', function (ev) {
                    o.$set(o.content, o.content_key, ev.color.toHex());
                    $(ev.currentTarget).closest('.card-body').css('background', ev.color.toHex());
                });
                $('#color-picker' + o.id).colorpicker('setValue', this.value);
            });
        }
    }
</script>