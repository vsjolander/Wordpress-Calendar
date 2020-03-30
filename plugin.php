<?php
/**
 * Plugin Name: VOS Event Calendar
 * Plugin URI:
 * Description: VOS Event Calendar
 * Author: Vilhelm Sjölander
 * Author URI:
 * Version: 1.0.0
 * License:
 * License URI:
 *
 * @package folkparken_event_calendar
 */

define('PLUGIN_VERSION', '1.0');

if (!defined('ABSPATH')) {
    exit;
}

function create_calendar_post_type()
{
    register_taxonomy_for_object_type('category', 'vos_event_calendar');
    register_taxonomy_for_object_type('post_tag', 'vos_event_calendar');
    register_post_type('event',
        array(
            'labels' => array(
                'name' => __('Calendar Event', 'vos_event_calendar'),
                'singular_name' => __('Calendar Event', 'vos_event_calendar'),
                'add_new' => __('Add New', 'vos_event_calendar'),
                'add_new_item' => __('Add New Calendar Event', 'vos_event_calendar'),
                'edit' => __('Edit', 'vos_event_calendar'),
                'edit_item' => __('Edit Calendar Event', 'vos_event_calendar'),
                'new_item' => __('New Calendar Event', 'vos_event_calendar'),
                'view' => __('View Calendar Event', 'vos_event_calendar'),
                'view_item' => __('View Calendar Event', 'vos_event_calendar'),
                'search_items' => __('Search Calendar Event', 'vos_event_calendar'),
                'not_found' => __('No Calendar Events found', 'vos_event_calendar'),
                'not_found_in_trash' => __('No Calendar Events found in Trash', 'vos_event_calendar')
            ),
            'public' => true,
            'rewrite' => array('slug' => __('calendar', 'vos_event_calendar')),
            'menu_icon' => 'dashicons-calendar',
            'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
            'has_archive' => 'kalender',
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'thumbnail'
            ), // Go to Dashboard Custom rws post for supports
            'can_export' => true, // Allows export in Tools > Export
            'taxonomies' => array(
                'post_tag',
                'category'
            ) // Add Category and Post Tags support
        ));

}

function vos_event_calendar_styles()
{
    $wp_scripts = wp_scripts();
    wp_enqueue_style(
        'vos-event-calendar-admin-ui-css',
        'https://ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css',
        false,
        PLUGIN_VERSION,
        false
    );
}

function vos_event_calendar_scripts()
{
    wp_register_script(
        'vos-event-calendar',
        plugins_url('/vos-event-calendar.js', __FILE__),
        array('jquery', 'jquery-ui-datepicker'),
        PLUGIN_VERSION,
        true
    );
    wp_enqueue_script('vos-event-calendar');
}

function vos_event_calendar_render_date_meta_box($post)
{
    wp_nonce_field('vos_event_calendar_metabox_nonce', 'vos_event_calendar_nonce');
    $event_date = get_post_meta($post->ID, 'vos-event-date', true);
    ?>
    <div class="meta-box-row">
        <input name="vos-event-date"
               id="vos-date-picker"
               type="text" <?php if (isset($event_date)) : ?>
            value="<?php echo $event_date; ?>"
        <?php endif; ?>
        />
    </div>
    <?php
}


function vos_event_calendar_add_meta_boxes()
{
    add_meta_box(
        'event_date_meta_box',
        'Event Date',
        'vos_event_calendar_render_date_meta_box',
        'event',
        'side'
    );
}

function vos_event_calendar_save_post($post_id)
{

    if (!isset($_POST['vos_event_calendar_nonce']) || !wp_verify_nonce($_POST['vos_event_calendar_nonce'], 'vos_event_calendar_metabox_nonce'))
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    if (isset($_POST['vos-event-date'])) {
        update_post_meta($post_id, 'vos-event-date', sanitize_text_field($_POST['vos-event-date']));
    }
}

function vos_event_template( $template )
{
    if ( is_post_type_archive('event') ) {
        $theme_files = array('archive-event.php', 'vos-event-calendar/archive-event.php');
        $exists_in_theme = locate_template($theme_files, false);
        if ( $exists_in_theme != '' ) {
            return $exists_in_theme;
        } else {
            return plugin_dir_path(__FILE__) . 'archive-event.php';
        }
    }
    return $template;
}


add_action('save_post', 'vos_event_calendar_save_post');
add_action('add_meta_boxes', 'vos_event_calendar_add_meta_boxes');
add_action('admin_enqueue_scripts', 'vos_event_calendar_scripts');
add_action('admin_print_styles', 'vos_event_calendar_styles');
add_action('init', 'create_calendar_post_type');
add_filter('template_include', 'vos_event_template');
