"use strict";

import gulp from "gulp";
import plugins from "gulp-load-plugins";

import browserSync from "browser-sync";

/* ----------------- */
/* Styles
/* ----------------- */

gulp.task("styles", () => {
    return gulp
        .src(["./assets/scss/*.scss"])
        .pipe(plugins().sourcemaps.init())
        .pipe(
            plugins()
                .sass()
                .on("error", plugins().sass.logError)
        )
        .pipe(plugins().sourcemaps.write())
        .pipe(gulp.dest("./web/"))
        .pipe(browserSync.stream());
});

// Move the javascript files into our /src/js folder
gulp.task("js", () => {
    return gulp
        .src(["node_modules/bootstrap/dist/js/bootstrap.min.js", "node_modules/tether/dist/js/tether.min.js", "./assets/scripts/*.js"])
        .pipe(gulp.dest("./web/"))
        .pipe(browserSync.stream());
});

// Static Server + watching scss/html files
gulp.task(
    "serve",
    gulp.series("styles", () => {
        browserSync.init({
            proxy: "knights.bodu",
        });

        gulp.watch(["node_modules/bootstrap/scss/bootstrap.scss", "scss/*.scss"], gulp.parallel(["styles"]));
        gulp.watch(["templates/*.twig"]).on("change", () => {
            browserSync.reload();
        });
    })
);

gulp.task("default", gulp.series("js", "serve"));
