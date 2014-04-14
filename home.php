<?php

add_action( 'genesis_meta', 'locallife_home_genesis_meta' );
/*
 * Add widget support for homepage.
 */
function locallife_home_genesis_meta() {

	/* Remove the Genesis Loop for now (so custom home page stuff can come first) */
	remove_action( 'genesis_loop', 'genesis_do_loop' ); 
	
	add_action( 'genesis_after_header', 'locallife_home_top_helper' );
	add_action( 'genesis_loop', 'locallife_home_loop_helper' );

	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
	add_filter( 'body_class', 'add_body_class' );
	
	function add_body_class( $classes ) {
		$classes[] = 'locallife';
		return $classes;
	}

	/* Option 1: Add the Genesis Loop back in (Get all Posts) 
	 * We do because we use Bill Erickson's Genesis Grid Loop Plugin
	 * to nicely and responsively format them :)
	 *
	 * OPTION 2: Add custom loop to filter posts (by post-type,category, etc)
	 * instead of calling default genesis_do_loop
	 *
	 * OPTION 3: Add no action and only the above
	 * custom widget output will appear on the home page
	 */	
	//add_action( 'genesis_loop', 'genesis_do_loop' ); /* OPTION 1 - Genesis Loop (Use with Genesis Grid Loop plugin) */
	//add_action( 'genesis_loop', 'locallife_home_loop_data' ); /* OPTION 2 - Custom Loop (function below, formatting not done) */
	
}

function locallife_home_top_helper() {

	echo '<div id="home-featured">';
	
	/* featured top slider and top right */
	echo '<div class="featured-top">';

	if ( is_active_sidebar( 'home-slider' ) ) {
		echo '<div class="slider-left">';
		dynamic_sidebar( 'home-slider' );
		echo '</div><!-- end .slider-left -->';
	}

	if ( is_active_sidebar( 'home-top-right' ) ) {
		echo '<div class="featured-right dark-widget-area">';
		dynamic_sidebar( 'home-top-right' );
		echo '</div><!-- end .featured-right -->';
	}

	echo '</div><!-- end .featured-top -->' ;
	
}

function locallife_home_loop_helper() {

	/* featured middle section (CSS styles widgets as 3-column grid) */
	if ( is_active_sidebar( 'home-middle' ) ) {
		echo '<div class="featured">';
		dynamic_sidebar( 'home-middle' );
		echo '</div><!-- end .featured -->';	
	}

	echo '</div><!-- end #home-featured -->' ;
	
}

function locallife_home_loop_data() {

    $arg = array(
            /*'post_type' => 'guide', // this can be an array : array('guide','guide1',...)*/
            'posts_per_page' => 5,
            'order' => 'DESC',
            //'category_name' => 'Front',
            'post_status' => 'publish'
            );
    $query = new WP_Query($arg);
    if ( $query->have_posts() ) : 
        while ( $query->have_posts() ) : $query->the_post(); 
        ?>
            <div class="post">
				<div class="entry-wrap">
					<div class="entry-title">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
					</div>
					<div class="entry-content">
						<?php the_post_thumbnail('thumbnail'); ?>
						<div class="entry-post-excerpt">
							<?php the_excerpt(); ?>
						</div>
					</div>
				</div>
            </div>
        <?php
        endwhile;
    endif;
    wp_reset_query();
}

genesis();