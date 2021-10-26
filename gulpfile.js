const gulp = require('gulp')

gulp.task('dist', function (done) {
	const files = gulp.src(
	  [
		'./**/*.php',
		'./**/*.txt',
		'./**/*.css',
		'./**/*.png',
		'./**/*.jpg',
		'./**/*.jpeg',
		'./**/*.json',
		'./assets/**',
		'./inc/**',
		'./patterns-data/',
		"!./vendor/**",
		"!./.vscode/**",
		"!./bin/**",
		"!./dist/**",
		"!./node_modules/**/*.*",
		"!./tests/**",
		"!./dist/**",
	  ], {
		base: './'
	  }
	)
	files.pipe(gulp.dest("dist/vk-block-patterns"));
	done();
  });