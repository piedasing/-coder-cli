const plugin = require('tailwindcss/plugin');

const { twExtra } = require('./additional/index.js');
const { twForm, twButton } = require('./additional/index.js');

module.exports = {
    use: () => {
        let plugins = [];

        plugins.push(plugin(twExtra));

        plugins.push(plugin(twForm));
        plugins.push(plugin(twButton));

        return plugins;
    },
};
