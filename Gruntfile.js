module.exports = function(grunt) {
    // load automatically grunt modules
    require('load-grunt-tasks')(grunt);

    // plugins configuration
    var config = {
        cmp: grunt.file.readJSON('composer.json'),
        bwr: grunt.file.readJSON('.bowerrc'),
        pkg: grunt.file.readJSON('package.json'),
        aws: grunt.file.readJSON('aws-credentials.json'),
        //hub: {
        //    all: {
        //        files: {
        //            src: [
        //                'src/*/Gruntfile.js',
        //                'vendor/*/Gruntfile.js'
        //            ]
        //        }
        //    }
        //},
        shell: {
            clearCache: {
                options: {
                    stdout: true
                },
                command: 'php app/console cache:clear'
            }
        },
        bowercopy: { // download with bower and copy necessary files to web/assets/*
            options: {
                srcPrefix: '<%= bwr.directory %>',
                destPrefix: '<%= cmp.extra["symfony-assets-dir"] %>'
            },
            scripts: {
                files: {
                    'js/jquery.js': 'jquery/dist/jquery.js',
                    'js/cookie.jquery.js': 'jquery.cookie/jquery.cookie.js',
                    'js/typeahead.js': 'typeahead.js/dist/typeahead.bundle.js',
                    'js/require.js': 'requirejs/require.js',
                    'js/domReady.js': 'requirejs-domready/domReady.js',
                    'js/hinclude.js': 'hinclude/hinclude.js',
                    'js/history.js': 'html5-history-api/history.js',
                    'js/owl.carousel.js' : 'OwlCarousel2/dist/owl.carousel.js',
                    'js/background-check.js': 'background-check/background-check.js',
                    'js/select2.js': 'select2/dist/js/select2.min.js',
                    'js/select2.fr.js': 'select2/dist/js/i18n/fr.js',
                    'js/placeholders.js': 'placeholders/dist/placeholders.jquery.js',
                    'js/jquery.lazyload.js': 'jquery.lazyload/jquery.lazyload.js',
                    'js/d3.js': 'd3/d3.js'
                }
            },
            stylesheets: {
                files: {
                    'css/normalize.css': 'normalize.css/normalize.css',
                    'css/font-awesome.css': 'fontawesome/css/font-awesome.min.css',
                    'css/select2.css': 'select2/dist/css/select2.min.css',
                    'css/owl.carousel.css' : 'OwlCarousel2/dist/assets/owl.carousel.css',
                }
            }//,
            /*fonts: {
                files: {
                    'fonts': 'font-awesome/fonts'
                }
            }*/
        }, //end bowercopy
        requirejs: {
            main: {
                options: {
                    mainConfigFile: '<%= cmp.extra["symfony-assets-dir"] %>/js/common.js',
                    appDir: '<%= cmp.extra["symfony-assets-dir"] %>',
                    baseUrl: '<%= cmp.extra["symfony-assets-dir"] %>/js',
                    dir: '<%= cmp.extra["symfony-assets-dir"] %>',
                    optimizeCss: "none", // it will use sass/compass instead
                    optimize: "none", // it will use uglify instead
                    modules: [
                        {
                            name: 'common',
                            include: ['jquery', 'domReady']
                        }
                    ]
                }
            }
        },
        compass: { // compass compilation
            sass: {
                options: {
                    sassDir: 'src/Viteloge/FrontendBundle/Resources/scss',
                    cssDir: '.tmp/css',
                    importPath: '<%= bwr.directory %>',
                    outputStyle: 'expanded',
                    noLineComments: true
                }
            }
        }, //end compass
        cssmin: { // css minify
            combine: {
                options:{
                    report: 'gzip',
                    keepSpecialComments: 0
                },
                files: {
                    '<%= cmp.extra["symfony-web-dir"] %>/built/min.css': [
                        '.tmp/css/**/*.css',
                        'web/assets/**/*.css'
                    ]
                }
            }
        }, //end cssmin
        dart2js: { // dart2js
            options: {
              // Task-specific options go here.
            },
            your_target: {
              // Target-specific file lists and/or options go here.
            },
        }, // end dart
        uglify: { // uglufy files
            options: {
                mangle: false,
                sourceMap: true,
                sourceMapName: '<%= cmp.extra["symfony-web-dir"] %>/built/app.map',
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            build: {
                files: [{
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-assets-dir"] %>/js/',
                    src: ['**/*.js'],
                    dest: '<%= cmp.extra["symfony-assets-dir"] %>/js/'
                }]
            }
        }, // end uglify
        watch: {
            css: {
                files: [
                    'src/**/*.scss'
                ],
                tasks: ['css']
            },
            javascript: {
                files: [
                    'src/**/*.js'
                ],
                tasks: ['javascript']
            },
            xliff: {
                files: [
                    'src/**/*.xliff',
                    'src/**/*.xlf'
                ],
                tasks: ['shell:clearCache']
            }
        }, // end watch
        copy: { // copy files
            dist: {
                files: [{
                    expand: true,
                    cwd: 'src/Viteloge/FrontendBundle/Resources/public/',
                    src: ['**'],
                    dest: '<%= cmp.extra["symfony-web-dir"] %>/bundles/frontendbundle/'
                }]
            }
        }, // end copy
        clean: {
            build: {
                src: ['<%= cmp.extra["symfony-assets-dir"] %>/**']
            },
            sass: {
                src: ['<%= cmp.extra["symfony-assets-dir"] %>/sass']
            }
        },
        s3: {
            options: {
                key: '<%= aws.key %>',
                secret: '<%= aws.secret %>',
                bucket: '<%= aws.bucket %>'
            },
            cdn: {
                upload: [
                    {
                        src: 'cmp.extra["symfony-assets-dir"]/css/*',
                        dest: 'css/'
                    },

                    {
                        src: 'cmp.extra["symfony-assets-dir"]/fonts/*',
                        dest: 'fonts/'
                    },
                    {
                        src: 'cmp.extra["symfony-assets-dir"]/images/*',
                        dest: 'images/'
                    },
                    {
                        src: 'cmp.extra["symfony-assets-dir"]/js/*',
                        dest: 'js/'
                    }
                ]
            }
        }
    };

    // init grunt config
    grunt.initConfig(config);

    // All tasks
    //grunt.registerTask('default', ['css','javascript']);
    grunt.registerTask('css', ['compass','cssmin']);
    grunt.registerTask('javascript', [/*'dart2js', */'uglify', 'copy']);
    grunt.registerTask('copy:assets', ['clean:build', 'copy', 'clean:sass']);
    grunt.registerTask('default', ['bowercopy']);
};
