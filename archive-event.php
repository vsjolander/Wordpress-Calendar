<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package understrap
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

$container = get_theme_mod('understrap_container_type');
?>

<div class="wrapper" id="archive-wrapper">


    <!-- Do the left sidebar check -->
    <?php get_template_part('global-templates/left-sidebar-check'); ?>

    <main class="site-main" id="main">

        <div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">

            <div class="row">
                <div class="col-12 mb-4">
                    <header class="page-header">
                        <?php
                        echo '<h1 class="page-title">' . __('Kalender') . '</h1>';
                        ?>
                    </header><!-- .page-header -->
                </div>
            </div>

            <div class="row">

                <div class="col">
                    <?php if (have_posts()) : ?>

                        <div class="grid grid--two-column">
                            <?php /* Start the Loop */ ?>
                            <?php while (have_posts()) : the_post(); ?>

                                <?php

                                /*
                                 * Include the Post-Format-specific template for the content.
                                 * If you want to override this in a child theme, then include a file
                                 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                                 */

                                include plugin_dir_path(__FILE__) . 'loop-templates/content-event.php';
                                ?>

                            <?php endwhile; ?>
                        </div>

                    <?php else : ?>

                        <p>Vi kunde inte hitta något i kalendern...</p>

                    <?php endif; ?>
                </div>

            </div> <!-- .row -->

        </div><!-- #content -->

    </main><!-- #main -->

    <!-- The pagination component -->
    <?php understrap_pagination(); ?>

    <!-- Do the right sidebar check -->
    <?php get_template_part('global-templates/right-sidebar-check'); ?>


</div><!-- #archive-wrapper -->

<?php get_footer(); ?>
