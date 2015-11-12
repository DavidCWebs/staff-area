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
    'bower_components/jquery-validation/dist/jquery.validate.js',
    'public/js/confirm-as-read.js'
  ];

  var jsRegistration = [
    'public/js/registration.js',
    'public/js/table-filter.js',
    'bower_components/bootstrap-select/js/bootstrap-select.js',
    'bower_components/jquery-validation/dist/jquery.validate.js'
  ];

  var cssProduction = [
    'public/css/staff-area-public.css',
    'bower_components/bootstrap-select/dist/css/bootstrap-select.css',
  ];

  var bannerFiles = [
    'public/css/cw-staff-area.min.css',
    'public/js/cw-staff-area.min.js',
    'public/js/cw-staff-area-registration.min.js'
  ];

  // Project configuration.
  // ---------------------------------------------------------------------------
  grunt.initConfig({

  pkg: grunt.file.readJSON('package.json'),
  banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
  '<%= grunt.template.today("yyyy-mm-dd") %>.' +
  'By David Egan: http://carawebs.com */',

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
      dest: 'public/css/cw-combined-staff-area.css',
    }
  },
  // Uglify the JS
  // ---------------------------------------------------------------------------
  uglify: {
    public: {
      files: {
        'public/js/cw-staff-area.min.js': 'public/js/cw-staff-area.js',
        'public/js/cw-staff-area-registration.min.js': 'public/js/cw-staff-area-registration.js',
      }
    }
  },
  // Minify CSS. Grunt task runs this after uncss
  // ---------------------------------------------------------------------------
  cssmin: {
    options: {
      banner: '<%= banner %>'
    },
    combine: {
      files: {
        'public/css/cw-staff-area.min.css': [cssProduction]
      }
    }
  },
  usebanner: {
    taskName: {
      options: {
        position: 'top',
        banner: '<%= banner %>',
        linebreak: true
      },
      files: {
        src: [ bannerFiles ]
      }
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
  //'concat:css',
  'cssmin',
  'uglify:public',
  'usebanner'
]);

// Default Grunt task
grunt.registerTask('default', 'build');

};
