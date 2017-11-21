module.exports = {

	build: {

		options : {
			banner : '/*! <%= app.name %> Wordpress Plugin v<%= app.version %> */ \n',
			preserveComments : 'some'
		},

		files: {
			'<%= app.jsPath %>/lib/jquery.cookie.min.js': [
				'<%= app.jsPath %>/lib/jquery.cookie.js'
			],
			'<%= app.jsPath %>/wishlist.min.js': [
				'<%= app.jsPath %>/wishlist.js'
			],
		}
	}
};