<?php
class postcs_Admin extends postcsCls {

	public function __construct() {
		/*Menu pages*/
		add_action('admin_menu', array( $this, 'postcs_menu' ));

		/*Form options*/
		add_action( 'admin_init', array( $this, 'postcs_register_option_settings' ));
	}

	/*Add menu*/
	public function postcs_menu() {
		add_menu_page( __( 'Post Types Carousel & Slider', 'post-types-carousel-slider' ), __( 'Post Types Carousel & Slider', 'post-types-carousel-slider' ), 'manage_options', 'postcs', array( $this, 'postcs_page' ) );
	}

	/*Common option for all slider*/
	public function postcs_register_option_settings() {
		register_setting( 'postcs-settings-group', 'ps_setting' );
	}

	/*Admin listing & slider setting*/
	public function postcs_page() {
		?>
		<div id="<?php echo esc_attr( 'psbox' ); ?>" class="<?php echo esc_attr( 'wrap' ); ?>">
			<h1><?php echo esc_html( 'Post Types Carousel & Slider' ); ?></h1>
			<hr>
			<?php
			$slider_no = sanitize_text_field($_GET['slider_no']);
			/*Slider setting form*/
			if($slider_no) {
				/*Get form data (common option for all slider)*/
				$get_ps_set = get_option("ps_setting");
				if($get_ps_set && key($get_ps_set) && key($get_ps_set) == $slider_no) {
					if(get_option( "ps_setting{$slider_no}" )) {
						/*Update slider setting*/
						update_option( "ps_setting{$slider_no}", $get_ps_set[key($get_ps_set)] );
					} else {
						/*Add slider setting*/
						delete_option("ps_setting{$slider_no}");
						add_option("ps_setting{$slider_no}", $get_ps_set[key($get_ps_set)], "", "yes" );									
					}
				}
				
				/*Get slider setting*/
				$get_ps_setting = get_option( "ps_setting{$slider_no}" );

				/*Delete common option*/
				delete_option("ps_setting");

				/*Design template*/
				$des_opt = "";
				if($get_ps_setting['design_option']) {
					$get_do = strtolower($get_ps_setting['design_option']);
					$get_do = str_replace(" ", "", $get_do);
					$des_opt = ' design_option="'.$get_do.'"';
				}
				
				/*Display shortcodes*/
				$sortcodeforcms = "[post-cs id=".$slider_no."]";
				$sortcodeforphp = "&lt;?php echo do_shortcode('[post-cs id=".$slider_no."]'); ?&gt;";
				
				/*Preview*/
				$preview = sanitize_text_field($_GET['preview']);
				if($preview) {
					?>
					<div class="content-box top">
						<a class="<?php echo esc_attr( 'button button-primary' ); ?>" href="<?php echo admin_url('/admin.php?page=postcs&slider_no='.$slider_no.''); ?>">
							<?php echo esc_html('Back'); ?>
						</a>
						<?php echo do_shortcode('[post-cs id='.$preview.' '.$des_opt.']'); ?>
					</div>
					<?php					
				} else {
					?>
					<div class="content-box top">
						<h3><?php echo esc_html( 'Sortcode' ) ?> :</h3>
						<pre>
							<strong><?php echo esc_html( 'CMS' ) ?> </strong> <?php echo $sortcodeforcms; ?>
						</pre>
						<pre>
							<strong><?php echo esc_html( 'PHP' ) ?> </strong> <?php echo $sortcodeforphp; ?>
						</pre>
						<h3 class="<?php echo esc_attr( 'rateme alain-right' ); ?>">Don’t forget to rate this plugin if you like it, thanks!... :)</h3>
						<a class="<?php echo esc_attr( 'button button-primary' ); ?>" href="<?php echo admin_url('/admin.php?page=postcs'); ?>"><?php echo esc_html('All Slider and Carousel'); ?></a>
						<a class="<?php echo esc_attr( 'button button-primary' ); ?>" href="<?php echo admin_url('/admin.php?page=postcs&slider_no='.$slider_no.'&preview='.$slider_no.''); ?>"><?php echo esc_html('Preview'); ?></a>						
					</div>
					<form method="post" action="options.php">
						<?php
						settings_fields( 'postcs-settings-group' );
						do_settings_sections( 'postcs-settings-group' );
						?>
						<table class="<?php echo esc_attr( 'form-table psform' ); ?>">
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Post type' ); ?></th>
								<td>
									<?php
									if(isset($get_ps_setting['post_type'])) {
										$get_post_type = $get_ps_setting['post_type'];	
									} else {
										$get_post_type = false;
									}
									?>
									<select name="ps_setting[<?php echo $slider_no; ?>][post_type]" class="<?php echo esc_attr( 'regular-text' ); ?>">
										<?php
										foreach ( get_post_types( '', 'names' ) as $post_type ) {
											if($post_type == 'page' || $post_type == 'attachment' || $post_type == 'revision' || $post_type == 'nav_menu_item' || $post_type == 'custom_css' || $post_type == 'customize_changeset' || $post_type == 'acf' || $post_type == 'product_variation' || $post_type == 'shop_order' || $post_type == 'shop_order_refund' || $post_type == 'shop_webhook') {

											} else { ?>
												<option <?php if($get_post_type == $post_type) echo 'selected'; ?> value="<?php echo esc_attr($post_type); ?>"><?php echo $post_type; ?></option>
											<?php }
										} ?>								
									</select>
									<p class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Retrieves posts by Post Type' ); ?>
									</p>				
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Posts per page' ); ?></th>
								<td>
									<?php
									if(isset($get_ps_setting['posts_per_page'])) {
										$get_posts_per_page = $get_ps_setting['posts_per_page'];	
									} else {
										$get_posts_per_page = 1;
									}
									if($get_posts_per_page == '') {
										$get_posts_per_page = 1;
									}
									?>
									<input type="number" name="ps_setting[<?php echo $slider_no; ?>][posts_per_page]" class="<?php echo esc_attr( 'regular-text' ); ?>" value="<?php echo esc_attr($get_posts_per_page); ?>" />
									<p class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Number of post to show per slider' ); ?>
									</p>
									<p class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Select 1 for slider and more then 1 for carousel' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Order' ); ?></th>
								<td>
									<?php
									if(isset($get_ps_setting['order'])) {
										$get_order = $get_ps_setting['order'];	
									} else {
										$get_order = false;
									}
									?>
									<select name="ps_setting[<?php echo $slider_no; ?>][order]" class="<?php echo esc_attr( 'regular-text' ); ?>">
										<option <?php if($get_order == 'ASC') echo 'selected'; ?> value="<?php echo esc_attr( 'ASC' ); ?>"><?php echo esc_html( 'ASC' ); ?></option>
										<option <?php if($get_order == 'DESC') echo 'selected'; ?> value="<?php echo esc_attr( 'DESC' ); ?>"><?php echo esc_html( 'DESC' ); ?></option>
									</select>
									<p class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Ascending or descending order' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Order by' ); ?></th>
								<td>
									<?php
									if(isset($get_ps_setting['orderby'])) {
										$get_orderby = $get_ps_setting['orderby'];
									} else {
										$get_orderby = false;
									}
									?>
									<select name="ps_setting[<?php echo $slider_no; ?>][orderby]" class="<?php echo esc_attr( 'regular-text' ); ?>">
										<option <?php if($get_orderby == 'ID') echo 'selected'; ?> value="<?php echo esc_attr( 'ID' ); ?>"><?php echo esc_html( 'ID' ); ?></option>
										<option <?php if($get_orderby == 'title') echo 'selected'; ?> value="<?php echo esc_attr( 'title' ); ?>"><?php echo esc_html( 'title' ); ?></option>
										<option <?php if($get_orderby == 'name') echo 'selected'; ?> value="<?php echo esc_attr( 'name' ); ?>"><?php echo esc_html( 'name' ); ?></option>
										<option <?php if($get_orderby == 'date') echo 'selected'; ?> value="<?php echo esc_attr( 'date' ); ?>"><?php echo esc_html( 'date' ); ?></option>
									</select>
									<p class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Sort retrieved posts by ID, title, name or date' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Category' ); ?></th>
								<td>
									<?php
									$categories = get_categories( array(
									    'orderby' => 'name',
									    'order'   => 'ASC'
									) );
									if(isset($get_ps_setting['cat'])) {
										$get_cat = $get_ps_setting['cat'];	
									} else {
										$get_cat = false;
									}
									?>
									<select name="ps_setting[<?php echo $slider_no; ?>][cat][]" multiple class="<?php echo esc_attr( 'regular-text' ); ?>">
									<option value="">Select Category</option>
									<?php foreach($categories as $key => $val) { ?>
										<option <?php if ($get_cat && in_array($val->term_id, $get_cat)) { echo "selected"; } ?> value="<?php echo $val->term_id; ?>"><?php echo $val->name; ?></option>
									<?php } ?>
									</select>
									<p class="description">
										<?php echo esc_html( 'Display posts that have this category' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Exclude Category' ); ?></th>
								<td>
									<?php
									if(isset($get_ps_setting['excat'])) {
										$get_excat = $get_ps_setting['excat'];	
									} else {
										$get_excat = false;
									}
									?>
									<select name="ps_setting[<?php echo $slider_no; ?>][excat][]" multiple class="<?php echo esc_attr( 'regular-text' ); ?>">
									<option value="">Select Category</option>
									<?php foreach($categories as $key => $val) { ?>
										<option <?php if ($get_excat && in_array($val->term_id, $get_excat)) { echo "selected"; } ?> value="<?php echo $val->term_id; ?>"><?php echo $val->name; ?></option>
									<?php } ?>
									</select>
									<p class="description">
										<?php echo esc_html( 'Display all posts except those from selected category' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Taxonomy' ); ?></th>
								<td>
									<?php $taxonomies = get_taxonomies();
									foreach($taxonomies as $key => $val) {
										if($key == 'category' || $key == 'post_tag' || $key == 'nav_menu' || $key == 'link_category' || $key == 'post_format') {

										} else {
											if(isset($get_ps_setting['tax'])) {
												$get_tax = $get_ps_setting['tax'];	
											} else {
												$get_tax = false;
											}
											$getterms = get_terms( array(
											    'taxonomy' => $key,
											    'hide_empty' => false,
											) );
											if(count($getterms)) {
											?>
											<select rows="10" cols="50" name="ps_setting[<?php echo $slider_no; ?>][tax][]" multiple class="<?php echo esc_attr( 'regular-text' ); ?>">
											<option value=""><?php echo esc_html( 'Select Taxonomy' ); ?></option>
											<?php foreach($getterms as $ke => $vl) { ?>
												<option <?php if ($get_tax && in_array($key . "?" . $vl->slug, $get_tax)) { echo "selected"; } ?> value="<?php echo $key . "?" . $vl->slug; ?>"><?php echo $vl->taxonomy . " : " . $vl->name; ?></option>
											<?php }
											} ?>
											</select>					
										<?php }
									} ?>
									<p class="description">
										<?php echo esc_html( 'Show posts associated with certain taxonomy' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Tags' ); ?></th>
								<td>
									<?php
									$tags = get_tags();
									if(isset($get_ps_setting['tags'])) {
										$get_tags = $get_ps_setting['tags'];	
									} else {
										$get_tags = false;
									}
									?>
									<select name="ps_setting[<?php echo $slider_no; ?>][tags][]" multiple class="<?php echo esc_attr( 'regular-text' ); ?>">
									<option value="">Select Tags</option>
									<?php foreach($tags as $key => $val) { ?>
										<option <?php if ($get_tags && in_array($val->slug, $get_tags)) { echo "selected"; } ?> value="<?php echo $val->slug; ?>"><?php echo $val->name; ?></option>
									<?php } ?>
									</select>
									<p class="description">
										<?php echo esc_html( 'Display posts that have this tag' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Search String' ); ?></th>
								<td>
									<?php
									if(isset($get_ps_setting['search_string'])) {
										$get_search_string = $get_ps_setting['search_string'];	
									} else {
										$get_search_string = false;
									}
									?>
									<input type="text" name="ps_setting[<?php echo $slider_no; ?>][search_string]" class="<?php echo esc_attr( 'regular-text' ); ?>" value="<?php echo esc_attr($get_search_string); ?>" />
									<p class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Show posts based on a keyword search' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Template Setting' ); ?></th>
								<td>
									<p class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Dynemic tags : below dynemic tags replace with real content. You can update below HTML as per your requirement.' ); ?>
									</p>
									<p>
										<strong>
											<code title="<?php echo esc_attr( 'Dynemic tag for title, get_the_title()' ); ?>">%title%</code>
											<code title="<?php echo esc_attr( 'Dynemic tag for title, get_the_date()' ); ?>">%date%</code>
											<code title="<?php echo esc_attr( 'Dynemic tag for link, get_permalink()' ); ?>">%permalink%</code>
											<code title="<?php echo esc_attr( 'Dynemic tag for content, get_the_content()' ); ?>">%content%</code>
											<code title="<?php echo esc_attr( 'Dynemic tag for excerpt, get_the_excerpt()' ); ?>">%excerpt%</code>
											<code title="<?php echo esc_attr( 'Dynemic tag for feature image' ); ?>">%feature_img%</code>
											<code title="<?php echo esc_attr( 'Dynemic tag for previous button' ); ?>">%author_name%</code>
											<code title="<?php echo esc_attr( 'Dynemic tag for next button' ); ?>">%author_posts_url%</code>
										</strong>
									</p>
									<br>
									<?php
									if(isset($get_ps_setting['template_setting']) && $get_ps_setting['template_setting'] != '') {
										$template_setting = $get_ps_setting['template_setting'];
									} else {
										$template_setting = '<div class="ps-box animated flipInX">
<div class="pad">
 <img class="image" src="%feature_img|thumbnail%">
 <div class="content">
   <h2 class="title">%title%</h2>
   <p><span class="date">%date%</span></p>
   <div class="excerpt">%excerpt%</div>
   <a class="readmore" href="%permalink%">Read more</a>
 </div>
</div>
</div>';
									}
									$settings = array(
										'media_buttons' => false,
										'quicktags' => false,
										'tinymce' => false
									);
									$editor_id = "ps_setting[".$slider_no."][template_setting]";
									wp_editor( $template_setting, $editor_id, $settings );
									?>
									<p class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Animation : below classes you can use for animation.' ); ?>
									</p>
									<p>
										<strong>
											<code><?php echo esc_html( 'bounceInDown' ); ?></code>
											<code><?php echo esc_html( 'bounceInLeft' ); ?></code>
											<code><?php echo esc_html( 'bounceInRight' ); ?></code>
											<code><?php echo esc_html( 'bounceInUp' ); ?></code>
											<code><?php echo esc_html( 'fadeIn' ); ?></code>
											<code><?php echo esc_html( 'fadeInDown' ); ?></code>
											<code><?php echo esc_html( 'fadeInLeft' ); ?></code>
											<code><?php echo esc_html( 'fadeInRight' ); ?></code>
											<code><?php echo esc_html( 'fadeInUp' ); ?></code>
											<code><?php echo esc_html( 'flipInX' ); ?></code>
											<code><?php echo esc_html( 'lightSpeedIn' ); ?></code>
											<code><?php echo esc_html( 'rotateInDownLeft' ); ?></code>
											<code><?php echo esc_html( 'rotateInDownRight' ); ?></code>
											<code><?php echo esc_html( 'rotateInUpLeft' ); ?></code>
											<code><?php echo esc_html( 'rotateInUpRight' ); ?></code>
											<code><?php echo esc_html( 'slideInUp' ); ?></code>
											<code><?php echo esc_html( 'slideInDown' ); ?></code>
											<code><?php echo esc_html( 'slideInLeft' ); ?></code>
											<code><?php echo esc_html( 'slideInRight' ); ?></code>
											<code><?php echo esc_html( 'zoomIn' ); ?></code>
											<code><?php echo esc_html( 'zoomInDown' ); ?></code>
											<code><?php echo esc_html( 'zoomInLeft' ); ?></code>
											<code><?php echo esc_html( 'zoomInRight' ); ?></code>
											<code><?php echo esc_html( 'zoomInUp' ); ?></code>
											<code><?php echo esc_html( 'rollIn' ); ?></code>
										</strong>
									</p>
									<p class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Animation : below classes you can use for animation.' ); ?>
									</p>
									<p class="<?php echo esc_attr( 'description' ); ?>">
										OR <a href="<?php echo esc_url( 'https://daneden.github.io/animate.css/' ); ?>" target="_blank"><?php echo esc_html( 'click here' ); ?></a>
										<?php echo esc_html( 'for more animation effect' ); ?>
										<a href="<?php echo esc_url( 'https://daneden.github.io/animate.css/' ); ?>" target="_blank"><?php echo esc_html( 'https://daneden.github.io/animate.css/' ); ?></a>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Design Option' ); ?></th>
								<td class="<?php echo esc_attr( 'design-option' ); ?>">
									<?php
									if(isset($get_ps_setting['design_option'])) {
										$get_design_option = $get_ps_setting['design_option'];	
									}
									if($get_design_option == '') {
										$get_design_option = 'fullwidth';
									}
									?>
									<p class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'If you update design option, you have to replace sortcode' ); ?>
									</p>
									<input id="<?php echo esc_attr( 'fullwidth' ); ?>" type="radio" <?php if($get_design_option == 'fullwidth') echo 'checked'; ?> name="ps_setting[<?php echo $slider_no; ?>][design_option]" value="<?php echo esc_attr( 'fullwidth' ); ?>">
									<label for="fullwidth" class="<?php echo esc_attr( 'fullwidth button' ); if($get_design_option == 'fullwidth') echo esc_attr( ' checked' ); ?>">
										<?php echo esc_html( 'Full width' ); ?>
									</label>
									<input id="<?php echo esc_attr( 'fixwidth' ); ?>" type="radio" <?php if($get_design_option == 'fixwidth') echo 'checked'; ?> name="ps_setting[<?php echo $slider_no; ?>][design_option]" value="<?php echo esc_attr( 'fixwidth' ); ?>">
									<label for="fixwidth" class="<?php echo esc_attr( 'fixwidth button' ); if($get_design_option == 'fixwidth') echo esc_attr( ' checked' ); ?>">
										<?php echo esc_html( 'Fix width' ); ?>
									</label>
									<input id="<?php echo esc_attr( 'insidebar' ); ?>" type="radio" <?php if($get_design_option == 'insidebar') echo 'checked'; ?> name="ps_setting[<?php echo $slider_no; ?>][design_option]" value="<?php echo esc_attr( 'insidebar' ); ?>">
									<label for="insidebar" class="<?php echo esc_attr( 'insidebar button' ); if($get_design_option == 'insidebar') echo esc_attr( ' checked' ); ?>">
										<?php echo esc_html( 'In Sidebar' ); ?>
									</label>
									<input id="<?php echo esc_attr( 'carousel2' ); ?>" type="radio" <?php if($get_design_option == 'carousel2') echo 'checked'; ?> name="ps_setting[<?php echo $slider_no; ?>][design_option]" value="<?php echo esc_attr( 'carousel2' ); ?>">
									<label for="carousel2" class="<?php echo esc_attr( 'carousel2 button' ); if($get_design_option == 'carousel2') echo esc_attr( ' checked' ); ?>">
										<?php echo esc_html( 'Carousel 2' ); ?>
									</label>
									<input id="<?php echo esc_attr( 'carousel3' ); ?>" type="radio" <?php if($get_design_option == 'carousel3') echo 'checked'; ?> name="ps_setting[<?php echo $slider_no; ?>][design_option]" value="<?php echo esc_attr( 'carousel3' ); ?>">
									<label for="carousel3" class="<?php echo esc_attr( 'carousel3 button' ); if($get_design_option == 'carousel3') echo esc_attr( ' checked' ); ?>">
										<?php echo esc_html( 'Carousel 3' ); ?>
									</label>
									<input id="<?php echo esc_attr( 'carousel4' ); ?>" type="radio" <?php if($get_design_option == 'carousel4') echo 'checked'; ?> name="ps_setting[<?php echo $slider_no; ?>][design_option]" value="<?php echo esc_attr( 'carousel4' ); ?>">
									<label for="carousel4" class="<?php echo esc_attr( 'carousel4 button' ); if($get_design_option == 'carousel4') echo esc_attr( ' checked' ); ?>">
										<?php echo esc_html( 'Carousel 4' ); ?>
									</label>
									<input id="<?php echo esc_attr( 'imginbg' ); ?>" type="radio" <?php if($get_design_option == 'imginbg') echo 'checked'; ?> name="ps_setting[<?php echo $slider_no; ?>][design_option]" value="<?php echo esc_attr( 'imginbg' ); ?>">
									<label for="imginbg" class="<?php echo esc_attr( 'imginbg button' ); if($get_design_option == 'imginbg') echo esc_attr( ' checked' ); ?>">
										<?php echo esc_html( 'Background image' ); ?>
									</label>
									<input id="<?php echo esc_attr( 'nodesign' ); ?>" type="radio" <?php if($get_design_option == 'nodesign') echo 'checked'; ?> name="ps_setting[<?php echo $slider_no; ?>][design_option]" value="<?php echo esc_attr( 'nodesign' ); ?>">
									<label for="nodesign" class="<?php echo esc_attr( 'nodesign button' ); if($get_design_option == 'nodesign') echo esc_attr( ' checked' ); ?>">
										<?php echo esc_html( 'No design' ); ?>
									</label>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Excerpt length' ); ?></th>
								<td>
									<?php
									if(isset($get_ps_setting['excerpt_length'])) {
										$get_excerpt_length = $get_ps_setting['excerpt_length'];	
									} else {
										$get_excerpt_length = 100;
									}
									if($get_excerpt_length == '') {
										$get_excerpt_length = 100;
									}
									?>
									<input type="number" name="ps_setting[<?php echo $slider_no; ?>][excerpt_length]" class="<?php echo esc_attr( 'regular-text' ); ?>" value="<?php echo esc_attr($get_excerpt_length); ?>" />
									<p class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Default excerpt word length is 100 words. Change is as per your requirement.' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Hide Next/Prev' ); ?></th>
								<td>
									<?php
									if(isset($get_ps_setting['hide_next_prev'])) {
										$get_hide_next_prev = $get_ps_setting['hide_next_prev'];	
									} else {
										$get_hide_next_prev = false;
									}
									?>
									<input type="checkbox" <?php if($get_hide_next_prev == '1') echo 'checked'; ?> name="ps_setting[<?php echo $slider_no; ?>][hide_next_prev]" class="<?php echo esc_attr( 'regular-text' ); ?>" value="1" />
									<span class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Tick here for hide Next/Prev arrow' ); ?>
									</span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php echo esc_html( 'Hide Pagination' ); ?></th>
								<td>
									<?php
									if(isset($get_ps_setting['hide_pagi'])) {
										$get_hide_pagi = $get_ps_setting['hide_pagi'];	
									} else {
										$get_hide_pagi = false;
									}
									?>
									<input type="checkbox" <?php if($get_hide_pagi == '1') echo 'checked'; ?> name="ps_setting[<?php echo $slider_no; ?>][hide_pagi]" class="<?php echo esc_attr( 'regular-text' ); ?>" value="1" />
									<span class="<?php echo esc_attr( 'description' ); ?>">
										<?php echo esc_html( 'Tick here for hide pagination' ); ?>
									</span>
								</td>
							</tr>
						</table>
						<?php submit_button(); ?>
					</form>
				<?php }
				} else {
					/*Listing*/
					$del_slider_no = sanitize_text_field($_GET['del_slider_no']);

					/*Delete Slider*/
					if($del_slider_no) {
						update_option("ps_setting{$del_slider_no}", "blank");
					}

					$preview = sanitize_text_field($_GET['preview']);
					if($preview) {
						/*Get slider setting*/
						$get_ps_setting = get_option( "ps_setting{$preview}" );
						?>
						<div class="<?php echo esc_attr('content-box'); ?>">
							<a class="<?php echo esc_attr( 'button button-primary' ); ?>" href="<?php echo admin_url('/admin.php?page=postcs'); ?>">
								<?php echo esc_html('Remove Preview'); ?>
							</a>
							<?php echo do_shortcode('[post-cs id='.$preview.']'); ?>
						</div>
						<?php
					}
					?>
					<div id="col-left">
						<h3 class="<?php echo esc_attr( 'rateme' ); ?>">Don’t forget to rate this plugin if you like it, thanks!... :)</h3>
					</div>
					<div id="col-right">
						<table class="<?php echo esc_attr( 'wp-list-table widefat fixed striped posts' ) ?>">
							<thead>
								<tr>
									<th width="50"><?php echo esc_html( 'No.' ); ?></th>
									<th><?php echo esc_html( 'Sortcode' ); ?></th>
									<th width="100"></th>
									<th width="100"></th>
									<th width="100"></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								while(get_option( "ps_setting{$i}" )) {
									$get_ps_setting = get_option( "ps_setting{$i}" );
									if($get_ps_setting != 'blank') {
									?>
									<tr>
										<td><?php echo $i; ?></td>
										<td>
											<pre>
												<strong>CMS</strong> [post-cs id=<?php echo $i; ?>]
											</pre>
											<pre>
												<strong>PHP</strong> &lt;?php echo do_shortcode('[post-cs id=<?php echo $i; ?>]'); ?&gt;
											</pre>
										</td>
										<td>
											<a class="<?php echo esc_attr('button-primary'); ?>" href="<?php echo admin_url('/admin.php?page=postcs&preview='.$i); ?>">
												<?php echo esc_html( 'Preview' ); ?>
											</a>
										</td>
										<td>
											<a class="<?php echo esc_attr('button-primary'); ?>" href="<?php echo admin_url('/admin.php?page=postcs&slider_no='.$i); ?>">
												<?php echo esc_html( 'Setting' ); ?>
											</a>
										</td>
										<td>
											<a class="<?php echo esc_attr('button-primary'); ?>" href="<?php echo admin_url('/admin.php?page=postcs&del_slider_no='.$i); ?>">
												<?php echo esc_html( 'Delete' ); ?>
											</a>
										</td>
									</tr>
								    <?php
								    }
								    $i++;
								} ?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="5">
										<a href="<?php echo admin_url('/admin.php?page=postcs&slider_no='.$i); ?>" class="<?php echo esc_attr( 'button button-primary button-hero' ); ?>">
											<?php echo esc_html( 'Add New Slider' ); ?>
										</a>
									</th>
								</tr>
							</tfoot>
						</table>
					</div>
			<?php } ?>				
			</div>
	<?php }

}

$postcs_Admin = new postcs_Admin();