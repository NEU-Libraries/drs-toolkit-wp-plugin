module.exports = function(grunt) {
    'use strict';
    // Project configuration.
    grunt.initConfig({
        jasmine : {
            src : 'assets/**/*.js',
            options : {
                vendor: [
                    'node_modules/jquery/dist/jquery.js',
                    'assets/js/jwplayer/jwplayer.js',
                    'node_modules/jasmine-jquery/lib/jasmine-jquery.js'
                ],
                specs : 'specs/**/*.js',
            		templateOptions: {
            			coverage: "./specs/reports/coverage.json",
            			report: [
            				{
            					type: "html",
            					options: {
            						dir: "./specs/reports/html"
            					}
            				},
            				{
            					type: "lcov",
            					options: {
            						dir: "./specs/reports/lcov"
            					}
            				},
            				]
            			},
        junit: {
            path: './specs/results/',
            consolidate: true
        }
      }
  },
	coveralls: {
		options: {
			// dont fail if coveralls fails
			force: true
		},
		main_target: {
			src: "./specs/reports/lcov/lcov.info"
		}
	},
});
    grunt.loadNpmTasks('grunt-contrib-jasmine');
    grunt.loadNpmTasks('grunt-coveralls');
};
