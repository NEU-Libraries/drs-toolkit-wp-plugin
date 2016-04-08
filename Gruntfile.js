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
                junit: {
                    path: './specs/results/',
                    consolidate: true
                }
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-jasmine');
};