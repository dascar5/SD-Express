<?php if($slider){ ?>
	<div class="bwp-slider <?php echo esc_attr($layout); ?>">
		<div class="container">
			<div class="row">
				<div class="slick-carousel slick-carousel-center" data-nav="<?php echo esc_attr($show_nav);?>" data-dots="<?php echo esc_attr($show_pag);?>" data-columns4="<?php echo $columns4; ?>" data-columns3="<?php echo $columns3; ?>" data-columns2="<?php echo $columns2; ?>" data-columns1="<?php echo $columns1; ?>" data-columns="<?php echo $columns; ?>" >
				<?php foreach($slider as $item){
					$posts = get_posts(array('name' => $item, 'post_type' => 'bwp_slider'));
					if(!empty($posts)){
						foreach($posts as $post){ ?>
							<div class="item">
								<?php if(has_post_thumbnail()){ ?>
								<a href="<?php echo esc_url(get_post_meta( $post->ID, 'url', true ));?>">
									<?php echo wp_kses_post(get_the_post_thumbnail($post->ID,'full')); ?>
								</a>
						<?php } ?>
								<?php if ( has_excerpt( $post->ID ) ) { ?>
									<div class="content-slider">
										<div class="content-excerpt"><a href="<?php echo esc_url(get_post_meta( $post->ID, 'url', true ));?>"><?php echo wp_kses_post($post->post_excerpt); ?></a></div>
										<div class="btn-slider"><a href="<?php echo esc_url(get_post_meta( $post->ID, 'url', true ));?>"><?php echo esc_html__("Shop Now","wpbingo"); ?></a></div>
									</div>
								<?php } ?>
							</div>						
						<?php }
					}
				}?>
				</div>
			</div>
		</div>
	</div>
<?php }?>