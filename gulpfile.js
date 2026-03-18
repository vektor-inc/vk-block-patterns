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
		'./vendor/**',
		'./inc/**',
		'./build/**',
		'./patterns-data/**',
		"!./.vscode/**",
		"!./bin/**",
		"!./dist/**",
		"!./node_modules/**",
		"!./tests/**",
		"!./src/**",
		"!./.git/**",
		"!./.github/**",
		"!./.wordpress-org/**",
		"!./composer.json",
		"!./composer.lock",
		"!./gulpfile.js",
		"!./package.json",
		"!./package-lock.json",
		"!./webpack.config.js",
		"!./README.md",
		"!./LICENSE",
		"!./.gitignore",
		"!./.distignore",
		"!./.phpcs.xml",
		"!./.phpunit.xml",
		"!./.svnignore",
		"!./.wp-env.json",
	  ], {
		base: './',
		allowEmpty: true,
	  }
	).pipe(gulp.dest("dist/vk-block-patterns"));
  });