jQuery(document).ready(function($){

	var target = document.getElementById('navigator');
	
	if ( target ) {
		
		wpifw.highestDepth = 0;
		wpifw.nextYX = [0, 0]
		wpifw.sceneCounter = 0;	
		
		wpifw.loadStartScene( target );
		
		wpifw.startSwitcher();
	}
});	

jQuery.fn.makeDraggable = function() {
	
	jQuery( ".draggable" ).draggable({
		
		containment: "#navigator", 
		grid: [ 25, 25 ],
		scroll: true,
		
		stack: "#navigator div",
		
		start: function() {
			//console.log('start');
      	},
      	drag: function() {
			//console.log('dragging');
      	},
      	stop: function() {
			//console.log('stop');
      	}
	})
}

wpifw = (function() {
	
	if (typeof openingScene !== "undefined")
		this.openingScene = openingScene;
		
	if (typeof lastModified !== "undefined")
		this.lastModified = lastModified;
		
	if (typeof mostRecent !== "undefined")
		this.mostRecent = mostRecent;
		
	if (typeof baseUrl !== "undefined")
		this.baseUrl = baseUrl;
	
	/*
	*	startsWith & endsWith
	*
	*/	
 	if ( typeof String.prototype.startsWith != 'function' ) {

		String.prototype.startsWith = function ( str ) {
			return this.indexOf( str ) == 0;
		};
	}
	
	 if ( typeof String.prototype.endsWith != 'function' ) {

		String.prototype.endsWith = function ( str ) {
			return this.indexOf( str, this.length - str.length ) !== -1;
		};
	}
	
	function HttpRequest() {};
	HttpRequest.prototype.get = function ( url, callback ) {
		
		var xhr = new XMLHttpRequest();
		
		xhr.onreadystatechange = function() { 
		
			if ( xhr.readyState == 4 && xhr.status == 200 ) {
				
				callback(xhr.responseText);
				
			} else if ( xhr.readyState == 4 && xhr.status == 404 ) {
				
				var notFound = JSON.stringify({ ID: 404 }); 
				callback( notFound );
			}
		};

		xhr.open( "GET", url, true );  
		xhr.send( null );
			
	};
	
	function startSwitcher() {
		
		var sceneDropdown = document.getElementById('wpifw_scene_navigator'), 
			openingRadio = document.getElementById('wpifw_opening_scene'),
			modifiedRadio = document.getElementById('wpifw_last_modified'),
			recentRadio = document.getElementById('wpifw_most_recent');
			
		openingRadio.addEventListener( 'change', radioSwitch.bind( this ) );
		modifiedRadio.addEventListener( 'change', radioSwitch.bind( this ) );
		recentRadio.addEventListener( 'change', radioSwitch.bind( this ) );	
		
		sceneDropdown.addEventListener( 'change', radioSwitch.bind( this ) );
		
	}
	
	function radioSwitch( e ) {
		resetNavigator();
		var target = document.getElementById('navigator');
		loadStartScene( target, e.target.value );
		
	}
	
	function resetNavigator() {
		
		wpifw.sceneCounter = 0;
		
		wpifw.nextYX[0] = 0;
		wpifw.nextYX[1] = 0;
		
		var allScenes = document.getElementsByClassName('sceneObj');
		var numScenes = allScenes.length;
		for ( i = 0; i < numScenes; i++ ) {
			allScenes[0].parentNode.removeChild( allScenes[0] );
		}
	}
	
	function loadStartScene( target, req ) {
		
		wpifw.addLoader( target );
		
		if ( req === 'mostRecent' ) { 
			scene = wpifw.mostRecent;
			
		} else if ( req === 'lastModified' ) {
			scene = wpifw.lastModified;
			
		} else if ( req === 'openingScene' ) {
			scene = wpifw.openingScene;
			
		} else if ( typeof req === 'string' ) {
			if ( req === 'Select...') {
				scene = 'none';
			} else {
				scene = wpifw.baseUrl + req;
			}
			
		} else { 
			scene = wpifw.openingScene;
		}
		
		if ( scene !== 'none' ) {
			wpifw.getScene( scene, function( sceneObject ) {
				
				wpifw.generateSceneDisplayObj( sceneObject, function( scene ) {
					
					getScenePosition( scene );
					wpifw.removeLoader();
					jQuery.fn.makeDraggable();
					
				});
			});
		} else {
			
			var sceneDropdown = document.getElementById('wpifw_scene_navigator'), 
				openingRadio = document.getElementById('wpifw_opening_scene'),
				modifiedRadio = document.getElementById('wpifw_last_modified'),
				recentRadio = document.getElementById('wpifw_most_recent');
				
			wpifw.removeLoader();
			openingRadio.checked = false;
			modifiedRadio.checked = false;
			recentRadio.checked = false;
		
			
		}
	}
		
	function generateSceneDisplayObj( sceneObject, callback ) {
	
		var scene_navigator = document.getElementById("navigator");
		
		if ( scene_navigator ) {
			
			var scene = document.createElement( 'div' );
			scene.id = 'scene' + wpifw.sceneObject.id;
			scene.className = 'sceneObj draggable';
			
			wpifw.sceneCounter = wpifw.sceneCounter + 1;
			
			scene.addEventListener('mousedown', moveToTop.bind( this ) );
			
			var iconDiv = document.createElement( 'div' );
			iconDiv.className = 'iconDiv';
			
			var editIcon = document.createElement( 'span' );
			editIcon.className = 'dashicons dashicons-edit';
			
			var editIconA = document.createElement('a');
			editIconA.appendChild( editIcon );
			
			iconDiv.appendChild( editIconA );
			
			if ( wpifw.sceneCounter != 1 ) {
				var closeIcon = document.createElement( 'span' );
				closeIcon.className = 'dashicons dashicons-no-alt';
				iconDiv.appendChild( closeIcon );
			}
			
			scene.appendChild( iconDiv );
			
			var sceneText = document.createTextNode( wpifw.sceneObject.title.rendered );
			var sceneTextPara = document.createElement( 'p' );
			sceneTextPara.className = 'sceneHeader';
			
			sceneTextPara.appendChild( sceneText );
			
			if ( wpifw.sceneObject.id !== 404 ) {
				var linkDestination = '/wp-admin/post.php?post=' +  wpifw.sceneObject.id + '&action=edit';
			} else {
				var linkDestination = '/wp-admin/post-new.php?post_type=scene';
			}
			
			editIconA.setAttribute( "href", linkDestination );
			scene.appendChild( sceneTextPara );
			
			var sceneTextString = wpifw.sceneObject.content.rendered;
			sceneTextString = sceneTextString.replace(/<(?:.|\n)*?>/gm, '');

			if(sceneTextString.length > 80) 
				sceneTextString = sceneTextString.substring(0,80) + '...';
			
			sceneTextPara = document.createElement('p');
			sceneTextPara.className = 'excerpt';
			sceneTextPara.innerHTML = sceneTextString;
			scene.appendChild( sceneTextPara );
			
			if ( wpifw.sceneObject.custom_meta ) {
				
				var follow_on_type = wpifw.sceneObject.custom_meta.choice[0];

				if ( follow_on_type === 'choice-options' ) {
					sceneText = document.createTextNode( 'Multiple choice' );
					
				} else if ( follow_on_type === 'fight-options' ) {
					sceneText = document.createTextNode( 'Fight' );
					
				} else if ( follow_on_type === 'luck-options' ) {
					sceneText = document.createTextNode( 'Test of luck' );
					
				} else if ( follow_on_type === 'passphrase-options' ) {
					sceneText = document.createTextNode( 'Passphrase' );
					
				} else if ( follow_on_type === 'no-options' ) {
					sceneText = document.createTextNode( 'The end' );
					
				}
					
				sceneTextPara = document.createElement('p');
				sceneTextPara.className = 'follow-on';
				sceneTextPara.appendChild( sceneText );
				scene.appendChild( sceneTextPara );
					
				sceneTextParaUl = document.createElement('ol');
					
				if ( follow_on_type === 'choice-options' ) {
					
					var numChoices = wpifw.sceneObject.custom_meta.choice.length;
					for  ( var i = 1; i < numChoices; i++ ) {
						sceneTextString = wpifw.sceneObject.custom_meta.choice[i].choice;
						
						if(sceneTextString.length > 24) 
							sceneTextString = sceneTextString.substring(0,24) + '...';
						
						sceneText = document.createTextNode( sceneTextString );
						
						sceneTextPara = document.createElement('a');
						sceneTextPara.className = 'choice-link';
						linkDestination = wpifw.sceneObject.custom_meta.choice[i].destination;
						sceneTextPara.setAttribute( "id", linkDestination );
						sceneTextParaItem = document.createElement('li');
						sceneTextPara.appendChild( sceneText );
						sceneTextParaItem.appendChild( sceneTextPara );
						sceneTextParaUl.appendChild( sceneTextParaItem );
						sceneTextPara.addEventListener( 'click', loadScene.bind( this ) );
					}
					
				} else if ( follow_on_type === 'fight-options' ) {
					
					sceneTextString = 'Flee';
					sceneText = document.createTextNode( sceneTextString );
					sceneTextPara = document.createElement('a');
					sceneTextPara.className = 'choice-link';
					linkDestination = wpifw.sceneObject.custom_meta.choice[2].fleeDestination;
					sceneTextPara.setAttribute( "id", linkDestination );
					sceneTextParaItem = document.createElement('li');
					sceneTextPara.appendChild( sceneText );
					sceneTextParaItem.appendChild( sceneTextPara );
					sceneTextParaUl.appendChild( sceneTextParaItem );
					sceneTextPara.addEventListener( 'click', loadScene.bind( this ) );
					
					
					sceneTextString = 'Mercy';
					sceneText = document.createTextNode( sceneTextString );
					sceneTextPara = document.createElement('a');
					sceneTextPara.className = 'choice-link';
					linkDestination = wpifw.sceneObject.custom_meta.choice[2].mercyDestination;
					sceneTextPara.setAttribute( "id", linkDestination );
					sceneTextParaItem = document.createElement('li');
					sceneTextPara.appendChild( sceneText );
					sceneTextParaItem.appendChild( sceneTextPara );
					sceneTextParaUl.appendChild( sceneTextParaItem );
					sceneTextPara.addEventListener( 'click', loadScene.bind( this ) );
					
					
					sceneTextString = 'Victory';
					sceneText = document.createTextNode( sceneTextString );
					sceneTextPara = document.createElement('a');
					sceneTextPara.className = 'choice-link';
					linkDestination = wpifw.sceneObject.custom_meta.choice[2].victory;
					sceneTextPara.setAttribute( "id", linkDestination );
					sceneTextParaItem = document.createElement('li');
					sceneTextPara.appendChild( sceneText );
					sceneTextParaItem.appendChild( sceneTextPara );
					sceneTextParaUl.appendChild( sceneTextParaItem );
					sceneTextPara.addEventListener( 'click', loadScene.bind( this ) );
					
					
					sceneTextString = 'Defeat';
					sceneText = document.createTextNode( sceneTextString );
					sceneTextPara = document.createElement('a');
					sceneTextPara.className = 'choice-link';
					linkDestination = wpifw.sceneObject.custom_meta.choice[2].defeat;
					sceneTextPara.setAttribute( "id", linkDestination );
					sceneTextParaItem = document.createElement('li');
					sceneTextPara.appendChild( sceneText );
					sceneTextParaItem.appendChild( sceneTextPara );
					sceneTextParaUl.appendChild( sceneTextParaItem );
					sceneTextPara.addEventListener( 'click', loadScene.bind( this ) );

					
				} else if ( follow_on_type === 'luck-options' ) {
					
					sceneTextString = 'Success';
					sceneText = document.createTextNode( sceneTextString );
					sceneTextPara = document.createElement('a');
					sceneTextPara.className = 'choice-link';
					linkDestination = wpifw.sceneObject.custom_meta.choice[1].success;
					sceneTextPara.setAttribute( "id", linkDestination );
					sceneTextParaItem = document.createElement('li');
					sceneTextPara.appendChild( sceneText );
					sceneTextParaItem.appendChild( sceneTextPara );
					sceneTextParaUl.appendChild( sceneTextParaItem );
					sceneTextPara.addEventListener( 'click', loadScene.bind( this ) );
					
					
					sceneTextString = 'Failure';
					sceneText = document.createTextNode( sceneTextString );
					sceneTextPara = document.createElement('a');
					sceneTextPara.className = 'choice-link';
					linkDestination = wpifw.sceneObject.custom_meta.choice[1].fail;
					sceneTextPara.setAttribute( "id", linkDestination );
					sceneTextParaItem = document.createElement('li');
					sceneTextPara.appendChild( sceneText );
					sceneTextParaItem.appendChild( sceneTextPara );
					sceneTextParaUl.appendChild( sceneTextParaItem );
					sceneTextPara.addEventListener( 'click', loadScene.bind( this ) );
					
				} else if ( follow_on_type === 'passphrase-options' ) {
					
					var passphrase = wpifw.sceneObject.custom_meta.choice[1].passphrase;
					
					if ( passphrase!== "" ) {					
						sceneTextString = '"' + passphrase + '"';
						
					} else {
						sceneTextString = 'Yet to be defined';
						
					}
					
					sceneText = document.createTextNode( sceneTextString );
					sceneTextPara = document.createElement('p');
					sceneTextPara.appendChild( sceneText );
					scene.appendChild( sceneTextPara );


					sceneTextString = 'Success';
					sceneText = document.createTextNode( sceneTextString );
					sceneTextPara = document.createElement('a');
					sceneTextPara.className = 'choice-link';
					linkDestination = wpifw.sceneObject.custom_meta.choice[1].correctDestination;
					sceneTextPara.setAttribute( "id", linkDestination );
					sceneTextParaItem = document.createElement('li');
					sceneTextPara.appendChild( sceneText );
					sceneTextParaItem.appendChild( sceneTextPara );
					sceneTextParaUl.appendChild( sceneTextParaItem );
					sceneTextPara.addEventListener( 'click', loadScene.bind( this ) );
					
					sceneTextString = 'Failure';
					sceneText = document.createTextNode( sceneTextString );
					sceneTextPara = document.createElement('a');
					sceneTextPara.className = 'choice-link';
					linkDestination = wpifw.sceneObject.custom_meta.choice[1].incorrectDestination;
					sceneTextPara.setAttribute( "id", linkDestination );
					sceneTextParaItem = document.createElement('li');
					sceneTextPara.appendChild( sceneText );
					sceneTextParaItem.appendChild( sceneTextPara );
					sceneTextParaUl.appendChild( sceneTextParaItem );
					sceneTextPara.addEventListener( 'click', loadScene.bind( this ) );
					
					sceneTextString = 'Decline';
					sceneText = document.createTextNode( sceneTextString );
					sceneTextPara = document.createElement('a');
					sceneTextPara.className = 'choice-link';
					linkDestination = wpifw.sceneObject.custom_meta.choice[1].declineDestination;
					sceneTextPara.setAttribute( "id", linkDestination );
					sceneTextParaItem = document.createElement('li');
					sceneTextPara.appendChild( sceneText );
					sceneTextParaItem.appendChild( sceneTextPara );
					sceneTextParaUl.appendChild( sceneTextParaItem );
					sceneTextPara.addEventListener( 'click', loadScene.bind( this ) );
				}
					
				scene.appendChild( sceneTextParaUl );
			
			}
			
			scene.style.zIndex = wpifw.highestDepth + 1;
			
			scene_navigator.appendChild( scene );

			callback( scene );
		}
		
	}
	
	function loadScene( e ) {
		
		var sceneClicked =  e.target;
			if (  !hasClass( sceneClicked, 'sceneObj') )
				sceneClicked = findAncestor( e.target, 'sceneObj');
		
		wpifw.currentYX = getScenePosition( sceneClicked );
		
		if ( wpifw.nextYX ) {
			wpifw.nextYX[0] = wpifw.currentYX[0] + 50;
			wpifw.nextYX[1] = wpifw.currentYX[1] + 50;
		}

		var targetScene = baseUrl + e.target.id;
		var target = document.getElementById('navigator');
		wpifw.addLoader( target );
		
		getScene( targetScene,  function( sceneObject ) {
			
			wpifw.generateSceneDisplayObj( sceneObject, function( scene ) {

				wpifw.removeLoader();
				scene.style.top = wpifw.nextYX[0] + 'px';
				scene.style.left = wpifw.nextYX[1] + 'px';
				
				//var yx =  wpifw.getScenePosition( scene ); var top = yx[0]; var left = yx[1];	wpifw.drawLine( 0, 0, top, left );
				jQuery.fn.makeDraggable();
			});			
		});
	}
	
	function addLoader( target ) {
		
		var loading_options = document.createElement( 'div' ),
			loader = document.createElement( 'div' );
			
		loading_options.id = 'loader-options';
		loader.className = 'loader';
			
		loading_options.appendChild( loader );
		target.appendChild( loading_options );
		
		var newHighestDepth = wpifw.highestDepth + 1;
			loading_options.style.zIndex = newHighestDepth;
			
		var loadingTop = wpifw.nextYX[0];
		if ( loadingTop > 0 ) {
			loading_options.style.top =  loadingTop + 'px';
		}
		
		var loadingLeft = wpifw.nextYX[1];
		if ( loadingLeft > 0 ) {
			loading_options.style.left = loadingLeft + 'px';
		}
		
	}
	
	function removeLoader() {
		
		loading = document.getElementById('loader-options');
		if ( loading )
			loading.parentNode.removeChild( loading );
	}
	
	
	
	function getScene( sceneUrl, callback ) {
		
		if ( sceneUrl.endsWith("Select...") || sceneUrl.endsWith("/scene/") ) {
			
			wpifw.sceneObject = [];
			wpifw.sceneObject.id = 404;
			wpifw.sceneObject.title = [];
			wpifw.sceneObject.title.rendered = 'Undefined';
			wpifw.sceneObject.content = [];
			wpifw.sceneObject.content.rendered = '<h1>Not defined</h1>';
			
			callback ( wpifw.sceneObject );
			
		} else {
		
			wpifw.sceneRequest = new HttpRequest();
			wpifw.sceneRequest.get( sceneUrl, function( answer ) {
				
				wpifw.sceneObject = JSON.parse( answer );
				callback ( wpifw.sceneObject );
			});	
		}
	
	}
	
	function hasClass(element, className) {
		return element.className && new RegExp("(^|\\s)" + className + "(\\s|$)").test(element.className);
	}
	
	function moveToTop( e ) {
			
			var sceneClicked =  e.target;
			if (  !hasClass( sceneClicked, 'sceneObj') )
				sceneClicked = findAncestor( e.target, 'sceneObj');
				
			if ( hasClass( e.target, 'dashicons-no-alt' ) ) {
				sceneClicked.parentNode.removeChild( sceneClicked );
				return
			}
			
			var target = document.getElementById('navigator');
			var elems = target.getElementsByClassName('sceneObj');
			var elemsLength = elems.length;
			
			for ( var i = 0; i < elemsLength; i++ ) {
				
				var depth = parseInt(elems[i].style.zIndex);
				
				if ( depth > wpifw.highestDepth )
					wpifw.highestDepth = depth;
			}
			
			var newHighestDepth = wpifw.highestDepth + 1;
			sceneClicked.style.zIndex = newHighestDepth;
	}
	
	function findAncestor (el, cls) {
		
		while ( ( el = el.parentElement ) && !el.classList.contains( cls ) );
		return el;
	}
		
	function drawLine( ax, ay, bx, by ) {
		
		if( ay > by ) {
			bx = ax + bx;  
			ax = bx - ax;
			bx = bx - ax;
			by = ay + by;  
			ay = by - ay;  
			by = by - ay;
		}
		var calc = Math.atan( ( ay - by ) / ( bx - ax ) );
		calc = calc * 180 / Math.PI;
		var length = Math.sqrt( ( ax - bx ) * ( ax - bx ) + ( ay - by ) * ( ay - by ) );
		var target = document.getElementById('navigator');
		target.innerHTML += "<div id='line' style='height:" + length + "px;width:1px;background-color:black;position:absolute;top:" + (ay) + "px;left:" + (ax) + "px; transform:rotate(" + calc + "deg);-ms-transform:rotate(" + calc + "deg);transform-origin:0% 0%;-moz-transform:rotate(" + calc + "deg);-moz-transform-origin:0% 0%;-webkit-transform:rotate(" + calc  + "deg);-webkit-transform-origin:0% 0%;-o-transform:rotate(" + calc + "deg);-o-transform-origin:0% 0%;'></div>";
		var line = document.createElement( 'div' );
		line.id = 'line';
		line.style.height = length + 'px';
		line.style.width = '1px';
		line.style.backgroundColor = 'black';
		line.style.position = 'absolute';
		line.style.top = ay + 'px';
		line.style.left = ax + 'px';
		line.style.transform = 'rotate(' + calc + 'deg)';
		line.style.MozTransform = 'rotate(' + calc + 'deg)';
		line.style.webkitTransform = 'rotate(' + calc + 'deg)';
		line.style.transformOrigin = '0% 0%';
		line.style.MozTransformOrigin = '0% 0%';
		line.style.webkitTransformOrigin = '0% 0%';
		
		docBody = document.getElementsByTagName('body');
		docBody[0].insertBefore( line, docBody[0].firstChild );
	}
	
	function getTopLeft( el ) {
		
		var left = top = 0;
		if (el.offsetParent) {
			do {
				left += el.offsetLeft;
				top += el.offsetTop;
			} 
			while ( el = el.offsetParent );
		}	
		
		if ( isNaN(top) ) {
		
			var top = 0;
			
		}
		
		return [top,left];	
	}
						

    //var top = el.offsetTop - el.scrollTop;
//	var left =  el.offsetLeft - el.scrollLeft;
	
	
    

    //return { top, left };

	//}
	
	function getScenePosition( el ) {
		
		var target = document.getElementById('navigator');
		
		navigatorYX = getTopLeft( target );
		elementYX = getTopLeft( el );
		
		elementYX[0] = elementYX[0] - navigatorYX[0];
		elementYX[1] = elementYX[1] - navigatorYX[1];
		
		return elementYX;
		
		
	}
	
	return {
		openingScene : openingScene,
		mostRecent : mostRecent,
		lastModified : lastModified,
		loadStartScene : loadStartScene,
		baseUrl : baseUrl,
		startSwitcher : startSwitcher,
		getTopLeft : getTopLeft, 
		getScenePosition : getScenePosition, 
		generateSceneDisplayObj : generateSceneDisplayObj,
		getScene : getScene,
		loadScene : loadScene,
		addLoader : addLoader,
		removeLoader : removeLoader,
		drawLine : drawLine,
		hasClass : hasClass, 
}

})();	