<?php
organics_enqueue_slider();

$theme_skin = organics_get_custom_option('theme_skin');
$color_scheme = organics_get_custom_option('body_scheme');
if (empty($color_scheme)) $color_scheme = 'original';
$color_scheme_list = organics_get_list_color_schemes();
$body_style = organics_get_custom_option('body_style');
$bg_color 	= organics_get_custom_option('bg_color');
$bg_pattern = organics_get_custom_option('bg_pattern');
$bg_image 	= organics_get_custom_option('bg_image');

$co_style = 'co_light';
?>
<div class="custom_options_shadow"></div>

<div id="custom_options" class="custom_options <?php echo esc_attr($co_style); ?>">

	<a href="#" id="co_toggle" class="icon-params-light"></a>
	
	<div class="co_header">
		<div class="co_title">
			<span><?php esc_html_e('Style switcher', 'organics'); ?></span>
			<a href="#" id="co_theme_reset" class="co_reset icon-retweet" title="<?php esc_attr_e('Reset to defaults', 'organics'); ?>"><?php esc_html_e('RESET', 'organics'); ?></a>
		</div>
	</div>

	<div id="sc_custom_scroll" class="co_options sc_scroll sc_scroll_vertical">
		<div class="sc_scroll_wrapper swiper-wrapper">
			<div class="sc_scroll_slide swiper-slide">
				<input type="hidden" id="co_site_url" name="co_site_url" value="<?php echo esc_url(organics_get_protocol().'://' . (home_url('/')) . (add_query_arg(array()))); ?>" />

				<div class="co_section">
					<div class="co_label"><?php esc_html_e('Body styles', 'organics'); ?></div>
					<div class="co_switch_box co_switch_horizontal co_switch_columns_2" data-options="body_style">
						<div class="switcher" data-value="<?php echo esc_attr($body_style); ?>"></div>
						<a href="#" data-value="boxed"><?php esc_html_e('Boxed', 'organics'); ?></a>
						<a href="#" data-value="wide"><?php esc_html_e('Wide', 'organics'); ?></a>
					</div>
				</div>

				<div class="co_section">
					<div class="co_label"><?php esc_html_e('Color scheme', 'organics'); ?></div>
					<div id="co_scheme_list" class="co_image_check" data-options="body_scheme">
						<?php 
						if (is_array($color_scheme_list) && count($color_scheme_list) > 0) {
							foreach ($color_scheme_list as $k=>$v) {
								$scheme = organics_get_file_url('skins/'.($theme_skin).'/images/schemes/'.($k).'.jpg');
								?>
								<a href="#" id="scheme_<?php echo esc_attr($k); ?>" class="co_scheme_wrapper<?php organics_show_layout($color_scheme==$k ? ' active' : ''); ?>" style="background-image: url(<?php echo esc_url($scheme); ?>)" data-value="<?php echo esc_attr($k); ?>"><span><?php echo esc_attr($v); ?></span></a>
								<?php
							}
						}
						?>
					</div>
				</div>

				<div class="co_section">
					<div class="co_label"><?php esc_html_e('Background pattern', 'organics'); ?></div>
					<div id="co_bg_pattern_list" class="co_image_check" data-options="bg_pattern">
						<?php
						for ($i=1; $i<=5; $i++) {
							$pattern = organics_get_file_url('images/bg/pattern_'.intval($i).'.jpg');
							$thumb   = organics_get_file_url('images/bg/pattern_'.intval($i).'_thumb.jpg');
							?>
							<a href="#" id="pattern_<?php echo esc_attr($i); ?>" class="co_pattern_wrapper<?php organics_show_layout($bg_pattern==$i ? ' active' : ''); ?>" style="background-image: url(<?php echo esc_url($thumb); ?>)"><span class="co_bg_preview" style="background-image: url(<?php echo esc_url($pattern); ?>)"></span></a>
							<?php
						}
						?>
					</div>
				</div>

				<div class="co_section">
					<div class="co_label"><?php esc_html_e('Background image', 'organics'); ?></div>
					<div id="co_bg_images_list" class="co_image_check" data-options="bg_image">
						<?php
						for ($i=1; $i<=3; $i++) {
							$image = organics_get_file_url('images/bg/image_'.intval($i).'.jpg');
							$thumb = organics_get_file_url('images/bg/image_'.intval($i).'_thumb.jpg');
							?>
							<a href="#" id="pattern_<?php echo esc_attr($i); ?>" class="co_image_wrapper<?php organics_show_layout($bg_image==$i ? ' active' : ''); ?>" style="background-image: url(<?php echo esc_url($thumb); ?>)"><span class="co_bg_preview" style="background-image: url(<?php echo esc_url($image); ?>)"></span></a>
							<?php
						}
						?>
					</div>
				</div>

			</div><!-- .sc_scroll_slide -->
		</div><!-- .sc_scroll_wrapper -->
		<div id="sc_custom_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical sc_custom_scroll_bar"></div>
	</div><!-- .sc_scroll -->
</div><!-- .custom_options -->