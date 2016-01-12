wpifw.table = {
	
	rows : 0,

};

wpifw.table.emptyTableCheck = function( parentTable ) {
	
	var tableRows = parentTable.getElementsByTagName("tr");
	
	if ( tableRows.length > 1 ) {
		
		for ( var i = 0; i < tableRows.length; i ++ ) {
			inc = i -1;
			if (i != 0 ) {
				var combatantDropdown = tableRows[i].getElementsByTagName("select");
				combatantDropdown[0].className = "combatant combatant" + inc;
			}
	
		}
	
	} else {
		
		//console.log( 'table empty' );
		//console.log( 'add row' );
		
	}
	
}

wpifw.table.deleteRow = function( e ) { 

	e.preventDefault();
	
	var parentTable = e.target.parentNode.offsetParent;
	
	var rows = parentTable.getElementsByTagName("tr"); //or document.forms[0].elements;
	var inputs = 		parentTable.getElementsByTagName("input"); //or document.forms[0].elements;
	
	var numRows = rows.length;
	
	var rowsToDelete = [];
	
	for ( var i = 0; i < numRows ; i++ ) {
		
		//console.log( 'checking row ' + i );
		
		var checkboxesInRow = rows[i].getElementsByTagName("input");
		
		//Is there a checkbox in the row?
		if ( checkboxesInRow.length > 0 && checkboxesInRow[0].checked ) {
			
			rowsToDelete.push(i);
			
		}
		
	}
	rowsToDelete.sort(function(a, b){return b-a});
	
	for (var i = 0; i < rowsToDelete.length; i++ ) {
		
		//if last row create clone before
		parentTable.deleteRow(rowsToDelete[i])
		wpifw.table.rows--;
		
	}
	
	e.target.blur();

	wpifw.table.emptyTableCheck( parentTable );
	
	
		
}