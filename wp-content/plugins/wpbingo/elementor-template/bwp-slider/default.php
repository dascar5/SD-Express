<?php if($settings['list_tab']){ ?>
	<div class="bwp-slider <?php echo esc_attr($layout); ?>">
		<div class="slick-carousel slick-carousel-center"  data-centerMode="true" data-nav="<?php echo esc_attr($show_nav);?>" data-dots="<?php echo esc_attr($show_pag);?>" data-columns4="<?php echo esc_attr($columns4); ?>" data-columns3="<?php echo esc_attr($columns3); ?>" data-columns2="<?php echo esc_attr($columns2); ?>" data-columns1="<?php echo esc_attr($columns1); ?>" data-columns="<?php echo esc_attr($columns); ?>" >
			<?php foreach ($settings['list_tab'] as  $item){ ?>
				<?php if($item['link_slider']){ ?>
					<div class="item">
						<div class="content">
							<?php if( $item['image_slider'] && $item['image_slider']['url'] ){ ?>
								<div  class="image-slider">
									<a href="<?php echo esc_url($item['url']); ?>">
										<img src="<?php echo esc_url($item['image_slider']['url']); ?>" alt="<?php echo esc_attr__('Image Slider','wpbingo'); ?>">
									</a>
								</div>
							<?php } ?>
							<div class="slider-content">
								<?php if( $item['subtitle'] ){ ?>
									<div class="description-slider">
										<?php echo wp_kses_post($item['subtitle']); ?>
									</div>
								<?php } ?>
								<?php if( $item['title_slider'] ){ ?>
									<h2 class="title-slider"><?php echo esc_html($item['title_slider']); ?></h2>
								<?php } ?>
								<?php if( $item['description_slider'] ){ ?>
									<div class="description-slider">
										<?php echo wp_kses_post($item['description_slider']); ?>
									</div>
								<?php } ?>
								<?php if( $item['title_button_slider'] &&  $item['link_slider'] ){ ?>
								<div class="btn-slider"><a href="<?php echo esc_url($item['link_slider']);?>"><?php echo esc_html($item['title_button_slider']); ?></a></div>
								<?php } ?>
							</div>
						</div>
					</div>
				<?php }else{ ?>
					<div class="item">
						<div class="content">
							<?php if( $item['image_slider'] && $item['image_slider']['url'] ){ ?>
								<div  class="image-slider">
									<img src="<?php echo esc_url($item['image_slider']['url']); ?>" alt="<?php echo esc_attr__('Image Slider','wpbingo'); ?>">
								</div>
							<?php } ?>
							<div class="slider-content">
								<?php if( $item['subtitle'] ){ ?>
									<div class="description-slider">
										<?php echo wp_kses_post($item['subtitle']); ?>
									</div>
								<?php } ?>
								<?php if( $item['title_slider'] ){ ?>
									<h2 class="title-slider"><?php echo esc_html($item['title_slider']); ?></h2>
								<?php } ?>
								<?php if( $item['description_slider'] ){ ?>
									<div class="description-slider">
										<?php echo wp_kses_post($item['description_slider']); ?>
									</div>
								<?php } ?>
							</div>
						</div>				
					</div>				
				<?php } ?>
					
			<?php } ?>
		</div>
	</div>
<?php }?>