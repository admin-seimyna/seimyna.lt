const path = require('path');
const svgToMiniDataURI = require('mini-svg-data-uri');
const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const { VueLoaderPlugin } = require('vue-loader');

const src = path.join(__dirname, '/resources');
const production = process.env.NODE_ENV === 'production';

module.exports = {
    mode: process.env.NODE_ENV,
    entry: {
        app: [
            path.join(src, 'js/app.js'),
            path.join(src, 'scss/app.scss'),
        ]
    },
    output: {
        path: path.resolve(__dirname, 'www'),
        filename: production ? 'js/[name].[chunkhash].js' : 'js/[name].js',
    },
    resolve: {
        extensions: ['.js', '.vue', '.scss', '.css'],
        alias: {
            'vue': '@vue/runtime-dom',
            '@': path.resolve(__dirname, 'resources/js/'),
        },
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                use: [
                    'vue-loader',
                ],
            },{
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                options: {
                    presets: ['@babel/preset-env'],
                },
            },{
                test: /\.(sa|sc|c)ss$/,
                use: [
                    MiniCSSExtractPlugin.loader,
                    'css-loader',
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: {
                                    tailwindcss: {
                                        config: './tailwind.config.js'
                                    },
                                    autoprefixer: {},
                                }
                            }
                        }
                    }, {
                        loader: 'resolve-url-loader',
                        options: {
                            sourceMap: true,
                        },
                    }, {
                        loader: 'sass-loader',
                        options: {
                            implementation: require('sass'),
                            sourceMap: true,
                        },
                    },
                ],
            }, {
                test: /\.svg$/i,
                exclude: [],
                use: [
                    {
                        loader: 'url-loader',
                        options: {
                            generator: (content) => svgToMiniDataURI(content.toString()),
                        },
                    },
                ],
            }, {
                test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
                generator: {
                    filename: 'fonts/[name][ext]',
                },
            },
        ],
    },
    optimization: {
        splitChunks: {
            cacheGroups: {
                vendor: {
                    test: /node_modules.*\.js$/,
                    name: 'vendor',
                    chunks: 'all',
                },
            },
        },
    },
    plugins: [
        new VueLoaderPlugin(),
        new MiniCSSExtractPlugin({
            filename: production ? 'css/[name].[chunkhash].css' : 'css/[name].css',
            ignoreOrder: true,
        }),
    ],
    node: {
        global: true,
        __filename: false,
        __dirname: false
    }
}
