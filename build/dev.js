var proxy_target = process.env.npm_config_proxy_target || 'http://jetbuilder.dev/';

var webpack = require('webpack');
var config = require('./webpack.dev.js');
var webpackDevServer = require('webpack-dev-server');
var port = 9090;

for (var k in config.entry) {
    if (config.entry.hasOwnProperty(k)) {
        config.entry[k].unshift('webpack-dev-server/client?http://localhost:' + port + '/', 'webpack/hot/dev-server');
    }
}

var server = new webpackDevServer(webpack(config), {
    hot: true,
    proxy: {
        "*": {
            target: proxy_target,
            changeOrigin: true
        }
    },
    contentBase: './',
    quiet: true,
    noInfo: false,
    publicPath: config.output.publicPath,
    stats: {colors: true}
});

server.listen(port, function (err) {
    if (err) {
        console.log(err);
    } else {
        console.log("> Listening at http://localhost:" + port + "\n");
    }
});

module.exports = server;