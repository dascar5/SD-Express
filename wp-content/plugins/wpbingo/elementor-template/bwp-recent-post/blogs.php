<?php $classes = array('post-grid',$attributes); ?>
<?php if($query->have_posts()):?>
	<?php while($query->have_posts()):$query->the_post(); ?>
	<!-- Wrapper for slides -->
		<div  <?php post_class( $classes ); ?>>
			<?php wpbingo_posted_on3(); ?>
			<div class="post-inner style">
				<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
				<?php
					if( has_post_thumbnail() ) :
						the_post_thumbnail( 'dimita-full-width', array( 'alt' => get_the_title() ) );
					else :
						echo '<img src="' . esc_url( get_template_directory_uri() . '/images/placeholder.jpg' ) . '" alt="' . get_the_title() . '">';
					endif;
				?>
				</a>
				<div class="post-content">
					<h2 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2> 	 
					<?php echo wpbingo_get_excerpt( $length, true ); ?>	
					<a class="post-btn-more" href="<?php echo esc_url(the_permalink()) ?>"> <?php echo  esc_html_e( 'Read more', 'wpbingo' ) ?></a>	
				</div>
			</div>
		</div><!-- #post-## -->	
	<?php endwhile; wp_reset_postdata(); ?>
<?php endif;?>	