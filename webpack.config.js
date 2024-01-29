const path = require('path');

module.exports = {
    entry: './assets/js/admin.js', // Your main JS file
    output: {
        filename: 'admin.js', // Output bundle file
        path: path.resolve(__dirname, 'dist'), // Directory where bundle.js is saved
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                },
            },
        ],
    },
};
