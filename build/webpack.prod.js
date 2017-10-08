var base_file = process.env.npm_config_base || 'webpack.admin';
var public_path = process.env.npm_config_public_path || '';

var path = require('path');
var config = require('./' + base_file);
var utils = require('./utils');
var webpack = require('webpack');
var merge = require('webpack-merge');

var ExtractTextPlugin = require("extract-text-webpack-plugin");
var OptimizeCSSPlugin = require('optimize-css-assets-webpack-plugin');

module.exports = merge(config, {
    module: {
        rules: utils.styleLoaders({
            sourceMap: true,
            extract: true
        })
    },
    output: {
        filename: '[name].js',
        chunkFilename: 'js/[id].[chunkhash].js',
        publicPath: public_path + config.output.publicPath
    },
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: '"production"'
            }
        }),
        // extract css into its own file
        new ExtractTextPlugin({
            filename: 'css/[name].css'
        }),
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            },
            sourceMap: true,
            minimize: false,
            comments: false
        }),
        // Compress extracted CSS. We are using this plugin so that possible
        // duplicated CSS from different components can be deduped.
        new OptimizeCSSPlugin(),

        // split vendor js into its own file
        new webpack.optimize.CommonsChunkPlugin({
            name: 'vendor',
            minChunks: function (module, count) {
                // any required modules inside node_modules are extracted to vendor
                return (
                    module.resource &&
                    /\.js$/.test(module.resource) &&
                    module.resource.indexOf(path.join(__dirname, '../node_modules')) === 0
                )
            }
        }),
        // extract webpack runtime and module manifest to its own file in order to
        // prevent vendor hash from being updated whenever app bundle is updated
        new webpack.optimize.CommonsChunkPlugin({
            name: 'manifest',
            chunks: ['vendor']
        })
    ]
});

