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
var merge = require('gulp-merge');
var remember = require('gulp-remember');
var minifyCss = require('gulp-minify-css');

var DEST = './web/packages/';

var packageNames = ['home', 'common'];
var packages = {};
var pack;

for (var i = 0; i < packageNames.length; i++) {
    pack = packageNames[i];
    packages[pack] = require('./web/packages/' + pack + '/' + pack + '.json');
}

var jsFiles = [];
var cssFiles = [];

for (var p in packages) {
    pack = packages[p];
    for (i = 0; i < pack.css.length; i++) {
        cssFiles.push(pack.css[i]);
    }
    for (i = 0; i < pack.js.length; i++) {
        jsFiles.push(pack.js[i]);
    }
}

gulp.task('js', function () {
    var streams = [];
    for (var p in packages) {
        pack = packages[p];
        streams.push(gulp.src(pack.js)
            .pipe(cache('js' + p))
            .pipe(plumber())
            .pipe(remember('js' + p))
            // This will output the non-minified version
            .pipe(concat(p + '.js'))
            .pipe(gulp.dest(DEST + p + "/")));
    }
    return merge.apply(this, streams);
});

gulp.task('css', function () {
    var streams = [];
    for (var p in packages) {
        pack = packages[p];
        var lessFilter = filter('**/*.less', {restore: true});
        streams.push(gulp.src(pack.css)
            .pipe(cache('css' + p))
            .pipe(plumber())
            .pipe(lessFilter)
            .pipe(less({
                paths: [path.join('.')]
            }))
            .pipe(lessFilter.restore)
            .pipe(remember('css' + p))
            // This will output the non-minified version
            .pipe(concat(p + '.css'))
            .pipe(gulp.dest(DEST + p + "/")));
    }
    return merge.apply(this, streams);
});

gulp.task('fonts', function () {
    var adelle = gulp.src('views/assets/fonts/adelle/*.*')
        .pipe(gulp.dest('web/packages/fonts/adelle'));
    var proxima = gulp.src('views/assets/fonts/proxima/*.*')
        .pipe(gulp.dest('web/packages/fonts/proxima/'));
    var fa = gulp.src('vendor/bower/font-awesome/fonts/*.*')
        .pipe(gulp.dest('web/packages/fonts'));
    return merge(adelle, proxima, fa);
});

gulp.task('watch-js', function () {
    return gulp.watch(jsFiles, ['js']);
});

gulp.task('watch-css', function () {
    return gulp.watch(cssFiles, ['css'])
});

gulp.task('browser-sync', function () {
    //browserSync.init({
    //    proxy: "http://192.168.33.99"
    //});
});


gulp.task('build-js', function () {
    var streams = [];
    for (var p in packages) {
        pack = packages[p];
        streams.push(gulp.src(pack.js)
            .pipe(plumber())
            // This will output the non-minified version
            .pipe(concat(p + '.js'))
            .pipe(uglify())
            .pipe(gulp.dest(DEST + p + "/")));
    }
    return merge.apply(this, streams);
});

gulp.task('build-css', function () {
    var streams = [];
    for (var p in packages) {
        pack = packages[p];
        var lessFilter = filter('**/*.less', {restore: true});
        streams.push(gulp.src(pack.css)
            .pipe(plumber())
            .pipe(lessFilter)
            .pipe(less({
                paths: [path.join('.')]
            }))
            .pipe(lessFilter.restore)
            // This will output the non-minified version
            .pipe(concat(p + '.css'))
            .pipe(minifyCss())
            .pipe(gulp.dest(DEST + p + "/")));
    }
    return merge.apply(this, streams);
});

gulp.task('default', ['js', 'css', 'watch-js', 'browser-sync', 'watch-css', 'fonts']);

gulp.task('build', ['build-js', 'build-css', 'fonts']);