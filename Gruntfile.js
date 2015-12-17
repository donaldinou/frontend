module.exports = function(grunt) {
    // load automatically grunt modules
    require('load-grunt-tasks')(grunt);

    // plugins configuration
    var config = {
        cmp: grunt.file.readJSON('composer.json'),
        bwr: grunt.file.readJSON('.bowerrc'),
        pkg: grunt.file.readJSON('package.json'),
        aws: grunt.file.readJSON('aws-credentials.json'),
        clean: {
            tmp: {
                src: ['.tmp']
            },
            cache: {
                src: ['app/cache/*', '!app/cache/.gitkeep']
            },
            logs: {
                src: ['app/logs/*', '!app/logs/.gitkeep']
            },
            built: {
                src: ['<%= cmp.extra["symfony-web-dir"] %>/built']
            },
            bundles: {
                src: ['<%= cmp.extra["symfony-web-dir"] %>/bundles']
            },
            assets: {
                src: ['<%= cmp.extra["symfony-assets-dir"] %>/**']
            },
            sass: {
                src: ['<%= cmp.extra["symfony-assets-dir"] %>/sass']
            },
            sasscache: {
                src: ['.sass-cache']
            },
        },
        shell: {
            clearCache: {
                options: {
                    stdout: true
                },
                command: 'php app/console cache:clear --no-debug'
            },
            assetsInstall: {
                options: {
                    stdout: true
                },
                command: 'php app/console assets:install --no-debug'
            },
            composerUpdate: {
                options: {
                    stdout: true
                },
                command: 'composer update --optimize-autoloader  --no-dev'
            },
            composerInstall: {
                options: {
                    stdout: true
                },
                command: 'composer install --optimize-autoloader --no-dev'
            },
            composerDump: {
                options: {
                    stdout: true
                },
                command: 'composer dump-autoload --optimize --no-dev'
            },
            sitemapDump: {
                options: {
                    stdout: true
                },
                command: 'php app/console presta:sitemap:dump'
            },
            copyZeroclipboard: {
                options: {
                    stdout: true
                },
                command: 'cp bower_components/zeroclipboard/dist/ZeroClipboard.swf web/built/zeroclipboard.swf'
            }
        },
        copy: {
            favicon: {
                src: '<%= cmp.extra["symfony-bundles-dir"] %>/vitelogecore/images/favicon/favicon.ico',
                dest: '<%= cmp.extra["symfony-web-dir"] %>/favicon.ico'
            },
            zeroclipboard: {
                src: '<%= cmp.extra["bwr.directory"] %>/zeroclipboard/dist/ZeroClipboard.swf',
                dest: '<%= cmp.extra["symfony-web-dir"] %>/built/zeroclipboard.swf',
                options: {
                    NoProcess: true
                }
            },
            vitelogefonts: {
                files: [{
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-bundles-dir"] %>/vitelogecore/fonts/',
                    src: ['**'],
                    dest: '<%= cmp.extra["symfony-web-dir"] %>/fonts/'
                }]
            },
            fontawesomefonts: {
                files: [{
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-assets-dir"] %>/fonts/',
                    src: ['**'],
                    dest: '<%= cmp.extra["symfony-web-dir"] %>/fonts/'
                }]
            }
        },
        bowercopy: {
            options: {
                srcPrefix: '<%= bwr.directory %>',
                destPrefix: '<%= cmp.extra["symfony-assets-dir"] %>'
            },
            scripts: {
                files: {
                    'js/jquery.js': 'jquery/dist/jquery.js',
                    'js/cookie.jquery.js': 'jquery.cookie/jquery.cookie.js',
                    'js/jquery.lazyload.js': 'jquery.lazyload/jquery.lazyload.js',
                    'js/jquery.smooth-scroll.js': 'jquery-smooth-scroll/jquery.smooth-scroll.js',
                    'js/bootstrap.js': 'bootstrap-sass/assets/javascripts/bootstrap.js',
                    'js/require.js': 'requirejs/require.js',
                    'js/domReady.js': 'requirejs-domready/domReady.js',
                    'js/hinclude.js': 'hinclude/hinclude.js',
                    'js/history.js': 'html5-history-api/history.js',
                    'js/owl.carousel.js' : 'OwlCarousel2/dist/owl.carousel.js',
                    'js/background-check.js': 'background-check/background-check.js',
                    'js/select2.js': 'select2/dist/js/select2.js',
                    'js/select2.fr.js': 'select2/dist/js/i18n/fr.js',
                    'js/placeholders.js': 'placeholders/dist/placeholders.jquery.js',
                    'js/d3.js': 'd3/d3.js',
                    'js/zeroclipboard.js': 'zeroclipboard/dist/ZeroClipboard.js'
                }
            },
            stylesheets: {
                files: {
                    'css/normalize.css': 'normalize.css/normalize.css',
                    'css/font-awesome.css': 'fontawesome/css/font-awesome.min.css',
                    'css/select2.css': 'select2/dist/css/select2.min.css',
                    'css/owl.carousel.css' : 'OwlCarousel2/dist/assets/owl.carousel.css',
                }
            },
            fonts: {
                files: {
                    'fonts/FontAwesome.otf': 'fontawesome/fonts/FontAwesome.otf',
                    'fonts/fontawesome-webfont.eot': 'fontawesome/fonts/fontawesome-webfont.eot',
                    'fonts/fontawesome-webfont.svg': 'fontawesome/fonts/fontawesome-webfont.svg',
                    'fonts/fontawesome-webfont.ttf': 'fontawesome/fonts/fontawesome-webfont.ttf',
                    'fonts/fontawesome-webfont.woff': 'fontawesome/fonts/fontawesome-webfont.woff',
                    'fonts/fontawesome-webfont.woff2': 'fontawesome/fonts/fontawesome-webfont.woff2'
                }
            },
            demo: {
                files: {
                    'tmp/demo-page.css': 'hover/css/demo-page.css'
                }
            }
        },
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
        compass: {
            frontend: {
                options: {
                    sassDir: 'src/Viteloge/FrontendBundle/Resources/scss',
                    cssDir: '.tmp/css',
                    importPath: '<%= bwr.directory %>',
                    outputStyle: 'expanded',
                    noLineComments: true
                }
            },
            estimation: {
                options: {
                    sassDir: 'src/Viteloge/EstimationBundle/Resources/scss',
                    cssDir: '.tmp/css',
                    importPath: '<%= bwr.directory %>',
                    outputStyle: 'expanded',
                    noLineComments: true
                }
            }
        },
        cssmin: {
            combine: {
                options:{
                    report: 'min',
                    sourceMap: false,
                    keepSpecialComments: 0
                },
                files: {
                    '<%= cmp.extra["symfony-web-dir"] %>/built/viteloge.min.css': [
                        '.tmp/css/**/*.css',
                        '<%= cmp.extra["symfony-assets-dir"] %>/css/**/*.css',
                        '<%= cmp.extra["symfony-bundles-dir"] %>/vitelogecore/css/*.css'
                    ]
                }
            }
        },
        concat: {
            options: {
                separator: ';\n'
            },
            headjs: {
                src: [
                    '<%= cmp.extra["symfony-bundles-dir"] %>/fosjsrouting/js/router.js',
                    '<%= cmp.extra["symfony-bundles-dir"] %>/vitelogefrontend/js/modernizr.js',
                    '<%= cmp.extra["symfony-assets-dir"] %>/js/hinclude.js',
                    '<%= cmp.extra["symfony-assets-dir"] %>/js/jquery.js',
                ],
                dest: '<%= cmp.extra["symfony-web-dir"] %>/built/head.js',
            },
            globaljs: {
                src: [
                    '<%= cmp.extra["symfony-assets-dir"] %>/js/bootstrap.js',
                    '<%= cmp.extra["symfony-assets-dir"] %>/js/cookie.jquery.js',
                    '<%= cmp.extra["symfony-assets-dir"] %>/js/jquery.lazyload.js',
                    '<%= cmp.extra["symfony-assets-dir"] %>/js/jquery.smooth-scroll.js',
                    '<%= cmp.extra["symfony-assets-dir"] %>/js/select2.js',
                    '<%= cmp.extra["symfony-assets-dir"] %>/js/select2.fr.js',
                    '<%= cmp.extra["symfony-assets-dir"] %>/js/placeholders.js',
                    '<%= cmp.extra["symfony-assets-dir"] %>/js/owl.carousel.js',
                    '<%= cmp.extra["symfony-assets-dir"] %>/js/history.js',
                    '<%= cmp.extra["symfony-bundles-dir"] %>/bazingajstranslation/js/translator.min.js',
                    '<%= cmp.extra["symfony-bundles-dir"] %>/vitelogefrontend/js/viteloge.jquery.js',
                    '<%= cmp.extra["symfony-bundles-dir"] %>/vitelogefrontend/js/viteloge.js',
                ],
                dest: '<%= cmp.extra["symfony-web-dir"] %>/built/global.js'
            }
        },
        uglify: {
            options: {
                report: 'gzip',
                mangle: false,
                sourceMap: false,
                /*sourceMapName: '<%= cmp.extra["symfony-web-dir"] %>/built/app.map',*/
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            built: {
                files: {
                    '<%= cmp.extra["symfony-web-dir"] %>/built/head.min.js': [
                        '<%= cmp.extra["symfony-web-dir"] %>/built/head.js'
                    ],
                    '<%= cmp.extra["symfony-web-dir"] %>/built/global.min.js': [
                        '<%= cmp.extra["symfony-web-dir"] %>/built/global.js'
                    ]
                }
            },
            assets: {
                files: [{
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-assets-dir"] %>/js/',
                    src: ['**/*.js', '!*.min.js'],
                    rename: function(destBase, destPath) {
                        return destBase+destPath.replace('.js', '.min.js');
                    },
                    dest: '<%= cmp.extra["symfony-web-dir"] %>/built/assets/js/'
                }]
            },
            bundles: {
                files: [{
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-bundles-dir"] %>',
                    src: ['**/*.js', '!*.min.js'],
                    rename: function(destBase, destPath) {
                        return destBase+destPath.replace('.js', '.min.js');
                    },
                    dest: '<%= cmp.extra["symfony-web-dir"] %>/built/bundles/'
                }]
            }
        },
        watch: {
            css: {
                files: [
                    'src/**/*.scss'
                ],
                tasks: ['css', 'compress', 'aws_s3']
            },
            javascript: {
                files: [
                    'src/**/*.js'
                ],
                tasks: ['javascript', 'compress', 'aws_s3']
            },
            xliff: {
                files: [
                    'src/**/*.xliff',
                    'src/**/*.xlf'
                ],
                tasks: ['shell:clearCache']
            }
        },
        compress: {
            assets: {
                options: {
                    mode: 'gzip'
                },
                expand: true,
                cwd: '<%= cmp.extra["symfony-assets-dir"] %>/',
                rename: function(destBase, destPath) {
                    return destBase+destPath+'.gz';
                },
                src: ['**/*', '!**/*.gz'],
                dest: '<%= cmp.extra["symfony-assets-dir"] %>/'
            },
            built: {
                options: {
                    mode: 'gzip'
                },
                expand: true,
                cwd: '<%= cmp.extra["symfony-web-dir"] %>/built/',
                rename: function(destBase, destPath) {
                    return destBase+destPath+'.gz';
                },
                src: ['**/*', '!**/*.gz'],
                dest: '<%= cmp.extra["symfony-web-dir"] %>/built/'
            },
            bundles: {
                options: {
                    mode: 'gzip'
                },
                expand: true,
                cwd: '<%= cmp.extra["symfony-bundles-dir"] %>/',
                rename: function(destBase, destPath) {
                    return destBase+destPath+'.gz';
                },
                src: ['**/*', '!**/*.gz'],
                dest: '<%= cmp.extra["symfony-bundles-dir"] %>/'
            },
            fonts: {
                options: {
                    mode: 'gzip'
                },
                expand: true,
                cwd: '<%= cmp.extra["symfony-web-dir"] %>/fonts/',
                rename: function(destBase, destPath) {
                    return destBase+destPath+'.gz';
                },
                src: ['**/*', '!**/*.gz'],
                dest: '<%= cmp.extra["symfony-web-dir"] %>/fonts/'
            }
        },
        aws_s3: {
            options: {
                accessKeyId: '<%= aws.key %>',
                secretAccessKey: '<%= aws.secret %>',
                region: '<%= aws.region %>',
                uploadConcurrency: 10, // 10 simultaneous uploads
                downloadConcurrency: 10 // 10 simultaneous downloads
            },
            production: {
                options: {
                    bucket: '<%= aws.bucket %>',
                    //params: {
                    //    ContentEncoding: 'gzip',
                    //}
                },
                files: [{
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-assets-dir"] %>/css/',
                    src: ['**'],
                    dest: 'assets/css/',
                    action: 'upload',
                    params: {
                        CacheControl: 'max-age=604800, public',
                        Expires: new Date(Date.now() + 2600000000)
                    }
                },
                {
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-assets-dir"] %>/fonts/',
                    src: ['**'],
                    dest: 'fonts/',
                    action: 'upload',
                    params: {
                        CacheControl: 'max-age=604800, public',
                        Expires: new Date(Date.now() + 63072000000)
                    }
                },
                {
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-assets-dir"] %>/images/',
                    src: ['**'],
                    dest: 'assets/img/',
                    action: 'upload',
                    params: {
                        CacheControl: 'max-age=604800, public',
                        Expires: new Date(Date.now() + 31630000000)
                    }
                },
                {
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-assets-dir"] %>/js/',
                    src: ['**'],
                    dest: 'assets/js/',
                    action: 'upload',
                    params: {
                        CacheControl: 'max-age=604800, public',
                        Expires: new Date(Date.now() + 2600000000)
                    }
                },
                {
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-web-dir"] %>/built/',
                    src: ['**'],
                    dest: 'built/',
                    action: 'upload',
                    params: {
                        CacheControl: 'max-age=604800, public',
                        Expires: new Date(Date.now() + 2600000000)
                    }
                },
                {
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-bundles-dir"] %>',
                    src: ['**'],
                    dest: 'bundles/',
                    action: 'upload',
                    params: {
                        CacheControl: 'max-age=604800, public',
                        Expires: new Date(Date.now() + 2600000000)
                    }
                },
                {
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-web-dir"] %>/fonts/',
                    src: ['**'],
                    dest: 'fonts/',
                    action: 'upload',
                    params: {
                        CacheControl: 'max-age=604800, public',
                        Expires: new Date(Date.now() + 63072000000)
                    }
                },]
            }
        }
    };

    // init grunt config
    grunt.initConfig(config);

    // All tasks
    grunt.registerTask('css', ['shell:assetsInstall', 'bowercopy', 'copy', 'compass', 'cssmin']);
    grunt.registerTask('javascript', ['shell:assetsInstall', 'bowercopy', 'copy', 'concat', 'uglify']);
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('deployApp', [
        'shell:composerInstall',
        'shell:sitemapDump'
    ]);
    grunt.registerTask('deployCss', [
        'clean',
        'shell:composerInstall',
        'shell:assetsInstall',
        'bowercopy',
        'copy',
        'compass',
        'cssmin',
        'shell:copyZeroclipboard',
        'compress',
        'aws_s3'
    ]);
    grunt.registerTask('deployJs', [
        'clean',
        'shell:composerInstall',
        'shell:assetsInstall',
        'bowercopy',
        'copy',
        'concat',
        'uglify',
        'shell:copyZeroclipboard',
        'compress',
        'aws_s3'
    ])
    grunt.registerTask('deployAssets', [
        'clean',
        'shell:composerInstall',
        'shell:assetsInstall',
        'bowercopy',
        'copy',
        'compass',
        'cssmin',
        'concat',
        'uglify',
        'shell:copyZeroclipboard',
        'compress',
        'aws_s3'
    ]);
    grunt.registerTask('deploy', [
        'clean',
        'shell:composerInstall',
        'shell:assetsInstall',
        'bowercopy',
        'copy',
        'compass',
        'cssmin',
        'concat',
        'uglify',
        'shell:copyZeroclipboard',
        'compress',
        'aws_s3',
        'shell:sitemapDump'
    ]);
};
