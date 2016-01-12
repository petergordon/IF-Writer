(function() {
	
	var fight_cb = document.getElementById("allow_fight"),
		classes_cb = document.getElementById("allow_classes"),
		classes_row = document.getElementById("classesRow"),
		characters_menu = document.getElementById("menu-posts-characters"),
		characters_defaults_menu = document.getElementById("menu-posts-character_defaults"),
		preface_cb = document.getElementById("allow_preface");
		preface = document.getElementById("preface-editor");
	
	
	
	if ( fight_cb ) {
		classesReveal();
		fight_cb.addEventListener( 'change', classesReveal );
	}
	
	function classesReveal() {
		if ( fight_cb.checked ) {
			classes_row.style.display = 'table-row';
		} else {
			classes_row.style.display = 'none';
			classes_cb.checked = false;
		}
	}
	
	if ( preface_cb ) {
		prefaceReveal();
		preface_cb.addEventListener( 'change', prefaceReveal );
	}
	
	function prefaceReveal() {
		if ( preface_cb.checked ) {	
			preface.style.display = 'block';
		} else {
			preface.style.display = 'none';
		}
		
	}
	

	
})();