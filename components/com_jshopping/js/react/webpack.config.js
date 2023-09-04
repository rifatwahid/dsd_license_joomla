const path = require("path");

module.exports = {
    watch: true,

    entry: "./src/index.js",
    output: {
        path: path.join(__dirname, "/dist"),
        filename: "index_bundle.js"
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: ['@babel/preset-env',
                            '@babel/react',{
                                'plugins': ['@babel/plugin-proposal-class-properties']}]
                    }
                },
            },

            {
                test: /\.css$/,
                use: ["style-loader", "css-loader"]
            }
        ]
    }
};