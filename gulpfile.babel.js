"use strict";

import gulp from "gulp";

import sass from "gulp-sass";
import autoprefixer from "gulp-autoprefixer";
import minCss from "gulp-minify-css";
import rename from "gulp-rename";

const config = {
    srcScss: ["assets/scss/**/*.scss"],
    build: "web",
};

gulp.task("styles", function(cb) {
    gulp.src(config.srcScss)
        .pipe(
            sass({
                outputStyle: "expanded",
            }).on("error", sass.logError)
        )
        .pipe(autoprefixer())
        .pipe(gulp.dest(config.build))

        // output the minified version
        .pipe(minCss())
        .pipe(rename({ extname: ".min.css" }))
        .pipe(gulp.dest(config.build));

    cb();
});

gulp.task("vendor", () => {
    return gulp
        .src([
            "node_modules/cookieconsent/build/cookieconsent.min.css",
            "node_modules/cookieconsent/build/cookieconsent.min.js",
            "node_modules/bootstrap/dist/js/bootstrap.min.js",
            "node_modules/tether/dist/js/tether.min.js",
        ])
        .pipe(gulp.dest(config.build));
});
gulp.task("js", () => {
    return gulp.src(["./assets/scripts/*.js"]).pipe(gulp.dest(config.build));
});

// Static Server + watching scss/html files
gulp.task(
    "serve",
    gulp.series("styles", () => {
        gulp.watch(["node_modules/bootstrap/scss/bootstrap.scss", "assets/scss/*.scss"], gulp.parallel(["styles"]));
    })
);

gulp.task("default", gulp.series("js", "vendor", "styles"));
