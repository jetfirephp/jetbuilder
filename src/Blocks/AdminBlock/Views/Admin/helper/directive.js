export default {
    img: {
        inserted: function (el, binding) {
            let val = (binding.value === '') ? '/public/media/default/default-image.png' : binding.value;
            el.src = PUBLIC_PATH + val;
        },
        update: function (el, binding) {
            let val = (binding.value === '') ? '/public/media/default/default-image.png' : binding.value;
            el.src = PUBLIC_PATH + val;
        }
    }
}