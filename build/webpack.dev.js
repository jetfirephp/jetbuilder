var base_file = process.env.npm_config_base || 'webpack.admin';
var webpack = require('webpack');
var config = require('./' + base_file);
var utils = require('./utils');
var merge = require('webpack-merge');

var FriendlyErrorsPlugin = require('friendly-errors-webpack-plugin');

module.exports = merge(config, {
    module: {
        rules: utils.styleLoaders({ sourceMap: false })
    },
    devtool: '#cheap-module-eval-source-map',
    plugins: [
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: '"development"'
            }
        }),
        new webpack.HotModuleReplacementPlugin(),
        new webpack.NoEmitOnErrorsPlugin(),
        new FriendlyErrorsPlugin()
    ]
});
