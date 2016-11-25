module.exports = function(grunt) {
    // load automatically grunt modules
    require('load-grunt-tasks')(grunt);

    // plugins configuration
    var config = {
        cmp: grunt.file.readJSON('composer.json'),
        bwr: grunt.file.readJSON('.bowerrc'),
        pkg: grunt.file.readJSON('package.json'),
        cfg: grunt.file.readJSON('grunt-config.json'),
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
            suDeploymentUser: {
                options: {
                    stdout: true
                },
                command: 'sudo su <%= cfg["deploy-user"] %>'
            },
            exit: {
                options: {
                    stdout: true
                },
                command: 'exit'
            },
            gitPull: {
                options: {
                    stdout: true
                },
                command: 'git pull'
            },
            stopService: {
                options: {
                    stdout: true
                },
                command: 'service <%= cfg["apache-service"] %> stop && service <%= cfg["php-service"] %> stop'
            },
            startService: {
                options: {
                    stdout: true
                },
                command: 'service <%= cfg["apache-service"] %> start && service <%= cfg["php-service"] %> start'
            },
            composerSelfUpdate: {
                options: {
                    stdout: true
                },
                command: 'composer self-update'
            },
            npmNpmUpdate: {
                options: {
                    stdout: true
                },
                command: 'npm install npm@latest -g'
            },
            npmBowerUpdate: {
                options: {
                    stdout: true
                },
                command: 'npm update -g bower'
            },
            npmGruntUpdate: {
                options: {
                    stdout: true
                },
                command: 'npm update -g grunt-cli'
            },
            bowerUpdate: {
                options: {
                    stdout: true
                },
                command: 'bower update --allow-root'
            },
            applicationOwner: {
                options: {
                    stdout: true
                },
                command: 'chown -Rf <%= cfg["deploy-user"] %>:<%= cfg["deploy-user"] %> ./'
            },
            removeLogs: {
                options: {
                    stdout: true
                },
                command: 'rm -Rf app/logs/<%= cfg.environment %>.log'
            },
            rightsLogs: {
                options: {
                    stdout: true
                },
                command: 'chown -Rf <%= cfg["deploy-user"] %>:<%= cfg["deploy-group"] %> app/logs/ && chmod 775 app/logs/ && find app/logs/ -type f -exec chmod 664 {} \\;'
            },
            rightsSpool: {
                options: {
                    stdout: true
                },
                command: 'chown -Rf <%= cfg["deploy-user"] %>:<%= cfg["deploy-group"] %> app/spool/default/ && chmod 775 app/spool/default/ && find app/spool/default/ -type f -exec chmod 664 {} \\;'
            },
            rightsSitemap: {
                options: {
                    stdout: true
                },
                command: 'find web/ -type f \\( -name "*.xml" -o -name "*.xml.gz" \\) -exec chmod 664 {} \\; -exec chown <%= cfg["deploy-group"] %>:<%= cfg["deploy-user"] %> {} \\;'
            },
            removeCache: {
                options: {
                    stdout: true
                },
                command: 'rm -Rf app/cache/<%= cfg.environment %>_old && rm -Rf app/cache/<%= cfg.environment %>/*'
            },
            clearCache: {
                options: {
                    stdout: true
                },
                command: 'php app/console cache:clear --env=<%= cfg.environment %> <%= cfg["symfony-command-suffix"] %>'
            },
            rightsCache: {
                options: {
                    stdout: true
                },
                command: 'chown -Rf <%= cfg["deploy-user"] %>:<%= cfg["deploy-group"] %> app/cache/<%= cfg.environment %>/ && find app/cache/<%= cfg.environment %>/ -type d -exec chmod 775 {} \\; && find app/cache/<%= cfg.environment %>/ -type f -exec chmod 664 {} \\;'
            },
            assetsInstall: {
                options: {
                    stdout: true
                },
                command: 'php app/console assets:install --env=<%= cfg.environment %> <%= cfg["symfony-command-suffix"] %>'
            },
            composerUpdate: {
                options: {
                    stdout: true
                },
                command: 'export SYMFONY_ENV=<%= cfg.environment %> && composer update <%= cfg["composer-command-suffix"] %>'
            },
            composerInstall: {
                options: {
                    stdout: true
                },
                command: 'export SYMFONY_ENV=<%= cfg.environment %> && composer install <%= cfg["composer-command-suffix"] %>'
            },
            composerDump: {
                options: {
                    stdout: true
                },
                command: 'export SYMFONY_ENV=<%= cfg.environment %> && composer dump-autoload <%= cfg["composer-command-suffix"] %>'
            },
            jsRoutingDump: {
                options: {
                    stdout: true
                },
                command: 'php app/console fos:js-routing:dump --env=<%= cfg.environment %> --locale=fr__RG__'
            },
            sitemapDump: {
                options: {
                    stdout: true
                },
                command: 'php -d memory_limit=4000M app/console presta:sitemap:dump --gzip --env=<%= cfg.environment %>'
            },
            sitemapDumpDefault: {
                options: {
                    stdout: true
                },
                command: 'php -d memory_limit=4000M app/console presta:sitemap:dump --gzip --section=default --env=<%= cfg.environment %>'
            },
            sitemapDumpCities: {
                options: {
                    stdout: true
                },
                command: 'php -d memory_limit=4000M app/console presta:sitemap:dump --gzip --section=cities --env=<%= cfg.environment %>'
            },
            sitemapDumpAds: {
                options: {
                    stdout: true
                },
                command: 'php -d memory_limit=4000M app/console presta:sitemap:dump --gzip --section=ads --env=<%= cfg.environment %>'
            },
            sitemapDumpQueries: {
                options: {
                    stdout: true
                },
                command: 'php -d memory_limit=4000M app/console presta:sitemap:dump --gzip --section=queries --env=<%= cfg.environment %>'
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
                    //'css/font-awesome.css': 'fontawesome/css/font-awesome.min.css', // deprecated because it was used in frontendbundle
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
                    cssDir: '<%= cfg["compass-tmp-dir"] %>/css',
                    importPath: '<%= bwr.directory %>',
                    outputStyle: 'expanded',
                    noLineComments: true,
                    environment: 'production'
                }
            },
            estimation: {
                options: {
                    sassDir: 'src/Viteloge/EstimationBundle/Resources/scss',
                    cssDir: '<%= cfg["compass-tmp-dir"] %>/css',
                    importPath: '<%= bwr.directory %>',
                    outputStyle: 'expanded',
                    noLineComments: true,
                    environment: 'production'
                }
            }
        },
        cssmin: {
            combine: {
                options:{
                    report: 'min',
                    sourceMap: false,
                    keepBreaks: false,
                    keepSpecialComments: "*"
                },
                files: {
                    '<%= cmp.extra["symfony-web-dir"] %>/built/viteloge.min.css': [
                        '<%= cmp.extra["symfony-assets-dir"] %>/css/**/*.css',
                        '<%= cmp.extra["symfony-bundles-dir"] %>/vitelogecore/css/*.css',
                        '<%= cfg["compass-tmp-dir"] %>/css/**/*.css'
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
                    '<%= cmp.extra["symfony-web-dir"] %>/js/fos_js_routes.js',
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
            },
            js: {
                files: [{
                    expand: true,
                    cwd: '<%= cmp.extra["symfony-web-dir"] %>/js/',
                    src: ['**/*.js', '!*.min.js'],
                    rename: function(destBase, destPath) {
                        return destBase+destPath.replace('.js', '.min.js');
                    },
                    dest: '<%= cmp.extra["symfony-web-dir"] %>/built/js/'
                }]
            }
        },
        watch: {
            css: {
                files: [
                    'src/**/*.scss'
                ],
                tasks: ['css', 'compress']
            },
            javascript: {
                files: [
                    'src/**/*.js'
                ],
                tasks: ['javascript', 'compress']
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
            prod: {
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
                    cwd: '<%= cmp.extra["symfony-web-dir"] %>/js/',
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

    // monkey patch
    if (!grunt.task.exists) {
        grunt.task.exists = function exists (name) {
            return _.include(_.pluck(grunt.task._tasks, 'name'), name);
        };
    }

    // All tasks
    grunt.registerTask('applicationOwner', 'Command line to set owner application', function() {
        if (process.platform != 'win32') {
            grunt.task.run('shell:applicationOwner');
        } else {
            grunt.log.writeln('Unable to correctly set application owner on Windows system');
        }
    });
    grunt.registerTask('removeLogs', 'Command line to remove logs', function() {
        if (process.platform != 'win32') {
            grunt.task.run('shell:removeLogs');
        } else {
            grunt.log.writeln('Unable to correctly remove logs on Windows system');
        }
    });
    grunt.registerTask('rightsLogs', 'Command line to set rights on logs directory', function() {
        if (process.platform != 'win32') {
            grunt.task.run('shell:rightsLogs');
        } else {
            grunt.log.writeln('Unable to correctly set rights logs on Windows system');
        }
    });
    grunt.registerTask('rightsSpool', 'Command line to set rights on spool directory', function() {
        if (process.platform != 'win32') {
            grunt.task.run('shell:rightsSpool');
        } else {
            grunt.log.writeln('Unable to correctly set rights spool on Windows system');
        }
    });
    grunt.registerTask('rightsSitemap', 'Command line to set rights on sitemap files', function() {
        if (process.platform != 'win32') {
            grunt.task.run('shell:rightsSitemap');
        } else {
            grunt.log.writeln('Unable to correctly set rights sitemap on Windows system');
        }
    });
    grunt.registerTask('removeCache', 'Command line to remove cache', function() {
        if (process.platform != 'win32') {
            grunt.task.run('shell:removeCache');
        } else {
            grunt.log.writeln('Unable to correctly remove cache on Windows system');
        }
    });
    grunt.registerTask('rightsCache', 'Command line to set rights on cache directory', function() {
        if (process.platform != 'win32') {
            grunt.task.run('shell:rightsCache');
        } else {
            grunt.log.writeln('Unable to correctly set rights cache on Windows system');
        }
    });
    grunt.registerTask('rightsApplication', 'Command line to set rights on application', function() {
        grunt.task.run(['applicationOwner', 'rightsCache', 'rightsLogs', 'rightsSpool', 'rightsSitemap']);
    });
    grunt.registerTask('connectDeploymentUser', 'Connect as a deployment user', function() {
        var cfg = grunt.config.get('cfg');
        var environment = cfg.environment || 'dev';
        if (grunt.task.exists('shell')) {
            var shell = grunt.config.get('shell');
            if (shell['connectDeploymentUser']) {
                grunt.task.run(['shell:connectDeploymentUser']);
            }
        }
    });
    grunt.registerTask('disconnectDeploymentUser', 'Disconnect the deployment user', function() {
        var username = process.env.USER || process.env.USERNAME;
        var cfg = grunt.config.get('cfg');
        var deployUser = cfg["deploy-user"];
        if (username == deployUser) {
            grunt.task.run(['shell:exit']);
        }
    });
    grunt.registerTask('gruntForceTaskOn', 'Enable the force option', function() {
        if ( !grunt.option( 'force' ) ) {
            grunt.config.set('force_on', true);
            grunt.option( 'force', true );
        }
    });
    grunt.registerTask('gruntForceTaskRestore', 'Restore the force option', function() {
        if ( grunt.config.get('force_on') ) {
            grunt.option( 'force', false );
        }
    });
    grunt.registerTask('aws', 'Deploy to AWS S3', function() {
        var cfg = grunt.config.get('cfg');
        var environment = cfg.environment || 'dev';
        if (grunt.task.exists('aws_s3')) {
            var aws_s3 = grunt.config.get('aws_s3');
            if (aws_s3[environment]) {
                grunt.task.run(['aws_s3:'+environment]);
            }
        }
    });
    grunt.registerTask('css', ['bowercopy', 'copy', 'compass', 'cssmin', 'applicationOwner', 'rightsCache']);
    grunt.registerTask('javascript', ['bowercopy', 'copy', 'concat', 'applicationOwner', 'rightsCache']);
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('tools-update', 'shell:composerSelfUpdate', 'shell:npmNpmUpdate', 'shell:npmBowerUpdate', 'shell:npmGruntUpdate')
    grunt.registerTask('deployApp', [
        'shell:composerUpdate',
        'shell:composerInstall',
        'shell:jsRoutingDump',
        'gruntForceTaskOn',
        'shell:sitemapDump',
        'shell:sitemapDumpCities',
        'shell:sitemapDumpAds',
        'shell:sitemapDumpQueries',
        'gruntForceTaskRestore',
        'rightsApplication'
    ]);
    grunt.registerTask('deployCss', [
        'clean',
        'removeCache',
        'shell:composerUpdate',
        'shell:composerInstall',
        'shell:assetsInstall',
        'shell:jsRoutingDump',
        'bowercopy',
        'copy',
        'compass',
        'cssmin',
        'shell:copyZeroclipboard',
        'compress',
        'aws',
        'rightsApplication'
    ]);
    grunt.registerTask('deployJs', [
        'clean',
        'removeCache',
        'connectDeploymentUser',
        'shell:composerUpdate',
        'shell:composerInstall',
        'shell:assetsInstall',
        'shell:jsRoutingDump',
        'bowercopy',
        'copy',
        'concat',
        'uglify',
        'shell:copyZeroclipboard',
        'compress',
        'aws',
        'rightsApplication'
    ])
    grunt.registerTask('deployAssets', [
        'clean',
        'removeCache',
        'shell:composerUpdate',
        'shell:composerInstall',
        'shell:assetsInstall',
        'shell:jsRoutingDump',
        'bowercopy',
        'copy',
        'compass',
        'cssmin',
        'concat',
        'uglify',
        'shell:copyZeroclipboard',
        'compress',
        'aws',
        'rightsApplication'
    ]);
    grunt.registerTask('deploy', [
        'shell:stopService',
        'gruntForceTaskOn',
        'shell:gitPull',
        'gruntForceTaskRestore',
        'clean',
        'removeCache',
        'shell:composerUpdate',
        'shell:composerInstall',
        'shell:assetsInstall',
        'shell:jsRoutingDump',
        'bowercopy',
        'copy',
        'compass',
        'cssmin',
        'concat',
        'uglify',
        'shell:copyZeroclipboard',
        'compress',
        'aws',
        'rightsApplication',
        'disconnectDeploymentUser',
        'shell:startService',
        'gruntForceTaskOn',
        'shell:sitemapDump',
        'shell:sitemapDumpCities',
        'shell:sitemapDumpAds',
        'shell:sitemapDumpQueries',
        'gruntForceTaskRestore',
        'rightsApplication'
    ]);
};
