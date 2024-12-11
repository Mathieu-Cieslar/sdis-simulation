module.exports = {
  devServer: {
    proxy: {
      '/api': {
        target: 'http://backend:8083',
        changeOrigin: true,
      },
    },
  },
};