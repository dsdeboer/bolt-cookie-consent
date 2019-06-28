"use strict";

import gulp from "gulp";

import sass from "gulp-sass";
import autoprefixer from "gulp-autoprefixer";
import minCss from "gulp-minify-css";
import rename from "gulp-rename";

const config = {
    srcCss: "assets/scss/**/*.scss",
    buildCss: "web",
};

gulp.task("styles", function(cb) {
    gulp.src(config.srcCss)

        // output non-minified CSS file
        .pipe(
            sass({
                outputStyle: "expanded",
            }).on("error", sass.logError)
        )
        .pipe(autoprefixer())
        .pipe(gulp.dest(config.buildCss))

        // output the minified version
        .pipe(minCss())
        .pipe(rename({ extname: ".min.css" }))
        .pipe(gulp.dest(config.buildCss));

    cb();
});

/* ----------------- */
/* Styles
/* ----------------- */
const DIST_DIR = "web";

// Move the javascript files into our /src/js folder
gulp.task("js", () => {
    return gulp.src(["node_modules/bootstrap/dist/js/bootstrap.min.js", "node_modules/tether/dist/js/tether.min.js", "./assets/scripts/*.js"]).pipe(gulp.dest(DIST_DIR));
});

// Static Server + watching scss/html files
gulp.task(
    "serve",
    gulp.series("styles", () => {
        gulp.watch(["node_modules/bootstrap/scss/bootstrap.scss", "assets/scss/*.scss"], gulp.parallel(["styles"]));
    })
);

gulp.task("default", gulp.series("js", "styles"));
