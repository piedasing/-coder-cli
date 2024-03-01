module.exports = ({ addComponents }) => {
    addComponents({
        '.btn': {
            width: '240px',
            height: '56px',
            padding: '20px 0px',
            display: 'inline-flex',
            'justify-content': 'center',
            'align-items': 'center',
            'border-radius': '100px',
        },
        '.btn-primary': {
            color: '#fff',
            background:
                'linear-gradient(27deg, #273624 8.33%, #4B544F 91.67%), #FFF',
        },
    });
};
