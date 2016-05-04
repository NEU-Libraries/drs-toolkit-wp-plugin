module.exports = function(grunt) {
    'use strict';
    // Project configuration.
    grunt.initConfig({
        jasmine: {
    pivotal: {
      src: "assets/**/*.js",
      options: {
        specs: "specs/**/*.js",
        junit: {
                    path: './specs/results/',
                    consolidate: true
                },
        template : require("grunt-template-jasmine-istanbul"),
        templateOptions: {
          coverage: "reports/coverage.json",
          report: [
            {
              type: "html",
              options: {
                dir: "reports/html"
              }
            },
            {
              type: "lcov",
              options: {
                dir: "reports/lcov"
              }
            },
          ]
        },
        vendor: [
          'node_modules/jquery/dist/jquery.js',
          'assets/js/jwplayer/jwplayer.js',
          'node_modules/jasmine-jquery/lib/jasmine-jquery.js'
        ]
      }
    }
  },
  coveralls: {
  options: {


    // dont fail if coveralls fails
    force: true
  },
  main_target: {
    src: "reports/lcov/lcov.info"
  }
},
    });
    grunt.loadNpmTasks("grunt-coveralls");
    grunt.loadNpmTasks("grunt-contrib-jasmine");
};
