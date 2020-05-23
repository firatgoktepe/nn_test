// Initialize modules
// Importing specific gulp API functions lets us write them below as series() instead of gulp.series()
const { src, dest, watch, series, parallel } = require('gulp');
// Importing all the Gulp-related packages we want to use
const sourcemaps = require('gulp-sourcemaps'); // For understanding real path of scss in browsers
const sass = require('gulp-sass'); // Compile scss file to css
//const concat = require('gulp-concat'); // For combine js files into one js file
//const uglify = require('gulp-uglify'); // For minifying js files
const postcss = require('gulp-postcss'); // For css (includes autoprefixer and cssnano)
//const autoprefixer = require('autoprefixer'); // For css
const cssnano = require('cssnano'); // For minifying css files




// File paths
const files = {
    scssPath: 'src/scss/**/*.scss',
    jsPath: 'src/js/**/*.js'
}

// Sass task: compiles the style.scss file into style.css
function scssTask(){
    return src(files.scssPath)
        .pipe(sourcemaps.init()) // initialize sourcemaps first
        .pipe(sass().on('error', sass.logError)) // compile SCSS to CSS
        .pipe(postcss([ cssnano() ])) // PostCSS plugins
        .pipe(sourcemaps.write('.')) // write sourcemaps file in current directory
        .pipe(dest('dist')

    ); // put final CSS in dist folder
}

// JS task: concatenates and uglifies JS files to script.js
/*
function jsTask(){
    return src([
        files.jsPath
        //,'!' + 'includes/js/jquery.min.js', // to exclude any specific files
        ])
        .pipe(concat('all.js'))
        .pipe(uglify())
        .pipe(dest('dist')
    );
}*/

// Watch task: watch SCSS and JS files for changes
// If any change, run scss and js tasks simultaneously
function watchTask(){
    watch(files.scssPath,
        {interval: 1000, usePolling: true}, //Makes docker work
        series(
            parallel(scssTask)

        )
    );
}

// Export the default Gulp task so it can be run
// Runs the scss and js tasks simultaneously
// then runs cacheBust, then watch task
exports.default = series(
    parallel(scssTask),
    watchTask
);

//  ***** NOTE: for running all of these: type only "gulp" at once ******
