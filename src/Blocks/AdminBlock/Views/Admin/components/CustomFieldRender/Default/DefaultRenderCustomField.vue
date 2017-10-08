<template>
    <div class="form-group floating-label">
        <input type="text" v-model="content[content_key]" class="form-control">
    </div>
</template>

<script type="text/babel">
    export default{
        name: 'default-render-custom-field',
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