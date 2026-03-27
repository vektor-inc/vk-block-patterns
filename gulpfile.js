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
		// Gulp 5 ではデフォルトの encoding が utf8 に変更されたため、
		// バイナリファイル（画像など）が破損する。encoding: false を指定して回避する。
		encoding: false,
	  }
	).pipe(gulp.dest("dist/vk-block-patterns"));
  });