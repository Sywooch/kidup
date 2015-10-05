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
var merge = require('gulp-merge');
var remember = require('gulp-remember');
var minifyCss = require('gulp-minify-css');
var gulpif = require('gulp-if');
var _ = require('lodash');
var argv = require('yargs').argv;

var DEST = './web/packages/';

var packageNames = [
    'common',
    'home',
    'booking',
    'item-view',
    'item-create',
    'email',
    'message',
    'pages',
    'review',
    'search',
    'user-settings',
    'user',
    'admin',
    'other'
];
var packages = {};
var pack;

for (var i = 0; i < packageNames.length; i++) {
    pack = packageNames[i];
    if (argv.packages) {
        // packages param is given, only do required packages
        var requiredPackages = argv.packages.split(",");
        var found = false;
        _.map(requiredPackages, function (rPack) {
            if (pack == rPack) found = true;
        });
        if (!found) continue;
    }
    try {
        packages[pack] = require('./web/packages/' + pack + '/' + pack + '.json');
    } catch (error) {

    }
}

var jsFiles = [];
var cssFiles = [];

_.map(packages, function (pack, p) {
    if (_.isArray(pack.css)) {
        _.map(pack.css, function (cssPack) {
            cssFiles.push(cssPack);
        });
    }
    if (_.isArray(pack.js)) {
        _.map(pack.js, function (jsPack) {
            jsFiles.push(jsPack);
        });
    }
});

gulp.task('js', function () {
    var streams = [];
    for (var p in packages) { // dont use map for this
        pack = packages[p];
        if (typeof pack.js === "undefined" || pack.js.length == 0) return false;
        streams.push(gulp.src(pack.js)
            .pipe(cache('js' + p))
            .pipe(plumber())
            .pipe(remember('js' + p))
            // This will output the non-minified version
            .pipe(concat(p + '.js'))
            .pipe(gulpif(argv.production, uglify({mangle: false})))
            .pipe(gulp.dest(DEST + p + "/")));
    }
    return merge.apply(this, streams);
});

gulp.task('css', function () {
    var streams = [];
    for (var p in packages) { // dont use map for this
        pack = packages[p];
        if (typeof pack.css === "undefined" || pack.css.length == 0) continue;
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
            .pipe(gulpif(argv.production, minifyCss()))
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
    var bootstrap = gulp.src('vendor/bower/bootstrap/fonts/*.*')
        .pipe(gulp.dest('web/packages/fonts'));
    return merge(adelle, proxima, fa, bootstrap);
});

gulp.task('images', function () {
    var jqueryUi = gulp.src('vendor/bower/jquery-ui/themes/base/images/*.*')
        .pipe(gulp.dest('web/packages/images/jquery-ui'));
    var mapIcons = gulp.src('vendor/bower/leaflet/dist/images/*.*')
        .pipe(gulp.dest('web/packages/images/leaflet'));
    return merge(jqueryUi, mapIcons);
});

gulp.task('watch-js', function () {
    return gulp.watch(jsFiles, ['js']);
});

gulp.task('watch-css', function () {
    return gulp.watch(cssFiles, ['css'])
});

gulp.task('watch-json', function () {
    return gulp.watch('web/packages/**/*.json', ['css', 'js'])
});

gulp.task('browser-sync', function () {
    //browserSync.init({
    //    proxy: "http://192.168.33.99"
    //});
});

gulp.task('default', ['js', 'css', 'watch-js', 'browser-sync', 'watch-css', 'fonts', 'watch-json']);
gulp.task('build', ['js', 'css', 'fonts']);
