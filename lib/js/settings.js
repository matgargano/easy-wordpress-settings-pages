jQuery(document).ready(function() {
	"use strict";
	var fileFrame;

	jQuery('.upload-file').on('click', '.upload-button', function( event ){
		event.preventDefault();
		var $parent = jQuery(this).closest('.upload-file');
		// If the media frame already exists, reopen it.
		if ( fileFrame ) {
			fileFrame.open();
			return;
		}

		// Create the media frame.
		fileFrame = wp.media.frames.fileFrame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback.
		fileFrame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			var attachment = fileFrame.state().get('selection').first().toJSON();
			// Do something with attachment.id and/or attachment.url here
			var jsonObject = JSON.stringify(attachment);
			$parent.find('.file').val(jsonObject);
			$parent.find('img').attr('src', attachment.sizes.thumbnail.url);
			$parent.find('.clear-upload').removeClass('hidden');
			$parent.find('img').removeClass('hidden');
		});
		// Finally, open the modal
		fileFrame.open();
	});
	jQuery('.upload-file').on('click', '.clear-upload', function( event ){
		event.preventDefault();
		var $parent = jQuery(this).closest('.upload-file');
		$parent.find('.file').val('');
		$parent.find('img').addClass('hidden')
		$parent.find('.clear-upload').addClass('hidden');
		

	});
});

