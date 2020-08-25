var gulp = require('gulp');
var babel = require('gulp-babel');
var autoprefixer = require('autoprefixer');
var notify = require('gulp-notify');
var uglify = require('gulp-uglify');
var cleanCss = require('gulp-clean-css');
var plumber = require('gulp-plumber');
var postcss = require('gulp-postcss');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var rm = require('gulp-rm');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var browserSync = require('browser-sync').create('Server');
var devUrl = 'http://visionpointmarketing.com.ddev.site/';

// Configuration file to keep your code DRY
var cfg = require('./gulpconfig.json');
var paths = cfg.paths;

gulp.task('clean:build', function() {
  return gulp.src('assets/build/**/*').pipe(rm());
});

// Run:
// gulp sass
// Compiles SCSS files in CSS
gulp.task('sass', function() {
  var stream = gulp
    .src(`${paths.sass}/**/*.scss`)
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(
      plumber({
        errorHandler: function(err) {
          console.log(err);
          this.emit('end');
        }
      })
    )
    .pipe(sass({ errLogToConsole: true }))
    .pipe(postcss([autoprefixer()]))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(paths.css))
    .pipe(rename('custom-editor-style.css'));
  return stream;
});
// gulp.task('scss', function () {
//     return gulp.src('assets/src/scss/**/*.scss')
//         .pipe(scss().on('error', scss.logError),
//         { noCache: true, style: 'compressed' })
//         .pipe(sourcemaps.init())
//         .pipe(sourcemaps.write('.'))
//         .pipe(cleanCss())
//         .pipe(concat('all.min.css'))
//         .pipe(wpcachebust({
//           themeFolder: './wp-content/themes/branch',
//           rootPath: './'
//         }))
//         .pipe(gulp.dest('assets/build/css/'))
//         // .pipe(notify('scss build complete!'))
//         .pipe(browserSync.stream());
// });

// gulp.task('fonts', function () {
//     return gulp.src('./vendor/twbs/bootstrap/assets/fonts/bootstrap/*')
//         .pipe(gulp.dest('assets/build/fonts/'))
//         .pipe(browserSync.stream());
// });

// FONT AWESOME
// Move font-awesome webfonts and css folders to assets/build/fonts
gulp.task('icons', function() {
  return gulp
    .src(
      [
        './vendor/components/font-awesome/webfonts/**.*',
        './vendor/components/font-awesome/css/**.*'
      ],
      {
        base: './vendor/components/font-awesome'
      }
    )
    .pipe(gulp.dest('./assets/build/fonts/font-awesome'));
});

gulp.task('js', function() {
  return (
    gulp
      .src([
        // loading jquery 3.3.1 from CDN
        // './node_modules/jquery/dist/jquery.js',
        './vendor/twbs/bootstrap/dist/js/bootstrap.min.js',
        './assets/src/js/*.js'
      ])
      .pipe(babel())
      // .on('error', function(e) {
      //   console.log('>>> ERROR', e);
      //   // emit here
      //   this.emit('end');
      // })
      .pipe(sourcemaps.init())
      .pipe(concat('all.min.js'))
      .pipe(sourcemaps.write())
      .pipe(uglify())
      .pipe(gulp.dest('./assets/build/js'))
      // .pipe(notify('JS build complete!'))
      .pipe(browserSync.stream())
  );
});

gulp.task('watch', function() {
  notify('Watch started!');
  var files = [
    './style.css',
    './assets/src/scss/**/*.scss',
    './assets/src/js/*.js',
    './**/*.php',
    './**/*.twig'
  ];
  browserSync.init(files, {
    proxy: {
      target: devUrl //Your wordpress URL
    }
  });
  gulp.watch('assets/src/js/*.js', gulp.series('js'));
  gulp.watch('assets/src/scss/**/*.scss', gulp.series('sass'));
  // gulp.watch(['fonts']);
  gulp.watch('*.twig').on('change', browserSync.reload);
});

gulp.task('default', gulp.parallel('icons', 'js', 'sass', 'watch'));
