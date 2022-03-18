const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
  ...defaultConfig,
  entry: __dirname + '/src/edit-post/header-toolbar/index.js',
  output: {
    path: __dirname + '/build/edit-post/header-toolbar/',
    filename: 'index.js',
  },
};
