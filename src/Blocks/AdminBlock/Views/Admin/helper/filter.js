import moment from 'moment'
moment.locale('fr');

export default {
    moment: (val, format) => {
        return moment(val).format(format);
    },
    extract: (value, keyToExtract) => {
        return value.map(function (item) {
            return item[keyToExtract]
        })
    },
    truncate: (text, length, clamp) => {
        clamp = clamp || '...';
        var node = document.createElement('div');
        node.innerHTML = text;
        var content = node.textContent;
        return content.length > length ? content.slice(0, length) + clamp : content;
    },
    convert: (value) => {
        value = Math.abs(parseInt(value, 10));
        let def = [[1, 'octets'], [1024, 'ko'], [1024 * 1024, 'Mo'], [1024 * 1024 * 1024, 'Go'], [1024 * 1024 * 1024 * 1024, 'To']];
        let def_length = def.length;
        for (let i = 0; i < def_length; i++) {
            if (value < def[i][0]) return (value / def[i - 1][0]).toFixed(2) + ' ' + def[i - 1][1];
        }
    },
    toString: (value) => {
        return value.toString();
    },
    capitalize: (text) => {
        return (typeof text === 'string')
            ? text.charAt(0).toUpperCase() + text.slice(1)
            : text;
    },
    uppercase: (text) => {
        return text.toUpperCase()
    },
    lowercase: (text) => {
        return text.toLowerCase()
    },
    slug: (text) => {
        text = text.replace(/^\s+|\s+$/g, ''); // trim
        text = text.toLowerCase();

        // remove accents, swap ñ for n, etc
        var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
        var to   = "aaaaaeeeeeiiiiooooouuuunc------";
        for (var i=0, l=from.length ; i<l ; i++) {
            text = text.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        text = text.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

        return text;
    }
};
