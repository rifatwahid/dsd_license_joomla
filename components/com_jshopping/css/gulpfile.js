const gulp = require('gulp')
const { dev, prod } = require('./gulp.helper')

gulp.task('sass-dev', gulp.parallel(
  () => dev('base'),
  () => dev('belem')
))

gulp.task('sass-prod', gulp.parallel(
  () => prod('base'),
  () => prod('belem')
))

gulp.task('sass', gulp.parallel(['sass-prod', 'sass-dev']))

gulp.task('watch', () => {
  gulp.watch([
    '../templates/base/css/sass/**/*.+(sass|scss)',
    '../templates/belem/css/sass/**/*.+(sass|scss)',
  ], gulp.series('sass'))
})