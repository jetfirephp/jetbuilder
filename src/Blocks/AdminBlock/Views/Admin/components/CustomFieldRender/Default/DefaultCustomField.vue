<template></template>

<script type="text/babel">
    export default{
        name: 'default-custom-field',
        props: {
            field: {
                type: Object,
                required: true
            },
            line: {
                default: 'default'
            }
        }
    }
</script>