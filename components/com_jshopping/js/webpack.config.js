const path = require('path')
const fs = require('fs')
const TerserPlugin  = require('terser-webpack-plugin')
const UglifyESPluginb = require('uglify-es-webpack-plugin')

let adminPath = path.resolve('../../../administrator/components/com_jshopping/js/');
if (!fs.existsSync(adminPath)) {
    adminPath = path.resolve('../../admin/js/');
}

module.exports = [{
    name: "front",
    mode: "production",
    context: path.resolve(__dirname, 'src'),
    entry: [
        './jquery/photoswipe.addon_offer_and_order.min.js',
        './jquery/photoswipe.min.js',
        './jquery/photoswipe-ui-default.min.js',
        './index.js',
        './deprecated.js'
    ],
    output: {
        filename: "index.min.js",
        path: path.resolve(__dirname, 'dist')
    },
    module: {
        rules: [
            {
                test: /(deprecated|photoswipe.addon_offer_and_order.min|photoswipe-ui-default.min|photoswipe.min).js/,
                use : [
                    {
                        loader: 'script-loader',
                        options:{
                            plugins: [
                                new TerserPlugin({
                                    terserOptions: {
                                        keep_fnames: true,
                                    }
                                })
                            ]
                        }
                    }
                ]
            }
        ]
    },
}, {
    name: "admin",
    mode: "production",
    context: path.resolve(adminPath, 'src'),
    entry: [
        './index.js',
        './deprecated.js'
    ],
    output: {
        filename: "index.min.js",
        path: path.resolve(adminPath, 'dist')
    },
    optimization: {
        minimizer: [
            new UglifyESPluginb({
                compress: false,
                keep_fnames: true
            })
        ]
    }
}]