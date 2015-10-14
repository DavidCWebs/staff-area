module.exports = function(grunt) {

  // load all grunt tasks
  // ---------------------------------------------------------------------------
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

  // Array of jquery, Bootstrap and custom JS - to be concatenated and minified to a single file
  // ---------------------------------------------------------------------------
  var jsPublicFileList = [
    'bower_components/jquery.fitvids/jquery.fitvids.js',
    'bower_components/jquery/dist/jquery.js'
    //'assets/js/custom/navbar-shrink.js',
  ];

  // Project configuration.
  // ---------------------------------------------------------------------------
  grunt.initConfig({

  pkg: grunt.file.readJSON('package.json'),

  // Concat task
  // ---------------------------------------------------------------------------
  concat: {
    options: {
      separator: ';',
    },
    public: {
      src: [jsPublicFileList],
      dest: 'public/js/staff-area.js',
    },
  },
  // Uglify the JS
  // ---------------------------------------------------------------------------
  uglify: {
    public: {
      files: {
        'public/js/staff-area.min.js': 'public/js/staff-area.js'
      }
    }
  },
  // Sass task
  // ---------------------------------------------------------------------------
  sass: {
    development: {
      options: {
        //sourcemap: true
      },
      files: {
        'css/main.css': 'css/_main.scss'
      }
    },
    production: {
      options: {
        //sourcemap: true
      },
      files: {
        'css/main.css': 'css/_main.scss'
      }
    }
  },

  // Minify CSS. Grunt task runs this after uncss
  // ---------------------------------------------------------------------------
  cssmin: {
    options: {
      banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
      '<%= grunt.template.today("yyyy-mm-dd") %>.' +
      'By David Egan: http://carawebs.com */'
    },
    my_target: {
      src: 'css/main.un.css',
      dest: 'css/main.min.css'
    }
  },
  // Copy Task
  // ---------------------------------------------------------------------------
  copy: {
    css : {
      files: {
        '_site/css/main.css': 'css/main.css'
      }
    },
    fonts: {
      files: [{
        cwd: 'bower_components/font-awesome/fonts',  // set working folder / root to copy
        src: '**/*',           // copy all files and subfolders
        dest: '_site/fonts',   // destination folder
        expand: true           // required when using cwd
      }]
    },
  },






});

// Register Grunt Tasks
// -----------------------------------------------------------------------------
grunt.registerTask('sassCopy', ['sass:development', 'copy:css']);

grunt.registerTask('build', [
  //'sass:production',
  //'cssmin',
  'concat',
  'uglify:public'
]);

// Default Grunt task
grunt.registerTask('default', 'build');

};
