<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cec-termine
 */


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

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main singleTermine">

		<?php
		while ( have_posts() ) :
			the_post();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
			echo '<header class="entry-header">';
			the_title( '<h1 class="entry-title">', '</h1>' );
			echo '</header>';
		?>
	<section class="postwrapper">

    <div class='col-t t-right'>
      <?php

        if ( !empty( get_field('termin_datum') ) ) {
          $termindatum = get_field('termin_datum');
          $datum = strtotime($termindatum);
          $datum_format = date('d.m.Y', $datum);
          $monatsindex = date('n', $datum);
          $tag = date('d.', $datum);
        }

        echo "<div class='datewrapper'>";
        echo "<span class='bigdate'>".$tag."<br><i>".$monate[$monatsindex]."</i></span>";
        echo "</div>";
        the_post_thumbnail("full");
        ?>
    </div><!-- .t-right -->

		<div class='col-t t-left'>
			<?php

        if ( !empty( get_field('termin_datum') ) ) {
          printf("<h5>Datum: %s</h5>", $datum_format );
        }

				the_content( sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'engel' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				) );
        ?>
		</div><!-- .t-right -->

		<div class="clear"></div>
	</section>
</article><!-- #post-<?php the_ID(); ?> -->
<?php
		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
