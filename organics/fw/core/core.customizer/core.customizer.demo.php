<div class="to_demo_wrap">
	<a href="" class="to_demo_pin iconadmin-pin" title="<?php esc_attr_e('Pin/Unpin demo-block by the right side of the window', 'organics'); ?>"></a>
	<div class="to_demo_body_wrap">
		<div class="to_demo_body">
			<h1 class="to_demo_header"><?php echo sprintf(
					esc_html__('Header with %s inner link %s and it $s hovered state %s', 'organics'),
					'<span class="to_demo_header_link">',
						'</span>',
						'<span class="to_demo_header_hover">',
						'</span>'
					); ?></h1>
			<p class="to_demo_info"><?php esc_html_e('Posted', 'organics');?> <span class="to_demo_info_link"><?php esc_html_e('12 May, 2015','organics');?> </span> <?php esc_html_e('by','organics');?> <span class="to_demo_info_hover"><?php esc_html_e('Author name hovered','organics');?> </span>.</p>
			<p class="to_demo_text"><?php esc_html_e('This is default post content. Colors of each text element are set based on the color you choose below.','organics');?></p>
			<p class="to_demo_text"><span class="to_demo_text_link"><?php esc_html_e('link example','organics');?></span> <?php esc_html_e('and','organics');?> <span class="to_demo_text_hover"><?php esc_html_e('hovered link','organics');?></span></p>

			<?php 
			if (is_array($ORGANICS_GLOBALS['custom_colors']) && count($ORGANICS_GLOBALS['custom_colors']) > 0) {
				foreach ($ORGANICS_GLOBALS['custom_colors'] as $slug=>$scheme) { 
					?>
					<h3 class="to_demo_header"><?php esc_html_e('Accent colors','organics');?></h3>
					<?php if (isset($scheme['accent1'])) { ?>
						<div class="to_demo_columns3"><p class="to_demo_text"><span class="to_demo_accent1"><?php esc_html_e('accent1 example','organics');?></span> <?php esc_html_e('and','organics');?> <span class="to_demo_accent1_hover"><?php esc_html_e('hovered accent1','organics');?></span></p></div>
					<?php } ?>
					<?php if (isset($scheme['accent2'])) { ?>
						<div class="to_demo_columns3"><p class="to_demo_text"><span class="to_demo_accent2"><?php esc_html_e('accent2 example','organics');?></span> <?php esc_html_e('and','organics');?> <span class="to_demo_accent2_hover"><?php esc_html_e('hovered accent2','organics');?></span></p></div>
					<?php } ?>
					<?php if (isset($scheme['accent3'])) { ?>
						<div class="to_demo_columns3"><p class="to_demo_text"><span class="to_demo_accent3"><?php esc_html_e('accent3 example','organics');?></span> <?php esc_html_e('and','organics');?> <span class="to_demo_accent3_hover"><?php esc_html_e('hovered accent3','organics');?></span></p></div>
					<?php } ?>
		
					<h3 class="to_demo_header"><?php esc_html_e('Inverse colors (on accented backgrounds)','organics');?></h3>
					<?php if (isset($scheme['accent1'])) { ?>
						<div class="to_demo_columns3 to_demo_accent1_bg">
							<h4 class="to_demo_accent1_hover_bg to_demo_inverse_dark to_demo_accent_header"><?php esc_html_e('Accented block header','organics');?></h4>
							<div>
								<p class="to_demo_inverse_light"><?php esc_html_e('Posted','organics');?> <span class="to_demo_inverse_link"><?php esc_html_e('12 May, 2015','organics');?></span> <?php esc_html_e('by','organics');?> <span class="to_demo_inverse_hover"><?php esc_html_e('Author name hovered','organics');?></span>.</p>
								<p class="to_demo_inverse_text"><?php esc_html_e('This is a inversed colors example for the normal text','organics');?></p>
								<p class="to_demo_inverse_text"><span class="to_demo_inverse_link"><?php esc_html_e('link example','organics');?></span> <?php esc_html_e('and','organics');?> <span class="to_demo_inverse_hover"><?php esc_html_e('hovered link','organics');?></span></p>
							</div>
						</div>
					<?php } ?>
					<?php if (isset($scheme['accent2'])) { ?>
						<div class="to_demo_columns3 to_demo_accent2_bg">
							<h4 class="to_demo_accent2_hover_bg to_demo_inverse_dark"><?php esc_html_e('Accented block header','organics');?></h4>
							<div>
								<p class="to_demo_inverse_light"><?php esc_html_e('Posted','organics');?> <span class="to_demo_inverse_link"><?php esc_html_e('12 May, 2015','organics');?></span> <?php esc_html_e('by','organics');?> <span class="to_demo_inverse_hover"><?php esc_html_e('Author name hovered','organics');?></span>.</p>
								<p class="to_demo_inverse_text"><?php esc_html_e('This is a inversed colors example for the normal text','organics');?></p>
								<p class="to_demo_inverse_text"><span class="to_demo_inverse_link"><?php esc_html_e('link example','organics');?></span> <?php esc_html_e('and','organics');?> <span class="to_demo_inverse_hover"><?php esc_html_e('hovered link','organics');?></span></p>
							</div>
						</div>
					<?php } ?>
					<?php if (isset($scheme['accent3'])) { ?>
						<div class="to_demo_columns3 to_demo_accent3_bg">
							<h4 class="to_demo_accent3_hover_bg to_demo_inverse_dark"><?php esc_html_e('Accented block header','organics');?></h4>
							<div>
								<p class="to_demo_inverse_light"><?php esc_html_e('Posted','organics');?> <span class="to_demo_inverse_link"><?php esc_html_e('12 May, 2015','organics');?></span> <?php esc_html_e('by','organics');?> <span class="to_demo_inverse_hover"><?php esc_html_e('Author name hovered','organics');?></span>.</p>
								<p class="to_demo_inverse_text"><?php esc_html_e('This is a inversed colors example for the normal text','organics');?></p>
								<p class="to_demo_inverse_text"><span class="to_demo_inverse_link"><?php esc_html_e('link example','organics');?></span> <?php esc_html_e('and','organics');?> <span class="to_demo_inverse_hover"><?php esc_html_e('hovered link','organics');?></span></p>
							</div>
						</div>
					<?php } ?>
					<?php 
					break;
				}
			}
			?>
	
			<h3 class="to_demo_header"><?php esc_html_e('Alternative colors used to decorate highlight blocks and form fields','organics');?></h3>
			<div class="to_demo_columns2">
				<div class="to_demo_alter_block">
					<h4 class="to_demo_alter_header block_with_no_top_margin"><?php esc_html_e('Highlight block header','organics');?></h4>
					<p class="to_demo_alter_text"><?php esc_html_e('This is a plain text in the highlight block. This is a plain text in the highlight block.','organics');?></p>
					<p class="to_demo_alter_text"><span class="to_demo_alter_link"><?php esc_html_e('link example','organics');?></span> <?php esc_html_e('and','organics');?> <span class="to_demo_alter_hover"><?php esc_html_e('hovered link','organics');?></span></p>
				</div>
			</div>
			<div class="to_demo_columns2">
				<h4 class="to_demo_header block_with_no_top_margin"><?php esc_html_e('Form field','organics');?></h4>
				<input type="text" class="to_demo_field" value="Input field example">
				<h4 class="to_demo_header"><?php esc_html_e('Form field focused','organics');?></h4>
				<input type="text" class="to_demo_field_focused" value="Focused field example">
			</div>
		</div>
	</div>
</div>
