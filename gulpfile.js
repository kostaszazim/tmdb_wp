'use strict';
const gulp = require("gulp");

const sass = require('gulp-sass')(require('sass'));

const zip = require('gulp-zip');

const cleanCss = require('gulp-clean-css');

const browserSync = require('browser-sync').create();

const plugin_folder = 'tmdb_integration';


gulp.task("hello", (done) => {
    console.log("hello from gulp");
    done();
})


gulp.task('packages-style', () => {
    return gulp.src("wp-content/plugins/itc_packages/woocommerce/admin/assets/sass/admin_packages.scss", {sourcemaps: true}).pipe(sass()).pipe(gulp.dest('wp-content/plugins/itc_packages/woocommerce/admin/assets/css', {sourcemaps: true})).pipe(browserSync.stream());
})

gulp.task('watch2', () => {
    gulp.watch([`wp-content/plugins/${plugin_folder}/**/*.(php|js|reload)`], gulp.series('reload'));
})
gulp.task('watch', () => {
    gulp.watch(['wp-content/plugins/itc_packages/**/sass/**/*.scss'], gulp.series('packages-style'));
})

gulp.task('serve', () => {
    browserSync.init({
        port:8080,
        proxy:'http://movies.plg/',
        open: 'local'
    })
})

gulp.task('make-prod', () => {
    return gulp.src(['**/*','!node_modules/**', '!.prettierrc', '!dist/**', '!gulpfile.js', '!*.json', '!**/*.sass', '!**/sass/**' ]).pipe(zip('dist.zip')).pipe(gulp.dest('dist'));
})


gulp.task('reload', function(done){
    browserSync.reload();
    done();
})

gulp.task('live-server', gulp.parallel('serve','watch2'));

gulp.task('live-server-stream', gulp.parallel('serve', 'watch'));