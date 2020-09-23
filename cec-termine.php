<?php

/**
 *
 * @link              https://herzog-webstudios.de/
 * @since             1.0.0
 * @package           Cec_Termine
 *
 * @wordpress-plugin
 * Plugin Name:       clean event cards
 * Plugin URI:        https://herzog-webstudios.de/
 * Description:       Simple Termin Karten.
 * Version:           1.0.0
 * Author:            Florian Herzog
 * Author URI:        https://herzog-webstudios.de/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cec-termine
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CEC_TERMINE_VERSION', '1.0.0' );

function activate_cec_termine() {

}
function deactivate_cec_termine() {

}
register_activation_hook( __FILE__, 'activate_cec_termine' );
register_deactivation_hook( __FILE__, 'deactivate_cec_termine' );

/**
 * Enqueue scripts and styles.
 */
function termine_scripts() {
	/* CSS */
  wp_enqueue_style( 'cec-style', plugin_dir_url( __FILE__ ) . 'public/css/cec-termine-public.css' );
	/* JS Scripte */
  wp_enqueue_script( 'cec-js', plugin_dir_url( __FILE__ ) . 'public/js/cec-termine-public.js', array(), '100', true );
}
add_action( 'wp_enqueue_scripts', 'termine_scripts' );

/**
 * CPT
 */
function create_posttype() {
  // custom post type Termine
	register_post_type( 'termine',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'Termine' ),
				'singular_name' => __( 'Termin' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'termine'),
      'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields')
		)
	);

}
add_action( 'init', 'create_posttype' );

/**
 * -----------------------------------------------------------------------------
 * Add Shortcodes
 * -----------------------------------------------------------------------------
 */
// [termine_zeigen]
function termine_zeigen_shortcode($attr, $content) {

    $atts = shortcode_atts( array(
			'cat' => '',
      'num' => ''
		), $atts );
    ob_start();

		//custom loop options vars
		$limit = -1;
		$today = strtotime("now");
		$datumHeute = date('Ymd', $today);
		$theorder = "ASC";
		$feld_abgleich = "termin_datum";

		// define query parameters based on attributes
		$options = array(
			 'post_type' => array ( 'termine'),
		    'posts_per_page' => $limit,
		    'meta_key' => $feld_abgleich,
		    'meta_compare' => '>',
		    'meta_value' => $datumHeute,
		    'orderby' => $feld_abgleich,
		    'order' => $theorder
		);

		$monate = array(
		            "leer",
		            "Jan",
		            "Feb",
		            "März",
		            "Apr",
		            "Mai",
		            "Juni",
		            "Juli",
		            "Aug",
		            "Sep",
		            "Okt",
		            "Nov",
		            "Dez",
		        );

		$query = new WP_Query( $options );
		?>
    <section class="termine-wrapper">
      <div class="container">

          <?php
					// run the loop based on the query
          if ( $query->have_posts() ) :
              while ( $query->have_posts() ) : $query->the_post();

								if ( !empty( get_field('termin_datum', $id) ) ) {
									$termindatum = get_field('termin_datum', $id);
									$datum = strtotime($termindatum);
									$monatsindex = date('n', $datum);
									$tag = date('d.', $datum);
									$jahr = date('Y', $datum);
								}

                //$year = get_the_date( 'Y' );
						    if( ! isset( $years[ $jahr ] ) ) {
									$years[ $jahr ] = array();
								}
						    $years[ $jahr ][] = array(
										'title' => get_the_title(),
										'permalink' => get_the_permalink(),
										'excerpt' => get_the_excerpt(),
										'monat' => $monate[$monatsindex],
										'tag' => $tag,
										'banner' => get_the_post_thumbnail_url(get_the_ID(),'full')
								);

          		endwhile;

          else :

            get_template_part( 'template-parts/content', 'none' );

          endif;

					foreach ($years as $the_year => $posts) {
						echo "<h2>$the_year</h2>";
						foreach ($posts as $index => $post) {
								echo '<div class="col-4 termine">';
								if (!empty($post['tag'])) {
									echo "<div class='datewrapper'>";
									echo "<span class='bigdate'>".$post['tag']."<br><i>".$post['monat']."</i></span>";
									echo "</div>";
								}
								printf("<a href='%s' class='allover'></a>", $post['permalink'] );
								printf("<div class='thumbnail' style='background-image:url(%s)'></div>",$post['banner']);
								echo '<div class="termin-content">';
								printf("<h4>%s</h4>", $post['title'] );
								printf("<p>%s</p>", substr($post['excerpt'],0,120) );
								printf("<span class='cec-more'>%s</span>", "Mehr Infos" );
								printf("<a href='%s' class='cec-pfeil'></a>", $post['permalink'] );
								echo '</div>';
								echo '</div>';
						}
					}
					//echo "<code><pre>";
					//var_dump($years);
					//echo "</pre></code>";
					?>
			</div>
		</section>

		<?php
    $code = ob_get_clean();

    return $code;
}
add_shortcode('termine_zeigen', 'termine_zeigen_shortcode');


function load_termin_template( $template ) {
    global $post;

    if ($post->post_type == 'termine') {
        /*
         * This is a 'termin' post
         * AND a 'single termin template' is not found on
         * theme or child theme directories, so load it
         * from our plugin directory.
         */
        return WP_PLUGIN_DIR  . '/cec-termine/public/single-termin.php';
    }
		/*
		if ( is_singular( 'my_custom_post_type' ) ) {
			$template = plugins_url( 'templates/my_custom_post_type.php', __FILE__ );
		}
		*/

    return $template;
}

add_filter( 'single_template', 'load_termin_template', 50,1 );
