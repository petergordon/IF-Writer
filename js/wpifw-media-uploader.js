jQuery(document).ready(function($){
    
    $('#icon_color').iris({
			hide: false
		});
	
	$('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_cover_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            //console.log(uploaded_image);
			var cover_image_JSON = uploaded_cover_image.toJSON();

            var cover_image_id = cover_image_JSON.id;
			var cover_image_url = cover_image_JSON.url;
			var cover_image_thumbnail_url = cover_image_JSON.sizes.thumbnail.url;
            // Let's assign the url value to the input field
            $('#cover_image_id').val(cover_image_id);
			$('#cover_image_url').val(cover_image_url);
			$('#cover_image_thumbnail_url').val(cover_image_thumbnail_url);
			var cover_thumbnail = document.getElementById('cover_image_thumbnail');
			cover_thumbnail.src = cover_image_thumbnail_url;
			cover_thumbnail.style.display = 'block';
			
			var overlayRow = document.getElementById("cover-overlay-row"),
				overlayThumbnailRow = document.getElementById("cover_image_thumbnail_row");
			overlayRow.style.display = 'table-row';
			overlayThumbnailRow.style.display = 'table-row';
			
			//$('#coverImageID').src(image_url);
        });
    });
	
	$('#clear-upload-btn').click(function(e) {
		var overlayRow = document.getElementById("cover-overlay-row");
		overlayRow.style.display = 'none';
		e.preventDefault();
		//console.log('clear clicked');
		var cover_thumbnail = document.getElementById('cover_image_thumbnail');
		cover_thumbnail.src = '';
		cover_thumbnail.style.display = 'none';
		
		var cover_image_id = '';
		var cover_image_url = '';
		var cover_image_thumbnail_url = '';
			
		$('#cover_image_id').val('');
		$('#cover_image_url').val('');
		$('#cover_image_thumbnail_url').val('');
		this.blur();
		
	});
	
	$('#cover-overlay-upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_cover_image_overlay = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            //console.log(uploaded_image);
			var cover_image_overlay_JSON = uploaded_cover_image_overlay.toJSON();

            var cover_image_overlay_id = cover_image_overlay_JSON.id;
			var cover_image_overlay_url = cover_image_overlay_JSON.url;
			var cover_image_overlay_title = cover_image_overlay_JSON.title;
            // Let's assign the url value to the input field
            $('#cover_image_overlay_id').val(cover_image_overlay_id);
			$('#cover_image_overlay_url').val(cover_image_overlay_url);
			$('#cover_image_overlay_title').val(cover_image_overlay_title);
			//$('#coverImageID').src(image_url);
        });
    });
	
		$('#clear-cover-overlay-upload-btn').click(function(e) {
		e.preventDefault();
		//console.log('clear clicked');
		
            var cover_image_overlay_id = '';
			var cover_image_overlay_url = '';
			var cover_image_overlay_title = '';
            // Let's assign the url value to the input field
            $('#cover_image_overlay_id').val('');
			$('#cover_image_overlay_url').val('');
			$('#cover_image_overlay_title').val('');
			this.blur();
	});
	
});