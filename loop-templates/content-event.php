<?php
/**
 * Post rendering content according to caller of get_template_part.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<div class="col-12 col-md-6">
    <div class="card mb-5 <?php echo join( ' ', get_post_class( $class, $post_id ) ); ?>" id="post-<?php the_ID(); ?>">
        <a href="<?php echo esc_url( get_permalink( get_the_ID() ) );?>">
            <?php
                if (get_the_post_thumbnail( $post->ID, 'large' )) :
                    echo get_the_post_thumbnail( $post->ID, 'large' );
                else:
            ?>
                <img src="https://vosab.s3.amazonaws.com/rws/uploads/2020/03/rws-placeholder.jpg" alt="RWS Placeholder">
            <?php endif; ?>
        </a>

        <div class="card-body">
            <div class="entry-date"><?php echo trim(get_post_meta( get_the_ID(), 'vos-event-date', true)); ?></div>
            <div class="entry-header mt-1 mb-3">

                <?php
                the_title(
                    sprintf( '<div class="h4 entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
                    '</a></div>'
                );
                ?>

            </div><!-- .entry-header -->

            <div class="entry-content">

                <?php the_excerpt(); ?>

                <?php
                wp_link_pages(
                    array(
                        'before' => '<div class="page-links">' . __( 'Pages:', 'understrap' ),
                        'after'  => '</div>',
                    )
                );
                ?>

            </div><!-- .entry-content -->

            <footer class="entry-footer d-none">

                <?php understrap_entry_footer(); ?>

            </footer><!-- .entry-footer -->
        </div>

    </div><!-- #post-## -->
</div>
