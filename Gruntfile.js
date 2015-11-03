module.exports = function(grunt) {

  // load all grunt tasks
  // ---------------------------------------------------------------------------
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

  // Array of jquery, Bootstrap and custom JS - to be concatenated and minified to a single file
  // ---------------------------------------------------------------------------
  var jsPublicFileList = [
    //'bower_components/jquery.fitvids/jquery.fitvids.js',
    //'public/js/fitvids-control.js'
    'public/js/table-filter.js',
    'bower_components/bootstrap-select/js/bootstrap-select.js',
    'bower_components/jquery-validation/dist/jquery.validate.js'
  ];

  var jsRegistration = [
    'public/js/registration.js',
    'public/js/table-filter.js',
    'bower_components/bootstrap-select/js/bootstrap-select.js',
    'bower_components/jquery-validation/dist/jquery.validate.js'
  ];

  var cssProduction = [
    'public/css/staff-area-public.css',
    'bower_components/bootstrap-select/dist/css/bootstrap-select.css'
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
      dest: 'public/js/cw-staff-area.js',
    },
    registration: {
      src: [jsRegistration],
      dest: 'public/js/cw-staff-area-registration.js',
    },
    css: {
      src: [cssProduction],
      dest: 'public/css/cw-staff-area-css.js',
    }
  },
  // Uglify the JS
  // ---------------------------------------------------------------------------
  uglify: {
    public: {
      files: {
        'public/js/cw-staff-area.min.js': 'public/js/cw-staff-area.js',
        'public/js/cw-staff-area-registration.min.js': 'public/js/cw-staff-area-registration.js',
        'public/js/cw-staff-area.min.css': 'public/js/cw-staff-area.css'
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
  'concat:public',
  'concat:registration',
  'uglify:public'
]);

// Default Grunt task
grunt.registerTask('default', 'build');

};
