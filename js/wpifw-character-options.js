(function() {
	
	/**
	 * Setup Character Options display.
	 */
	 
	 
	 
	var defaultsYes = document.getElementById('wpifw_use_defaults_yes'),
		classYes = document.getElementById('wpifw_use_class_yes'),
		use_stat_defaults = document.getElementById('use_stat_defaults'),
		classRow = document.getElementById('classRow'),
		characterStats =  document.getElementById('character-stats');
	 
	var hasGold = document.getElementById('wpifw_has_gold');
	hasGold.addEventListener('click', setGold);
	 
	if ( defaultsYes ) {
		defaultsYes.addEventListener( 'change' , defaultsChanged );
		//defaultsNo.addEventListener( 'change' , defaultsChanged );
	}
	
	if ( classYes ) {
		classYes.addEventListener( 'change' , classChanged );
		//defaultsNo.addEventListener( 'change' , defaultsChanged );
	}
	 
	function classChanged() {
		if ( classYes.checked ) {
			classRow.style.display = 'table-row';
			use_stat_defaults.style.display = 'table-row';
		} else {
			classRow.style.display = 'none';
			use_stat_defaults.style.display = 'none';
			characterStats.style.display = 'table';
			defaultsYes.checked = false;
		}
	}

	function defaultsChanged() {
		if ( defaultsYes.checked ) {
			characterStats.style.display = 'none';
		} else {
			characterStats.style.display = 'table';
		}
	}
	
	function setGold(e) {
		
		if (!e) {
			e = window.event;
		}
		
		var gold_selector = document.getElementById('gold_selector');
		
		//console.log(gold_selector);
		if ( hasGold.checked == true ) {
			//console.log('true'); 
			gold_selector.style.display = "table-row";
		} else {
			//console.log('false');
			gold_selector.style.display = "none";
		}
		
	}


})();