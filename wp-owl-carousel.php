i<?php

/*
 *
 * @link              http://studio.croati.co
 * @since             1.0.0
 * @package           WP_Owl_Carousel
 *
 * @wordpress-plugin
 * Plugin Name:	      WP Owl Carousel
 * Description:       Simple carousel based on jquery Owl carousel
 * Plugin URI:        http://studio.croati.co
 * Version:           1.0.0
 * Author:            Tonino Jankov
 * Author URI:        http://studio.croati.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-owl-carousel
 * Domain Path:       /languages
 */

// If this file is called directly, abort.


if ( ! defined( 'WPINC' ) ) {
	die;
}


function wpc_enqueue_style() {
	wp_enqueue_style( 'wp-carousel-montserrat', 'http://fonts.googleapis.com/css?family=Montserrat', false ); 
	wp_enqueue_style( 'wp-carousel-style', plugins_url('assets/owl.carousel.css', __FILE__), false ); 
}

function wpc_enqueue_script() {
	wp_register_script('wp-carousel-script', plugins_url('assets/owl.carousel.min.js', __FILE__), array('jquery'), '1.0', true);
	wp_enqueue_script( 'wp-carousel-script' );
}

function print_wpc() {
	global $add_pro_carousel;
	if ( ! $add_pro_carousel )
		return;
	wpc_enqueue_style();
	wpc_enqueue_script();
}
	
// add_action( 'wp_enqueue_scripts', 'print_wpc' );
add_action( 'wp_enqueue_scripts', 'wpc_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'wpc_enqueue_script' );


add_action( 'init', 'create_quotation_post_type' );
 
function create_quotation_post_type() {
    $args = array(
                  'description' => 'Quotation Post Type',
                  'menu_position' => 5,
                  'show_ui' => true,
                  'show_in_menu' => true,
                  'exclude_from_search' => false,
                  'labels' => array(
                                    'name'=> 'Quotations',
                                    'singular_name' => 'Quotation',
                                    'add_new' => 'Add quotation',
                                    'add_new_item' => 'Add new quotation',
                                    'edit' => 'Edit quotations',
                                    'edit_item' => 'Edit quotation',
                                    'new-item' => 'New quotation',
                                    'view' => 'View quotations',
                                    'view_item' => 'View Quotation',
                                    'search_items' => 'Search quotations',
                                    'not_found' => 'No quotations found',
                                    'not_found_in_trash' => 'No quotations found in trash'
                                   ),
                 'public' => true,
                 'has_archive' => true,
                 'capability_type' => 'post',
                 'hierarchical' => false,
                 'rewrite' => array('slug' => 'quotations'),
                 'supports' => array('title', 'custom-fields')
                 );
    register_post_type( 'quotation' , $args );
}

add_shortcode( 'wp_carousel', 'carousel' );
function carousel( $atts ) {
	global $add_wp_carousel;
	$add_wp_carousel = true;
	
    ob_start();
    
    $a = shortcode_atts( array(
        'show' => '-1',
    ), $atts );
    
    $query = new WP_Query( array(
		'post_type' => 'quotation',
        'posts_per_page' => $a['show'],
        'order' => 'ASC',
        'orderby' => 'title',
    ) );
    
    if ( $query->have_posts() ) { 
	?>	
		
		<script type="text/javascript">			
			jQuery(document).ready(function() {			
				jQuery('.wp-carousel').owlCarousel({
				    loop:true,
				    items:1,
					autoplay:true,
					autoplayTimeout:3000,
					autoplayHoverPause:true,
					center:true,
					nav:true,
					navText:['<','>']
				});		
			});		
		</script>
		<style type="text/css">
			.quote-content {font-size:1.2rem; color:#555; font-style:italic;}
			.quote-author {font-size:1rem;text-align:right; margin-right:28px; color:#444;}
			.quote-content, .quote-author { font-family: 'Montserrat', sans-serif; }
			.wp-carousel {position:relative; }
			.wp-carousel-item {width:90%; margin:auto;}
			
			.wp-carousel .owl-controls {}
			.wp-carousel .owl-controls .owl-nav { width:100%;  position:absolute; top:30%;}
			.wp-carousel .owl-controls .owl-nav div { font-size:2rem; color:#ddd;}
			.wp-carousel .owl-controls .owl-nav .owl-prev {position:absolute; left:6px;}
			.wp-carousel .owl-controls .owl-nav .owl-next {position:absolute; right:6px;}
			.wp-carousel .owl-controls 
			.wp-carousel .owl-dots { position:absolute; bottom:12px; }
			.wp-carousel .owl-dots .owl-dot {
				display:inline-block;
			}
			.wp-carousel .owl-dots .owl-dot span {    
				background: #d6d6d6 none repeat scroll 0 0;
			    border-radius: 30px;
			    display: block;
			    height: 12px;
			    margin: 4px 5px;
			    transition: opacity 200ms ease 0s;
			    width: 12px;
			}
			.wp-carousel .owl-dots .owl-dot span { 
				background:#bbbbbb;
			}
			.wp-carousel .owl-controls {
			    text-align: center;
			}
		</style>		
        <div class="wp-carousel">
			
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>

            <div class="wp-carousel-item" style="">				
				<?php global $post; ?>
				<p class="quote-content"><?php echo get_post_meta($post->ID, 'content', true); ?></p>
				<p class="quote-author"><?php echo get_post_meta($post->ID, 'author', true); ?></p>
			</div>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div> 
        
		
        
    <?php $cposts = ob_get_clean();
    return $cposts;
    }
}





?>
