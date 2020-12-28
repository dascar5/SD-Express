<?php if($slider){ ?>
	<div class="bwp-slider <?php echo esc_attr($layout); ?>">
		<ul class="accordion">
			<?php foreach($slider as $item){
				$posts = get_posts(array('name' => $item, 'post_type' => 'bwp_slider'));
				if(!empty($posts)){
					foreach($posts as $post){ ?>
						<li class="tabs">
							<div class="title">
								<h2 class="title-slider"><?php echo wp_kses_post($post->post_excerpt); ?></h2>
							</div>
							<div class="paragraph row">
								<div class="content col-sm-6">
									<?php if ( has_excerpt( $post->ID ) ) { ?>
										<div class="slider-content">
											<a href="<?php echo esc_url(get_post_meta( $post->ID, 'url', true ));?>"><?php echo wp_kses_post($post->post_excerpt); ?></a>
										</div>
									<?php } ?>
									<?php if($label) { ?>
										<div class="btn-slider">
											<a href="<?php echo esc_url(get_post_meta( $post->ID, 'url', true ));?>"><?php echo esc_html($label); ?></a>
										</div>
									<?php } ?>
								</div>
								<div class="image-slider col-sm-6">
									<a href="<?php echo esc_url(get_post_meta( $post->ID, 'url', true ));?>">
										<?php echo wp_kses_post(get_the_post_thumbnail($post->ID,'full')); ?>
									</a>
								</div>
							</div>
						</li>
					<?php }
				}
			}?>
		</ul>
	</div>
<?php }?>