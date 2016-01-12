(function() {
	
	/**
	 * Setup Character defaults options display.
	 */ 	
	function setCharacterDefaultOptions(e) {
		
		if (!e) {
			e = window.event;
		}
		
		var gold_selector = document.getElementById('gold_selector');
		
		//console.log(gold_selector);
		if ( scene_action_options.checked == true ) {
			//console.log('true'); 
			gold_selector.style.display = "block";
		} else {
			//console.log('false');
			gold_selector.style.display = "none";
		}
		
	}
	
	function itSelected() {

		if ( wpifw_gender_it.checked == true ) {
		
			//console.log("It Selected");
			wpifw_gender_male.checked = false;
			wpifw_gender_female.checked = false;
			
		} 

	}
	function maleSelected() {

		if ( wpifw_gender_male.checked == true ) {
		
			wpifw_gender_it.checked = false;
			
		} 

	}
	function femaleSelected() {

		if ( wpifw_gender_female.checked == true ) {
		
			wpifw_gender_it.checked = false;
			
		} 

	}

	var scene_action_options = document.getElementById('wpifw_has_gold');
	scene_action_options.addEventListener('click', setCharacterDefaultOptions);
	
	var wpifw_gender_it = document.getElementById('wpifw_gender_it'),
		wpifw_gender_male = document.getElementById('wpifw_gender_male'),
		wpifw_gender_female = document.getElementById('wpifw_gender_female');
		
	genderEmptyCheck();
	function genderEmptyCheck() {
		if ( wpifw_gender_it.checked == false && wpifw_gender_male.checked == false && wpifw_gender_female.checked == false ) {
			wpifw_gender_male.checked = true;
			wpifw_gender_female.checked = true;	
		}
	}
		
	wpifw_gender_it.addEventListener('click', itSelected);
	wpifw_gender_male.addEventListener('click', maleSelected);
	wpifw_gender_female.addEventListener('click', femaleSelected);
	
	var wpifw_alignment_friend = document.getElementById('wpifw_alignment_friend'),
		wpifw_alignment_foe = document.getElementById('wpifw_alignment_foe'),
		wpifw_alignment_neutral = document.getElementById('wpifw_alignment_neutral');
		
	wpifw_alignment_friend.addEventListener('click', alignmentEmptyCheck.bind( this ) );
	wpifw_alignment_foe.addEventListener('click', alignmentEmptyCheck.bind( this ) );
	wpifw_alignment_neutral.addEventListener('click', alignmentEmptyCheck.bind( this ) );
	
	alignmentEmptyCheck();
	
	function alignmentEmptyCheck( e ) {
		
		if ( e )
		e.target.blur();
		if ( wpifw_alignment_neutral.checked == false && wpifw_alignment_foe.checked == false && wpifw_alignment_friend.checked == false ) {
			wpifw_alignment_neutral.checked = true;
		}
	}
		
				
})();