<?php 
	$widget_id = isset( $widget_id ) ? $widget_id : 'bwp_portfolio_'.rand().time();
	$show_tab 		= ( isset( $show_tab ) ) 		? $show_tab : true;
	$title	 		= ( isset( $title ) ) 			? $title : '';
	$description 	= ( isset( $description ) ) 	? $description : '';
	if( count($portfolio) == 0 ){
		return ;
	}
	$attributes     = '';
	if( $style == 'masonry' ){
		$class_col_lg = ($col1 == 5) ? '2-4'  : (12/$col1);
		$attributes .= 'portfolio-item col-xl-'.$class_col_lg .' col-lg-'.$class_col_lg .' col-md-'.$class_col_lg .' col-12';
	}else{
		$class_col_lg = ($col1 == 5) ? '2-4'  : (12/$col1);
		$class_col_md = ($col2 == 5) ? '2-4'  : (12/$col2);
		$class_col_sm = ($col3 == 5) ? '2-4'  : (12/$col3);
		$class_col_xs = ($col4 == 5) ? '2-4'  : (12/$col4);
		$attributes .= 'grid-item col-xl-'.$class_col_lg .' col-lg-'.$class_col_md .' col-md-'.$class_col_sm .' col-'.$class_col_xs; 
	}	
	$args = array(
		'post_type' => 'portfolio',
		'tax_query' => array(
			array(
				'taxonomy'	=> 'category_portfolio',
				'field' 	=> 'slug',
				'terms'		=> $portfolio
			)
		),
		'orderby'	=> $orderby,
		'showposts' => $number
	);
	$query = new wp_query( $args );
	$max_page = $query -> max_num_pages;
?>
<div id="<?php echo esc_attr( $widget_id ); ?>" class="bwp-portfolio <?php echo esc_attr( $style ) ?>"  data-style="<?php echo esc_attr( $style ) ?>">
	<!-- Title & description -->
	<?php if( $title != '' || $description != '' ){ ?>
	<div class="portfolio-desc">
		<?php echo ( $title != '' ) ? '<h1>'. $title .'</h1>' : ""; ?>
		<?php echo ( $description != '' ) ? '<div class="p-desc">'. $description .'</div>' : ""; ?>
	</div>
	<?php } ?>
	<!-- Tab  -->
	<div class="portfolio-tab">
		<ul id="tab_<?php echo esc_attr( $widget_id ); ?>">
			<li class="selected" data-portfolio-filter="*"><?php _e( 'All', "wpbingo" ); ?></li>
		<?php
			foreach( $portfolio as $slug ){
				if($slug != 'all'){
					$cat = get_term_by('slug', $slug, 'category_portfolio');
					if($cat)
						echo '<li data-portfolio-filter=".'. $slug.'">' .esc_html( $cat -> name ). '</li>';
				}
			}
		?>
		</ul>
	</div>
	<!-- Container -->
	<div class="portfolio-container">
	<div class="row">
		<ul id="container_<?php echo esc_attr( $widget_id ); ?>" class="portfolio-content clearfix <?php echo ( $style != 'masonry' ) ? '' : '';?>">
		<?php
			while( $query -> have_posts() ) : $query -> the_post();
			global $post;
			$pterms	   	= get_the_terms( $post->ID, 'category_portfolio' );
			$term_str  = '';
			if( count($pterms) > 0 ){
				foreach( $pterms as $key => $term ){
					$term_str .= $term -> slug . ' ';
				}
			}	
			$img = '';
			if( $style == 'masonry' ){
			?>
				<li class="<?php echo $attributes.' '.esc_attr( $term_str ). ' '; ?>">
					<div class="portfolio-item-inner">
						<div class="portfolio-in">
							<?php 
								if( has_post_thumbnail() ){
									$img = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
								}
							?>							
							<a class="portfolio-img" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
								<?php the_post_thumbnail( 'full', array( 'alt' => get_the_title() ) ); ?>
							</a>
						</div>
						<div class="pitem-text">
							<a href="<?php the_permalink(); ?>" class="p-item item-more" title="<?php the_title_attribute(); ?>"><span class="fa fa-link"></span></a>
							<a href="<?php echo esc_attr( $img ); ?>" class="p-item item-popup" title="<?php the_title_attribute(); ?>"><span class="fa fa-search"></span></a>
						</div>
					</div>
				</li>
			<?php }else{ ?>					
				<li class="<?php echo $attributes.' '.esc_attr( $term_str ); ?>">
					<div class="portfolio-item-inner">
						<div class="portfolio-in">
							<?php 
								if( has_post_thumbnail() ){
									$img = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
								}
							?>
							<a class="portfolio-img" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
								<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => get_the_title() ) ); ?>
							</a>
							<div class="pitem-text">
								<a href="<?php the_permalink(); ?>" class="p-item item-more" title="<?php the_title_attribute(); ?>"><span class="fa fa-link"></span></a>
								<a href="<?php echo esc_attr( $img ); ?>" class="p-item item-popup" title="<?php the_title_attribute(); ?>"><span class="zmdi zmdi-eye"></span></a>
							</div>
						</div>
					</div>
				</li>
		<?php 
			}
			endwhile;
			wp_reset_postdata();
		?>
		</ul>
		</div>
	</div>
</div>