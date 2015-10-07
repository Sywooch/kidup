var gulp = require('gulp');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var watch = require('gulp-watch');
var plumber = require('gulp-plumber');
var changed = require('gulp-changed');
var less = require('gulp-less');
//var browserSync = require('browser-sync').create();
var path = require('path');
var cache = require('gulp-cached');
var filter = require('gulp-filter');
var merge = require('merge-stream');
var remember = require('gulp-remember');
var minifyCss = require('gulp-minify-css');
var gulpif = require('gulp-if');
var _ = require('lodash');
var argv = require('yargs').argv;


var watchers = require('./web/packages/watch.json');
var originals = require('./web/packages/originals.json');
var cssFiles = [];
var origCssFiles = [];
var origCssFilesWatch = [];
var jsFiles = [];
var origJsFiles = [];
var origJsFilesWatch = [];

if (_.isArray(watchers.css)) {
    cssFiles = watchers.css;
}
if (_.isArray(watchers.js)) {
    jsFiles = watchers.js;
}
if (_.isObject(originals.css)) {
    origCssFiles = originals.css;
    _.map(originals.css, function(file){
        origCssFilesWatch.push(file);
    });
}
if (_.isObject(originals.js)) {
    origJsFiles = originals.js;
    _.map(originals.js, function(file){
        origJsFilesWatch.push(file);
    });
}

gulp.task('js', function () {
    var streams = [];
    _.map(origJsFiles, function (original, asset) {
        var dest = asset.replace(asset.replace(/^.*[\\\/]/, ''), '');
        var stream = gulp.src(original)
            //.pipe(changed(dest))
            .pipe(gulp.dest(dest));
        streams.push(stream);
    });
    return merge.apply(this, streams);
});

gulp.task('css', function () {
    var streams = [];
    _.map(origCssFiles, function (original, asset) {
        var dest = asset.replace(asset.replace(/^.*[\\\/]/, ''), '');
        var stream = gulp.src(original)
            //.pipe(changed(dest))
            .pipe(gulp.dest(dest));
        streams.push(stream);
    });
    return merge.apply(this, streams);
});

gulp.task('less', function () {
    var streams = [];
    _.map(cssFiles, function (file) {
        var lessFilter = filter('**/*.less', {restore: true});
        var stream = gulp.src(file)
            .pipe(cache(file))
            .pipe(plumber())
            .pipe(lessFilter)
            .pipe(less({
                paths: [path.join('.', '../')]
            }))
            .pipe(lessFilter.restore)
            .pipe(remember(file))
            // This will output the non-minified version
            .pipe(gulp.dest(file.replace(file.replace(/^.*[\\\/]/, ''), '')));
        streams.push(stream);
    });
    return merge.apply(this, streams);
});

gulp.task('watch-css', function () {
    return gulp.watch(origCssFilesWatch, ['css']);
});

gulp.task('watch-js', function () {
    return gulp.watch(origJsFilesWatch, ['js']);
});

gulp.task('watch-less', function () {
    return gulp.watch(cssFiles, ['less'])
});

gulp.task('default', ['js', 'css', 'watch-js', 'watch-css', 'watch-less']);
