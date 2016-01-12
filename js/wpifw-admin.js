
/* **********************************************
     Begin wpifw-admin.js
********************************************** */

/*
*  wpifw
*
*  Create namespace.
*  
*
*  @type	function
*  @date	16/02/2015
*  @since	0.0.1
*
*  @param	
*  @return	AdminTable
*  @return 	CustomMetaOptions
*/

var wpifw = (function() {
	
	/*
	*	AdminTable
	*
	*	New agnostic ( but preferred ) constructor function. e.g:
	*	wpifw.{name} = new wpifw.AdminTable ( {table element}, false ).init();
	*
	*	@type	function
	*	@date	16/02/2015
	*	@since	0.0.1
	*
	*	@param	table-element 
	*	@return	n/a
	*/
	
	function AdminTable ( table ) {
		
		"use strict";
		// reinvoke self using 'new' syntax to inherit from prototype
		// regardless of called as function or constructor.
		if (! ( this instanceof AdminTable ) ) {
			return new wpifw.AdminTable ( table );
		}
		
		//Private Vars

		//Public Vars
		this.table = table;
		this.tableBody = this.table.tBodies[0];
		this.tableRows = this.table.tBodies[0].rows;
		this.rows = 0;
		this.numberOfInitRows = this.tableRows.length;
		this.table_inputs = this.table.getElementsByTagName('input');
		this.table_selects = this.table.getElementsByTagName('select');	
		
	}
	
	/*
	*	init
	*
	*	Function should be run after an AdminTable object created. e.g:
	*	wpifw.{name} = new wpifw.AdminTable ( {table element} ).init();
	*
	*	@type	function
	*	@date	16/02/2015
	*	@since	0.0.1
	*
	*	@param	n/a
	*	@return	n/a
	*/
	
	AdminTable.prototype.init = function () {
		
		/* 
		*	The bind function is an addition to ECMA-262, 5th edition; as such it 
		*	may not be present in all browsers. The following code allows use of 
		*	much of the functionality of bind() in implementations that do not 
		*	natively support it.
		*
		*	@date	16/02/2015 
		*	@link : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Function/bind
		*/
		
		var i;
		
		if ( !Function.prototype.bind ) {
			Function.prototype.bind = function( oThis ) {
		
				if ( typeof this !== 'function' ) {
					// closest thing possible to the ECMAScript 5
					// internal IsCallable function
					throw new TypeError( 'Function.prototype.bind - what is trying to be bound is not callable' );
				}
				
				var aArgs   = Array.prototype.slice.call( arguments, 1 ),
				fToBind = this,
				fNOP    = function() {},
				fBound  = function() {
				  return fToBind.apply( this instanceof fNOP && oThis
						 ? this
						 : oThis,
						 aArgs.concat( Array.prototype.slice.call( arguments ) ) );
				};
				
				fNOP.prototype = this.prototype;
				fBound.prototype = new fNOP();
				
				return fBound;
			};
		}
		
		
		//	
		//	 		
		if (typeof String.prototype.startsWith != 'function') {

			String.prototype.startsWith = function (str){
				return this.indexOf(str) === 0;
			};
		}
		
		//	Correctly initalize the 'this.rows'
		//	value on start. 
		this.rows = this.numberOfInitRows;
		
		
 
		//	Add appropriate event listeners to table
		//	input elements.
		var numberOfInputs = this.table_inputs.length;
		
		for ( i = 0; i < numberOfInputs; i++ ) {
			
			if ( this.table_inputs[i].value == "Add" ) {
				
				//	Check for addEventListener support
				if ( this.table_inputs[i].addEventListener ) {

					this.table_inputs[i].addEventListener( 'click', this.addRow.bind( this ) );
				} else {
					//	attachEvent for IE8
					this.table_inputs[i].attachEvent( 'onclick', this.addRow.bind( this ) );
				}				
				
			} else if ( this.table_inputs[i].value == "Delete" ) {
				
				//	Check for addEventListener support
				if ( this.table_inputs[i].addEventListener ) {
					this.table_inputs[i].addEventListener( 'click', this.deleteRow.bind( this ) );
				} else {
					//	attachEvent for IE8
					this.table_inputs[i].attachEvent( 'onclick', this.deleteRow.bind( this ) );
				}
			}
			
		}
		

		//	Add appropriate event listeners to table
		//	select elements.
		var numberOfSelects = this.table_selects.length;
		
		for ( i = 0; i < numberOfSelects; i++ ) {
			
			if ( this.table_selects[i].type == "select-one" ) {
				
				//	Check for addEventListener support
				if (this.table_selects[i].addEventListener) {
					
					this.table_selects[i].addEventListener( 'change', this.selectChange );
				} else {
					//attachEvent for IE8
					this.table_selects[i].attachEvent( 'onchange', this.selectChange );
				}
			}
		}
	

	};
	
	AdminTable.prototype.selectChange = function () {
	
		var i;
		//	Check if a random value has been selected. 
		if ( this.value == 'Random...' ) {
			
			//	if so - disable the field. 
			this.name = "random";
			this.disabled = true;
			
			//	Duplicate the pre-populated random options select item. 
			var rdm_itm = document.getElementsByClassName('randomClass');
			var rdm_cln =  rdm_itm[0].cloneNode(true);
			
			//	Build array of options
			var randomClassOptions = rdm_cln.options.length;
		
			//	Cylcle through options 
			for ( i = 0; i < randomClassOptions; i++ ) {
				if ( i !== 0 ) {
	
					// if ( random prepend doesn't exist - prepend it ) {
					if ( rdm_cln.options[i].value.startsWith("random-") ) {
						//console.log("already starts with random");
					} else {
						rdm_cln.options[i].value = "random-" + rdm_cln.options[i].value;
					}
				}
			}

			//	Append cloned row to table
			var randomRow = this.parentNode;
			randomRow.appendChild(rdm_cln);
			rdm_cln.style.display = "block";
		}
		
		
	};

	AdminTable.prototype.addRow = function ( e ) {
		
		//	IE e.preventDefault() fallback for lack of
		//	event object passed on event occurence.
		if ( window.event ) {
        	window.event.returnValue = false;
		} else {
			e.preventDefault();
		}
		
		var i;		
		//	Clone the first table row
		var cln = this.tableRows[0].cloneNode( true );
		
      	//	Get input elements in clone and cache
		//	the selector and length.
		var cln_inputs = cln.getElementsByTagName( 'input' ),
			num_clnInputs = cln_inputs.length;
		
		//	Loop through clone input elements and 
		//	reset value.	
		for ( i = 0; i < num_clnInputs; i++ ) {
			cln_inputs[i].value = '';
		}
		
		//	Get select elements in clone and cache
		//	the selector and length.
		var cln_selects = cln.getElementsByTagName( 'select' ),
			num_clnSelects = cln_selects.length;
		
		//	Remove from clone any fields appended as
		//	a result of a random choice.	
		for ( i = 0; i < num_clnSelects; i++ ) {
			if ( cln_selects[i].className === 'randomClass' ) {
				cln_selects[i].parentNode.removeChild( cln_selects[i] );
			} else {
				//	Reset the value and any disabled parameter
				//	of the remaining.	
				cln_selects[i].selectedIndex = 0;
				cln_selects[i].disabled = false;
				
				//	Check for addEventListener support
				if ( cln_selects[i].addEventListener ) {				
					cln_selects[i].addEventListener( 'change', this.selectChange );
				} else {
					//attachEvent for IE8
					cln_selects[i].attachEvent( 'onchange', this.selectChange );
				}
			}
		}
		
		//	Append the updated clone of the first
		//	row to the table body.
		this.tableBody.appendChild( cln );

		//	increment the table row count by one.
		this.rows++;
		
		//	Remove focus from the element that
		//	initiated the event.
		e.target.blur();
	};
		
	AdminTable.prototype.deleteRow = function ( e ) {
		
		var i,
			del_cln,
			numRows = this.tableRows.length,
			rowsToDelete = [];
			
		//	IE e.preventDefault() fallback for lack of
		//	event object passed on event occurence.
		if ( window.event ) {
        	window.event.returnValue = false;
		} else {
			e.preventDefault();
		}
		
		for ( i = 0; i < numRows ; i++ ) {
		
			var checkboxesInRow = this.tableRows[i].getElementsByTagName("input");
			
			//Is there a checkbox in the row?
			if ( checkboxesInRow.length > 0 && checkboxesInRow[0].checked ) {
				
				rowsToDelete.push(i);
			}
		}
		
		rowsToDelete.sort( function( a, b ){ return b - a; } );

		for ( i = 0; i < rowsToDelete.length; i++ ) {
			
			//if last row create clone before
			if ( this.rows == 1 )
				del_cln = this.tableRows[0].cloneNode(true);
			
			this.tableBody.deleteRow( rowsToDelete[i] );
			//	decrement the table row count by one.
			this.rows--;
		}
		if ( this.rows <= 0 ) {
			this.tableBody.appendChild( del_cln );
			
			var cln_selects = this.tableRows[0].getElementsByTagName('select');
			var cln_inputs = this.tableRows[0].getElementsByTagName('input');

			for ( i = 0; i < cln_selects.length; i++ ) {
				
				if ( cln_selects[i].className === 'randomClass' ) {
					cln_selects[i].parentNode.removeChild( cln_selects[i] );
				} else {
					//	Reset the value and any disabled parameter
					//	of the remaining.	
					cln_selects[i].selectedIndex = 0;
					cln_selects[i].disabled = false;
					
					//	Check for addEventListener support
					if ( cln_selects[i].addEventListener ) {				
						cln_selects[i].addEventListener( 'change', this.selectChange );
					} else {
						//attachEvent for IE8
						cln_selects[i].attachEvent( 'onchange', this.selectChange );
					}
				}
			}
			for ( i = 0; i < cln_inputs.length; i++ ) {
				
				cln_inputs[i].value = '';
				
				if ( cln_inputs[i].type == 'checkbox' ) {
					
					cln_inputs[i].checked = false;
				}
			}
			this.rows = 1;
		}
		//	Remove focus from the element that 
		//	initiated the event.		
		e.target.blur();
	};
	
	/*
	*	CustomMetaOptions
	*
	*	New agnostic ( but new preferred ) constructor function. e.g:
	*	wpifw.{name} = new wpifw.CustomMetaOptions ( '{array}' );
	*
	*	@type	function
	*	@date	16/02/2015
	*	@since	0.0.1
	*
	*	@param : array, required.
 	*	@return	n/a
	*/
	
	function CustomMetaOptions( meta_options ) {
		
		"use strict";
		// reinvoke self using 'new' syntax to inherit from prototype
		// regardless of called as a function or constructor
		if (! ( this instanceof CustomMetaOptions ) ) {
			return new wpifw.CustomMetaOptions ();
		}
		
		//Private Vars
		var i,
			list_items = meta_options.getElementsByTagName('input'),
			options_list = [];
		
		for ( i = 0; i < list_items.length; i++ ) {
			
			options_list.push( list_items[i].value );
		}
			
		//	Cache the arguments array.
		this.options_list = options_list;
		this.options_list_elements = [];
		
		for  ( i = 0; i < options_list.length; i++ ) {
			
			this.options_list_elements.push(document.getElementById(options_list[i]));
		}
		
		// Add event listeners for the meta options radio buttons
		if ( meta_options.addEventListener ) {
			meta_options.addEventListener( 'click', setOptions.bind( this ) );
		} else {
			//attachEvent for IE8
			meta_options.attachEvent( 'onclick', setOptions.bind( this ) );
		}
		
		/*
		*	setOptions
		*
		*	Click handler called via meta_options 'click' event listener
		*
		*	@type	function
		*	@date	16/02/2015
		*	@since	0.0.1
		*
		*	@param : e
		*	@return	n/a
		*/
		
		function setOptions( e ) {
		
			if ( !e ) { e = window.event; }
		
			var i,
				thisClicked = e.target.value;
			
			if ( thisClicked !== 0 ) {
					
				for ( i = 0; i < this.options_list.length; i++ ) {
					this.options_list_elements[i].style.display = "none";
					
					if ( thisClicked === this.options_list[i] ) {
						this.options_list_elements[i].style.display = "block";
					}				
				}
			}			
		}
	}
	
	return {
		CustomMetaOptions : CustomMetaOptions,
		AdminTable : AdminTable
	};
	
})();