var gulp = require('gulp');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var watch = require('gulp-watch');
var plumber = require('gulp-plumber');
var changed = require('gulp-changed');
var less = require('gulp-less');
var browserSync = require('browser-sync').create();
var path = require('path');
var cache = require('gulp-cached');
var filter = require('gulp-filter');

var DEST = 'web/packages/';

var packageNames = ['home'];
var common = require('./web/packages/common/common.package.json');
var packages = {};

for(var i = 0; i < packageNames.length; i++){
    var pack = packageNames[i];
    packages[pack] = require('./web/packages/'+pack+'/'+pack+'.package.json');
}

var jsFiles = [];
var cssFiles = [];

for(var p in packages){
    var pack = packages[p];
    for(i = 0; i < pack.css.length; i++){
        cssFiles.push(pack.css[i]);
    }
    for(i = 0; i <= pack.js.length; i++){
        jsFiles.push(pack.js[i]);
    }
}

gulp.task('watch-js', function () {
    return gulp.watch(jsFiles, ['js'])
});

gulp.task('js', function () {
    return gulp.src(jsFiles)
        .pipe(cache('js'))
        .pipe(plumber())
        // This will output the non-minified version
        .pipe(concat('common.js'))
        .pipe(gulp.dest(DEST+"/common/"))
        .pipe(browserSync.stream());
});

gulp.task('watch-css', function () {
    return gulp.watch(cssFiles, ['css'])
});

gulp.task('css', function () {
    return gulp.src(cssFiles)
        //.pipe(cache('css'))
        .pipe(plumber())
        //.pipe(less({
        //    paths: [ path.join(__dirname, 'views/assets/css/base', 'base') ]
        //}))
        // This will output the non-minified version
        .pipe(concat('common.css'))
        .pipe(gulp.dest(DEST+"common/"))
        .pipe(browserSync.stream());
});

gulp.task('browser-sync', function() {
    //browserSync.init({
    //    proxy: "http://192.168.33.99"
    //});
});

gulp.task('default', ['watch-js', 'browser-sync', 'watch-css']);