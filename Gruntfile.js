'use strict';
module.exports = function(grunt) {

  grunt.initConfig({
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      all: [
        'Gruntfile.js',
        'assets/js/ajax-solr/*.js',
        '!assets/js/ajax-solr/ajax-solr.min.js',
        '!assets/js/ajax-solr/imagesloaded.pkgd.min.js',
        '!assets/js/ajax-solr-scripts.min.js'
      ]
    },
    uglify: {
      dist: {
        files: {
          'assets/js/ajax-solr-scripts.min.js': [
            'assets/js/ajax-solr/ajax-solr.min.js',
            'assets/js/ajax-solr/AjaxSolrManager.js',
            'assets/js/ajax-solr/CurrentSearchWidget.js',
            'assets/js/ajax-solr/ResultWidget.js',
            'assets/js/ajax-solr/SolrPagerWidget.js',
            'assets/js/ajax-solr/TagCloudWidget.js',
            'assets/js/ajax-solr/TextWidget.js'
          ]
        },
        options: {
          // JS source map: to enable, uncomment the lines below and update sourceMappingURL based on your install
          // sourceMap: 'assets/js/scripts.min.js.map',
          // sourceMappingURL: '/app/themes/roots/assets/js/scripts.min.js.map'
        }
      }
    },
    watch: {
      js: {
        files: [
          '<%= jshint.all %>'
        ],
        tasks: ['jshint', 'uglify']
      },
      livereload: {
        // Browser live reloading
        // https://github.com/gruntjs/grunt-contrib-watch#live-reloading
        options: {
          livereload: false
        },
        files: [
          'js/ajax-solr/scripts.min.js',
          '*.php'
        ]
      }
    },
    clean: {
      dist: [
        'assets/js/ajax-solr-scripts.min.js'
      ]
    }
  });

  // Load tasks
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Register tasks
  grunt.registerTask('default', [
    'jshint',
    'clean',
    'uglify'
  ]);

  grunt.registerTask('dev', [
    'watch'
  ]);

};


