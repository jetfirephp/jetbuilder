var path = require('path');
var webpack = require('webpack');

function resolve (dir) {
    dir = dir || '';
    return path.join(__dirname, '../', dir)
}

module.exports = {

    context: resolve(),
    entry: {
        /*admin: ['./src/Blocks/AdminBlock/Views/Admin/admin.js'],*/
        auth: ['./src/Blocks/AdminBlock/Views/Admin/auth.js']
    },

    output: {
        path: resolve('src/Blocks/AdminBlock/Resources/public/dist/'),
        publicPath: '/src/Blocks/AdminBlock/Resources/public/dist/',
        filename: '[name].js'
    },

    module: {
        rules: [
            {
                test: /\.vue$/,
                use: ["vue-loader"]
            },
            {
                test: /\.js$/,
                use: ["babel-loader"],
                include: resolve(),
                exclude: /(node_modules|public[\/\\]js|src[\/\\]Blocks[\/\\]AdminBlock[\/\\]Resources[\/\\]public[\/\\]js)/
            },
            {
                test: /\.(png|jpg|gif|svg|woff2?|eot|ttf)$/,
                use: [
                    {
                        loader: "url-loader",
                        query: {
                            limit: 10000,
                            name: '[name]-[hash:7].[ext]'
                        }
                    }
                ]
            },
            {
                test: require.resolve('tinymce/tinymce'),
                use: [
                    "imports-loader?this=>window",
                    "exports-loader?window.tinymce"
                ]
            },
            {
                test: /tinymce\/(themes|plugins)\//,
                use: [
                    "imports-loader?this=>window"
                ]
            }
        ]
    },

    plugins: [
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        }),
        new webpack.IgnorePlugin(/regenerator|nodent|js\-beautify/, /ajv/)
    ],
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        modules: [
            resolve(),
            "node_modules"
        ],
        alias: {
            'vue' : 'vue/dist/vue.js',
            '@' : resolve(),
            '@admin' :  resolve('src/Blocks/AdminBlock/Views/Admin/'),
            '@admin_resource': resolve('src/Blocks/AdminBlock/Resources/public/'),
            '@modules': resolve('src/Modules/')
        }
    }

};