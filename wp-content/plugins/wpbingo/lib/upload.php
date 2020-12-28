<?php
add_action('in_admin_footer', 'bwp_upload_script');	
add_filter( 'admin_enqueue_scripts', 'bwp_enqueue_media' );

function bwp_enqueue_media() {
	if ( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	} else {
		if (!wp_script_is ( 'media-upload' )) {
			wp_enqueue_script( 'media-upload' );
		}
	}
}

function bwp_upload_script(){ ?>
	<script type="text/javascript">

		jQuery(function($) {
			// Bind to my upload butto
			$(document).on('click', '.bwp_upload_image_button', function(event) {
				event.preventDefault();
				customUpload($(this));
				return false;
			});

			function customUpload(el) {
				custom_media = true;
				var _orig_send_attachment = wp.media.editor.send.attachment;
				wp.media.editor.send.attachment = function(props, attachment) {
					el = renderUpload(el, attachment, props);
				}
				wp.media.editor.open();
			}
			
			function renderUpload(field, attachment, props) {
				var inputText = field.data("image_id");
				// This gets the full-sized image url
				var src = attachment.url;

				// Get the size selected by the user
				var size = props.size;

				// Or, if you'd rather, you can set the size you want to get:
				// var size = 'thumbnail'; // or 'full' or 'medium' or 'large'...

				// If the media supports the selected size, get it
				if (attachment.sizes[size]) {
					src = attachment.sizes[size].url;
					$( '#'+inputText ).val( src );
					$( '.'+inputText).attr( 'src', src );
					$( '.'+inputText).show();
				}
				
				// Do what you want with src here....
			}
			
			$( ".bwp_remove_image_button" ).on( "click", function() {
				var inputText = $(this).data("image_id");
				$( '.'+inputText).hide();
				$( '#'+inputText ).val( '' );
				return false;
			});
		});

	</script>
<?php } 
