<?php
// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Clean up custom post type data
$ptype = 'jvwcu_item';

// Delete posts of this type
$posts = get_posts([
    'post_type' => $ptype,
    'numberposts' => -1,
    'post_status' => 'any',
]);

if ($posts) {
    foreach ($posts as $post) {
        // Force delete to trash bypass
        wp_delete_post($post->ID, true);
    }
}
