
/* **********************************************
     Begin wpifw-admin-scene.js
********************************************** */

// ?? describe table design here ?? //

/*
*  Anonymous self invoking function
*
*
*  @type	function
*  @date	17/02/2015
*  @since	0.0.1
*
*  @requires	wpifw-admin.js
*  @param	
*  @return	
*/

(function() {
	

	
	// Get the main custom meta element that contains
	// all tables options selectors (ul > li elements)
	wpifw.custom_meta = document.getElementById( 'custom-meta' );
	
	// Get custom meta options list (ul element)
	wpifw.meta_options = document.getElementById( 'custom-meta-options' );
	
	// Instantiate new options object pass options list.
	new wpifw.CustomMetaOptions( wpifw.meta_options );
	
	// Get all tables within custom meta element
	wpifw.custom_meta_tables = wpifw.custom_meta.getElementsByTagName( 'table' );
	
	//	For each table within the custom meta tables array
	function hasClass(element, cls) {
		return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
	}	
	
	for ( var i = 0; i < wpifw.custom_meta_tables.length; i++ ) {
		
		if ( hasClass(wpifw.custom_meta_tables[i], 'AdminTable') ) {
			//	create a 'controller' AdminTable object and 
			//	run the prototypal init() function 
			new wpifw.AdminTable ( wpifw.custom_meta_tables[i] ).init();
		}
	}

})();