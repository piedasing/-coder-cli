const plugins = require('./_tailwindcss/tailwind.plugin.js');

module.exports = {
    prefix: 'tw-',
    important: false,
    separator: ':',
    corePlugins: {},
    mode: 'jit',
    content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
    darkMode: 'media', // or 'media' or 'class'
    plugins: [
        function ({ addVariant }) {
            addVariant('hover', '@media (hover: hover) and (pointer: fine) { &:hover }');
            addVariant(
                'group-hover',
                '@media (hover: hover) and (pointer: fine) { :merge(.group):hover & }',
            );
            addVariant(
                'peer-hover',
                '@media (hover: hover) and (pointer: fine) { :merge(.peer):hover ~ & }',
            );
        },
        ...plugins.use(),
    ],
    theme: {
        extend: {
            colors: {
                primary: '#0096ff',
                success: '#67C23A',
                info: '#909399',
                warning: '#E6A23C',
                danger: '#F56C6C',
                transparent: 'transparent',
                white: '#fff',
                black: '#000',
            },
            zIndex: {
                auto: 'auto',
                0: '0',
                10: '10',
                20: '20',
                30: '30',
                40: '40',
                50: '50',
                header: '1050',
                modal: '2000',
                swal: '2050',
                loading: '2100',
            },
        },
    },
};
