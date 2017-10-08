var webpack = require('webpack');
var config = require('./webpack.prod.js');
require('shelljs/global');

webpack(config, function (err, stats) {
    if (err) throw err;
    process.stdout.write(stats.toString({
            colors: true,
            modules: false,
            children: false,
            chunks: false,
            chunkModules: false
        }) + '\n')
});
