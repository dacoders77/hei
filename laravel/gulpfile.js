var gulp = require('gulp');
var sass = require('gulp-sass');
var plumber = require('gulp-plumber');
var sourcemaps = require('gulp-sourcemaps');
var postcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
var gulpif = require('gulp-if');
var argv = require('yargs').argv;
var uglify = require('gulp-uglify');
var jshint = require('gulp-jshint');
var notify = require('gulp-notify');
var concat = require('gulp-concat');
var strip = require('gulp-strip-comments');
var imagemin = require('gulp-imagemin');
var runSequence = require('run-sequence');
var del = require('del');
var changed = require('gulp-changed');

var src = {
  js: {
    admin: ['resources/assets/js/libs/jquery.once.js','resources/assets/js/libs/jquery.validate.min.js','resources/assets/js/admin.js'],
    //public: ['resources/assets/js/libs/*.js','resources/assets/js/scripts.js','!resources/assets/js/admin.js'],
      public: [
          'resources/assets/js/libs/_jquery.js',
          'resources/assets/js/libs/datepicker.min.js',
          'resources/assets/js/libs/jquery.autotab.min.js',
          'resources/assets/js/libs/jquery.easy-autocomplete.min.js',
          'resources/assets/js/libs/jquery.fancybox.min.js',
          'resources/assets/js/libs/jquery.geocomplete.min.js',
          'resources/assets/js/libs/jquery.maskedinput.js',
          'resources/assets/js/libs/jquery.once.js',
          'resources/assets/js/libs/jquery.simplewizard.js',
          'resources/assets/js/libs/jquery.validate.min.js',
          'resources/assets/js/libs/scratchcard.min.js',
          'resources/assets/js/libs/tooltipster.main.js',
          'resources/assets/js/scripts.js',
          '!resources/assets/js/admin.js'
      ],
    other: ['resources/assets/js/*.js','!resources/assets/js/scripts.js','!resources/assets/js/admin.js'],
  },
  scss: {
    admin: 'resources/assets/scss/admin.scss',
    public: ['resources/assets/scss/**/*.scss','!resources/assets/scss/admin.scss'],
  },
  css: 'resources/assets/css/**/*.css'
}

var dist = {
  js: 	{
    admin: 'public/assets/admin/js/',
    public: 'public/assets/js/',
  },
  css:  {
    admin: 'public/assets/admin/css/',
    public: 'public/assets/css/',
  },
}

gulp.task('sass', function() {
  gulp.src(src.scss.admin)
    .pipe(sourcemaps.init())
    .pipe(plumber())
    .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
    .pipe(gulpif(argv.production, sass({outputStyle: 'compressed'}).on('error', sass.logError)))
    .pipe(postcss([autoprefixer()]))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(dist.css.admin));

  gulp.src(src.scss.public)
    .pipe(sourcemaps.init())
    .pipe(plumber())
    .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
    .pipe(gulpif(argv.production, sass({outputStyle: 'compressed'}).on('error', sass.logError)))
    .pipe(postcss([autoprefixer()]))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(dist.css.public));
});

gulp.task('css', function() {
  gulp.src(src.css)
    .pipe(gulp.dest(dist.css.public));
});

gulp.task('js', function () {
  gulp.src(src.js.admin)
    .pipe(concat('admin.js'))
    .pipe(gulp.dest(dist.js.admin));

  gulp.src(src.js.public)
    .pipe(jshint())
    .pipe(notify(function (file) {
      if (file.jshint.success) {
        return false;
      }
      var errors = file.jshint.results.map(function (data) {
        if (data.error) {
          return "(" + data.error.line + ':' + data.error.character + ') ' + data.error.reason;
        }
      }).join("\n");
      return file.relative + " (" + file.jshint.results.length + " errors)\n" + errors;
    }))
    .pipe(concat('build.js'))
    .pipe(gulpif(argv.production, uglify()))
    .pipe(gulpif(argv.production, strip()))
    .pipe(gulp.dest(dist.js.public));

  gulp.src(src.js.other)
    .pipe(jshint())
    .pipe(notify(function (file) {
      if (file.jshint.success) {
        return false;
      }
      var errors = file.jshint.results.map(function (data) {
        if (data.error) {
          return "(" + data.error.line + ':' + data.error.character + ') ' + data.error.reason;
        }
      }).join("\n");
      return file.relative + " (" + file.jshint.results.length + " errors)\n" + errors;
    }))
    .pipe(gulpif(argv.production, uglify()))
    .pipe(gulpif(argv.production, strip()))
    .pipe(gulp.dest(dist.js.public));
});

gulp.task('images', function() {
  gulp.src('resources/assets/images/**/*.ico')
    .pipe(changed('public/assets/images'))
    .pipe(gulp.dest('public/assets/images'))

  gulp.src('resources/assets/images/**/*.+(png|jpg|jpeg|gif|svg)')
    .pipe(changed('public/assets/images'))
    .pipe(imagemin({
      interlaced: true,
    }))
    .pipe(gulp.dest('public/assets/images'))
});

gulp.task('fonts', function() {
  return gulp.src(['resources/assets/fonts/**/*'])
    .pipe(changed('public/assets/fonts'))
    .pipe(gulp.dest('public/assets/fonts'))
});

gulp.task('admin', function() {
  return gulp.src('resources/assets/admin/**/*')
    .pipe(gulp.dest('public/assets/admin/'))
});

gulp.task('admin-lte', function() {
  gulp.src([
      'node_modules/admin-lte/bower_components/**/*',
    ])
    .pipe(gulp.dest('public/assets/admin/admin-lte/bower_components/'))
  gulp.src([
      'node_modules/admin-lte/plugins/**/*',
    ])
    .pipe(gulp.dest('public/assets/admin/admin-lte/plugins/'))
  gulp.src([
      'node_modules/admin-lte/dist/**/*',
    ])
    .pipe(gulp.dest('public/assets/admin/admin-lte/dist/'))
});

gulp.task('watch',function() {
    gulp.watch('resources/assets/fonts/**/*', ['fonts']);
    gulp.watch('resources/assets/images/**/*.+(png|jpg|jpeg|gif|svg|ico)', ['images']);
    gulp.watch('resources/assets/scss/**/*.scss',['sass']);
    gulp.watch('resources/assets/css/**/*.css', ['css']);
    gulp.watch('resources/assets/js/**/*.js', ['js']);
});

gulp.task('clean', function() {
    if(argv.production) {
        del.sync(['public/assets/**/*'],{force:true});
        del.sync(['public/assets/admin/**/*'],{force:true});
    }
});

gulp.task('default', function() {
    runSequence('clean','admin-lte',['admin','fonts','images','sass','css','js'],'watch');
});