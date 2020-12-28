<?php $tag_id = 'ourteam_post_' .rand().time(); 
	$args = array(
		'post_type' => 'ourteam',
		'posts_per_page' => $numberposts,
		'post_status' => 'publish'
	);
 
	$query = new WP_Query($args);
?>
<?php if($query->have_posts()):?>
<div class="bwp-ourteam">
 <div class="block">
	<?php if (isset($title1) && $title1){ ?>	
	<div class="title-block">
		<?php echo '<h2>'. $title1 .'</h2>'; ?>
	</div>
	<?php } ?>
  <div class="block_content">
   <div id="<?php echo $tag_id; ?>" class="slick-carousel" data-dots="true" data-columns4="<?php echo $columns4; ?>" data-columns3="<?php echo $columns3; ?>" data-columns2="<?php echo $columns2; ?>" data-columns1="<?php echo $columns1; ?>" data-columns="<?php echo $columns; ?>">
		<?php while($query->have_posts()):$query->the_post(); ?>
			<!-- Wrapper for slides -->
			<div class="ourteam-item">
				<div class="ourteam-items">
					<div class="ourteam-image">
						<?php the_post_thumbnail('full', array( 'class' => 'img-responsive' )) ?>
					</div>
					<div class="carousel-body ourteam-info">
						<?php 
							$team_job  = get_post_meta( get_the_ID(), 'team_job',true) ? get_post_meta( get_the_ID(), 'team_job',true) : '';				
						?>
						<div class="ourteam-customer-name"><?php the_title(); ?></div>
						<?php if($team_job): ?>	
						<div class="team-job"><?php echo esc_html($team_job); ?></div>
						<?php endif; ?>						
						<?php 
							$team_facebook  	= get_post_meta( get_the_ID(), 'team_facebook',true) ? get_post_meta( get_the_ID(), 'team_facebook',true) : '#';
							$team_twitter  		= get_post_meta( get_the_ID(), 'team_twitter',true) ? get_post_meta( get_the_ID(), 'team_twitter',true) : '#';
							$team_youtube  	= get_post_meta( get_the_ID(), 'team_youtube',true) ? get_post_meta( get_the_ID(), 'team_youtube',true) : '#';
							$team_pinterest  	= get_post_meta( get_the_ID(), 'team_pinterest',true) ? get_post_meta( get_the_ID(), 'team_pinterest',true) : '#';
							if( $team_facebook || $team_twitter || $team_google_plus || $team_tumblr || $team_pinterest ) :
								echo '<ul class="social-link">';
								if( $team_facebook ) {
									echo '<li><a href="' . $team_facebook . '"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>';
								}
								if( $team_twitter ) {
									echo '<li><a href="' . $team_twitter . '"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>';
								}
								if( $team_youtube ) {
									echo '<li><a href="' . $team_youtube . '"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>';
								}
								if( $team_pinterest ) {
									echo '<li><a href="' . $team_pinterest . '"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>';
								}
								echo '</ul>';
							endif;
						?>						
					</div>
				</div>
			</div>
		<?php endwhile; ?>
   </div>
  </div>
 </div>
</div>
<?php endif;?>