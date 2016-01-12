(function() {
	
	/**
	 * Setup Event Listeners.
	 */ 
	var scene_action_options = document.getElementById('scene-action-options');
	scene_action_options.addEventListener('click', setSceneOptions);
	
	var add_choice = document.getElementById('add_choice');
	add_choice.addEventListener('click', addChoiceOptions);
	
	var deleteChoice = document.getElementById('delete-choice');
	deleteChoice.addEventListener("click", dispatchDelete);
	
	var add_combatant = document.getElementById('add_combatant');
	add_combatant.addEventListener('click', addCombatantOptions);
	
	var combatant_table = document.getElementById('combatant-table');
	combatant_table.addEventListener("change", removeDuplicateCombatants);
	
	var deleteCombatant = document.getElementById('delete-combatant');
	deleteCombatant.addEventListener("click", dispatchDelete);
	
	function dispatchDelete(e) {
		
		wpifw.table.deleteRow( e );
		
	}
	
	/**
	 * Setup Scene options display.
	 */ 
	function setSceneOptions(e) {
		
		if (!e) {
			e = window.event;
		}
		
		var radioClicked = e.target.value,
			no_options = document.getElementById('no-options'),	
			choice_options = document.getElementById('choice-options'),
			fight_options = document.getElementById('fight-options'),
			luck_options = document.getElementById('luck-options');
		
		toggleSceneOptions(radioClicked);
		
		function toggleSceneOptions(radioClicked) {

			if ( radioClicked !== 0 ) {
			
				choice_options.style.display = "none";
				fight_options.style.display = "none";
				luck_options.style.display = "none";
				no_options.style.display = "none";
			
			}
			
			if (radioClicked === "choice") {
				
				choice_options.style.display = "block";
				
			} else if (radioClicked === "fight") {
				
				fight_options.style.display = "block";
				
			} else if (radioClicked === "luck") {
				
				luck_options.style.display = "block";
				
			} else if (radioClicked === "none") {
				
				no_options.style.display = "block";
				
			} else {	
				
				e.preventDefault();
			}
		
		}
	}
	
	function addChoiceOptions(e) {
		
		var destination_table = document.getElementById('destination-table');
		destination_table.style.display = "block";
		
		var delete_itm = document.getElementById('delete-choice');
		var  delete_cln =  delete_itm.cloneNode(true);
		
		// Delete the footer...
		//destination_table.deleteTFoot();
		destination_table.deleteTHead();
		
		// Insert a row in the table at row index 0
		var newRow   = destination_table.insertRow(-1);
		
		// Insert a cell in the row at index 0
		var newCell0  = newRow.insertCell(0);
		var newCell1  = newRow.insertCell(1);
		var newCell2  = newRow.insertCell(2);
		
		var field0 = document.createElement("input");
		var field1 = document.createElement("input");
		
		var itm = document.getElementById('destination-dropdown');
		var cln = itm.cloneNode(true);
		
		field0.type="checkbox";
		field1.type="text";
		field1.name="choice[]";
		
		newCell0.appendChild(field0);
		newCell1.appendChild(field1);
		newCell2.appendChild(cln);
		
		var recreateHeader = destination_table.createTHead();
		
		var headerRow = recreateHeader.insertRow();
		var headerCell0  = document.createElement("th");
		headerRow.appendChild(headerCell0);
		var headerCell1  = document.createElement("th");
		headerRow.appendChild(headerCell1);
		var headerCell2  = document.createElement("th");
		headerRow.appendChild(headerCell2);
		
		var headerField1 = headerCell1.innerHTML = "Question / Statement";
		var headerField2 = headerCell2.innerHTML = "Destination Scene";
		
		headerCell0.appendChild(delete_cln);
		var deleteChoice = document.getElementById('delete-choice');
		deleteChoice.addEventListener("click", dispatchDelete);
		
		headerCell0.style.width = "2.2em";
		headerCell1.style.width = "80%";
		headerCell2.style.width = "20%";
		  
		e.preventDefault();
		
		e.target.blur();
		
	}
	

	function addCombatantOptions(e) {
		
		var combatant_table = document.getElementById('combatant-table');
		var numRows = combatant_table.getElementsByTagName('tr');
		wpifw.table.rows = numRows.length -1;
		
		combatant_table.style.display = "block";
		
		// Delete the footer...
		//destination_table.deleteTFoot();
		var delete_itm = document.getElementById('delete-combatant');
		var  delete_cln =  delete_itm.cloneNode(true);
		
		combatant_table.deleteTHead();
		
		// Insert a row in the table at row index 0
		var newRow   = combatant_table.insertRow(-1);
		
		// Insert a cell in the row at index 0
		var newCell0  = newRow.insertCell(0);
		var newCell1  = newRow.insertCell(1);
		
		var field0 = document.createElement("input");
		
		var itm = document.getElementById('combatant-dropdown');
		var cln = itm.cloneNode(true);
		cln.disabled = false;
		cln.selectedIndex = 0;
		cln.name = 'combatants[]'; 
		cln.className = "combatant combatant" + wpifw.table.rows; 
		
		field0.type="checkbox";
		
		newCell0.appendChild(field0);
		newCell1.appendChild(cln);
		
		var recreateHeader = combatant_table.createTHead();
		
		var headerRow = recreateHeader.insertRow();
		var headerCell0  = document.createElement("th");
		headerRow.appendChild(headerCell0);
		var headerCell1  = document.createElement("th");
		headerRow.appendChild(headerCell1);
		
		//var headerField0 = document.createElement("input");
		var headerField1 = headerCell1.innerHTML = "Combatants";
		
		headerCell0.appendChild(delete_cln);
		var deleteCombatant = document.getElementById('delete-combatant');
		deleteCombatant.addEventListener("click", dispatchDelete);
		
		//headerField0.type = "submit";
		headerCell0.style.width = "2.2em";
		headerCell1.style.width = "100%";
		  
		e.preventDefault();
		
		e.target.blur();
		
	}
	
	function removeDuplicateCombatants(e) {
		
		var selectionData = document.getElementsByClassName("combatant"),
			numCombatants = selectionData.length;

		for (var i = 0; i < numCombatants; i ++ ) {
			//TODO : need to check browser support as replace seems not good.... using classList instead for now		
			//selectionData[i].className.replace( /(?:^|\s)MyClass(?!\S)/g , '' );
			selectionData[i].classList.remove('duplicate');
		}
		warningMessages = document.getElementsByClassName("duplicateMessage");
		for ( var i = 0; i < warningMessages.length; i++ ) {
			warningMessages[i].parentNode.removeChild(warningMessages[i]);
		}
		
		toObject(selectionData); 
		function toObject(arr) {
		  var rv = {};
		  for (var i = 0; i < arr.length; ++i)
			rv[i] = arr[i];
		  return rv;
		}
		
		dataView = {};
		for (var i = 0; i < numCombatants; i++ ) {
			dataView[i] = selectionData[i].options[selectionData[i].selectedIndex].value; 
		}
		
		var splitClass = e.target.className;
		var currentClass = splitClass.split("combatant");
		var combatantChanged = parseInt( currentClass[2] );
		var newCombatant = dataView[combatantChanged];
		
		if ( newCombatant === 'Random...' ) {
			newRandom(combatantChanged, e.target);
		}
		
		var i = 0;
		
		for (i in dataView) {
			if ( dataView[i] == newCombatant ) {
				if ( combatantChanged != i ) {
					if ( newCombatant === 'Please select...' || newCombatant === 'Random...' ) {
					} else {
						var duplicateCharacterClass = "combatant" + combatantChanged;
						var duplicateCharacter = document.getElementsByClassName(duplicateCharacterClass);
						
						if ( duplicateCharacter[0].className.match(/(?:^|\s)duplicate(?!\S)/) ) {
						} else {
							duplicateCharacter[0].className = duplicateCharacter[0].className + " duplicate";
							var message = document.createElement("div");
							message.className = "duplicateMessage";
							var messagetext = document.createTextNode("Duplicate character");
							message.appendChild(messagetext);
							var rowOfDuplicate = duplicateCharacter[0].parentNode;
							rowOfDuplicate.appendChild(message);
						}
					}
				}
			}
			i++
		}
	} // End removeDuplicateCombatants(e);
	
	if (typeof String.prototype.startsWith != 'function') {

		String.prototype.startsWith = function (str){
  
			return this.indexOf(str) == 0;
		
		};
	}
	
	function newRandom(combatantChanged, el) {
		
		//console.log("new random at " + combatantChanged + " on row " + el);
		
		var rdm_itm = document.getElementById('randomClass');
		var rdm_cln =  rdm_itm.cloneNode(true);
		
		randomClassOptions = rdm_cln.options.length;
		
		for ( var i = 0; i < randomClassOptions; i++ ) {
			if ( i != 0 ) {

				// if ( random prepend doesn't exist - prepend it ) {
				
				if ( rdm_cln.options[i].value.startsWith("random-") ) {
					//console.log("already starts with random");
				} else {
					rdm_cln.options[i].value = "random-" + rdm_cln.options[i].value;
				}
			}
		}
		
		randomRow = el.parentNode;
		
		el.disabled = true;
		el.name = "random";
		
		randomRow.appendChild(rdm_cln);
		rdm_cln.style.display = "block";
	}
		
	
	
				
})();