module.exports = function(grunt) {
    // load automatically grunt modules
    require('load-grunt-tasks')(grunt);

    // plugins configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        bowercopy: { // download with bower and copy necessary files to web/assets/*
            options: {
                srcPrefix: 'bower_components',
                destPrefix: 'web/assets'
            },
            scripts: {
                files: {
                    'js/jquery.js': 'jquery/dist/jquery.js',
                    //'js/bootstrap.js': 'bootstrap/dist/js/bootstrap.js'
                }
            },
            stylesheets: {
                files: {
                    //'css/bootstrap.css': 'bootstrap/dist/css/bootstrap.css',
                    //'css/font-awesome.css': 'font-awesome/css/font-awesome.css'
                }
            },
            fonts: {
                files: {
                    //'fonts': 'font-awesome/fonts'
                }
            }
        }, //end bowercopy
        compass: { // compass compilation
            sass: {
                options: {
                    sassDir: 'src/Viteloge/FrontendBundle/Resources/scss',
                    cssDir: '.tmp/css',
                    importPath: 'app/components',
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
                    'web/built/min.css': [
                        '.tmp/css/**/*.css'
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
        uglify: { // uglugy files
            options: {
                mangle: false,
                sourceMap: true,
                sourceMapName: 'web/built/app.map'
            },
            dist: {
                files: {
                  'web/built/app.min.js':[
                    'app/components/jquery/jquery.js',
                    'app/components/bootstrap-sass-official/asset/javascripts/bootstrap.js'
                    //'.tmp/js/**/*.js'
                  ]
                }
            }
        }, // end uglify
        watch: {
            css: {
                files: ['src/Ddms/*/Resources/scss/**/*.scss'],
                tasks: ['css']
            },
            javascript: {
                files: ['src/Ddms/*/Resources/public/js/*.js'],
                tasks: ['javascript']
            }
        }, // end watch
        copy: {
            dist: {
                files: [{
                    expand: true,
                    cwd: 'app/components/font-awesome/fonts',
                    dest: 'web/fonts',
                    src: ['**']
                }]
            }
        },
        aws: grunt.file.readJSON('aws-credentials.json'),
        s3: {
            options: {
                key: '<%= aws.key %>',
                secret: '<%= aws.secret %>',
                bucket: '<%= aws.bucket %>'
            },
            cdn: {
                upload: [
                    {
                        src: 'web/assets/css/*',
                        dest: 'css/'
                    },

                    {
                        src: 'web/assets/fonts/*',
                        dest: 'fonts/'
                    },
                    {
                        src: 'web/assets/images/*',
                        dest: 'images/'
                    },
                    {
                        src: 'web/assets/js/*',
                        dest: 'js/'
                    }
                ]
            }
        }
    });

    // Déclaration des différentes tâches
    grunt.registerTask('default', ['bowercopy']);
    //grunt.registerTask('default', ['css','javascript']);
    //grunt.registerTask('css', ['compass','cssmin']);
    //grunt.registerTask('javascript', ['dart2js', 'uglify']);
    //grunt.registerTask('cp', ['copy']);
};
