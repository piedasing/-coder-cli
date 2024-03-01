module.exports = ({ addComponents }) => {
    addComponents({
        '.flex-center': {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
        },
        '.absolute-center': {
            position: 'absolute',
            top: '50%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
        },
    });
};
