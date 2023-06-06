const path = require('path');

module.exports = {
  entry: {
    './public/admin/assets/js/bundle.js': './public/admin/assets/js/main.js',
  },
  output: {
    path: path.resolve(__dirname),
    filename: '[name]'
  },
  mode: 'development',
  watch: true,
  watchOptions: {
    aggregateTimeout: 1000,
    poll: 1000
  },
};
