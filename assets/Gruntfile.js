module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		sass: {
			dist: {
				files: {
					'../../../public/static/admin/default/css/main.css' : 'scss/main.scss'
				},
				options: {
					style: "compressed",
					sourcemap: "none"
				}
			}
		},
		concat: {
		    options: {
		    	separator: ';\n',
		    },
		    dist: {
		    	src: [
		    		'js/vendor/**/*.js', 
		    		'js/modules/**/*.js',
		    		'js/app.js'
		    	],
		    	dest: '../../../public/static/admin/default/js/app.js'
		    },
		},
		uglify: {
		    options: {
		      	mangle: {
		        	except: []
		      	}
		    },
		    my_target: {
		      	files: {
		        	'../../../public/static/admin/default/js/app.js': ['../../../public/static/admin/default/js/app.js']
		    	}
		    }
		},
  		watch: {
			css: {
				files: '**/*.scss',
				tasks: ['sass']
			},
			js: {
				files: '**/*.js',
				tasks: ['concat']
			}
		}
	});
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.registerTask('default',['watch']);
}
