const gulp = require('gulp')

gulp.task('dist', function () {
	return gulp.src(
	  [
		'./**/*.php',
		'./**/*.txt',
		'./**/*.css',
		'./**/*.png',
		'./**/*.jpg',
		'./**/*.jpeg',
		'./**/*.svg',
		'./**/*.json',
		'./**/*.js',
		'./assets/**',
		'./inc/**',
		'./build/**',
		'./patterns-data/',
		"!./.vscode/**",
		"!./bin/**",
		"!./dist/**",
		"!./node_modules/**/*.*",
		"!./tests/**",
		"!./dist/**",
	  ], {
		base: './'
	  }
	).pipe(gulp.dest("dist/vk-block-patterns"));
  });