var gulp = require('gulp');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var minify = require('gulp-minify');

gulp.task('scripts', function () {
    return gulp.src(['./node_modules/jquery/dist/jquery.min.js', './node_modules/popper.js/dist/umd/popper.min.js', './node_modules/bootstrap/dist/js/bootstrap.min.js', './resources/js/app.js'])
        .pipe(concat('app.js'))
        .pipe(minify())
        .pipe(gulp.dest('app/public/js/'));
});
gulp.task('fonts', function () {
    return gulp.src("./node_modules/@fortawesome/fontawesome-free/webfonts/**.*")
        .pipe(gulp.dest('app/public/webfonts'));
});
gulp.task('styles', function () {
    return gulp.src(['resources/scss/app.scss'])
        .pipe(sass({ outputStyle: 'compressed' }))
        .pipe(concat('app.css'))
        .pipe(gulp.dest('app/public/css/'))
});
gulp.task('default', gulp.parallel('styles', 'scripts', 'fonts'), function (done) {
    done();
});