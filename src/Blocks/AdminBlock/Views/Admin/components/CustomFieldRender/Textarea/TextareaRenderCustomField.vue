<template>
    <div class="form-group floating-label">
        <textarea type="text" v-model="content[content_key]" class="form-control"></textarea>
    </div>
</template>

<script type="text/babel">
    export default{
        name: 'textarea-render-custom-field',
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
        }
    }
</script>