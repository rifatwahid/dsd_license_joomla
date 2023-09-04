const gulp     = require('gulp')
const sass     = require('gulp-sass')
const concat   = require('gulp-concat') 
const cleanCSS = require('gulp-clean-css')
const autoprefixer = require('gulp-autoprefixer')
const sourcemaps   = require('gulp-sourcemaps')

module.exports = {

  dev: (template) => {
    return gulp.src([
      `../templates/${template}/css/sass/**/*.+(sass|scss)`,
      './photoswipe/photoswipe.+(css|sass|scss)',
      './photoswipe/default-skin.+(css|sass|scss)'
    ])
    .pipe(sourcemaps.init())
    .pipe(sass())
    .pipe(autoprefixer('> 1%, Last 2 versions'))
    .pipe(concat('index.css'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(`../templates/${template}/css/`))
  },

  prod: (template) => {
    return gulp.src([
      `../templates/${template}/css/sass/**/*.+(sass|scss)`,
      './photoswipe/photoswipe.+(css|sass|scss)',
      './photoswipe/default-skin.+(css|sass|scss)'
    ])
    .pipe(sass())
    .pipe(autoprefixer('> 1%, Last 2 versions'))
    .pipe(concat('index.min.css'))
    .pipe(cleanCSS({ level: { 1: { specialComments: false } } }))
    .pipe(gulp.dest(`../templates/${template}/css/`))
  }

}