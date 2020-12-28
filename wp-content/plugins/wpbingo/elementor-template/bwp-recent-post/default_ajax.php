<?php
	$numberposts 	= (isset($_POST['numberposts']) && $_POST['numberposts'] ) ? $_POST['numberposts'] : 4;
	$paged 			= (isset($_POST['paged']) && $_POST['paged'] ) ? $_POST['paged'] : 2;
	$total 			= (isset($_POST['total']) && $_POST['total'] ) ? $_POST['total'] : 0 ;
	$category 		= (isset($_POST['category']) && $_POST['category'] ) ? $_POST['category'] : '';
	$length 		= (isset($_POST['length']) && $_POST['length'] ) ? $_POST['length'] : 20;
	$attributes 			= (isset($_POST['attributes']) && $_POST['attributes'] ) ? $_POST['attributes'] : "col-lg-4 col-sm-6 col-xs-12";
	$args = array(
		'post_type' => 'post',
		'cat' => $category, 
		'posts_per_page' => $numberposts,
		'paged' => $paged
	);
	
	$query = new WP_Query( $args );
	$result = new stdClass();

	ob_start();
		include(WPBINGO_WIDGET_TEMPLATE_PATH.'bwp-woo-recent-post-widget/blogs.php');
		$blogs = ob_get_contents();
		$result->blogs = $blogs;
	ob_end_clean();			
	
	$result->check_loadmore = 0;
	
	if(($paged * $numberposts) >= $total)
		$result->check_loadmore = 1;
	die (json_encode($result));
