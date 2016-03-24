module.exports = function(grunt) {
    'use strict';
    // Project configuration.
    grunt.initConfig({
        jasmine : {
            src : 'assets/**/*.js',
            options : {
                specs : 'specs/**/*.js'
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-jasmine');
};