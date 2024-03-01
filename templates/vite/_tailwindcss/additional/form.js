module.exports = ({ addComponents, theme }) => {
    addComponents({
        '.input': {
            width: '100%',
            padding: '8px 12px',
            'border-radius': '8px',
            border: '1px solid #B0B0B0',
            'background-color': '#E8E8E8',
            'box-shadow': '0px 0px 5px 0px rgba(0, 0, 0, 0.25) inset',
            'font-size': '16px',
        },
        '.select': {
            width: '100%',
            padding: '4px 12px',
            border: '1px solid #f1da98',
            color: '#e8cd68',
            'text-align': 'center',
            'background-color': 'transparent',
            'box-shadow': '0px 0px 5px 0px rgba(0, 0, 0, 0.25) inset',
            'font-size': '16px',
            appearance: 'none',
            backgroundImage: 'url(@/assets/images/icon-triangle-down.png)',
            backgroundSize: '14px 7px',
            backgroundRepeat: 'no-repeat',
            backgroundPosition: '90% center',
        },
    });
};
