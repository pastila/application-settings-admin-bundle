const path = require('path')
module.exports = {
    entry: {
        'bundle-landing': './landing/js/app.js',
        'bundle': './js/main.js',
    },
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: '[name].js',
    },
};