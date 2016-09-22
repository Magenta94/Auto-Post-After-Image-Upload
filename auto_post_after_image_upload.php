<?php
/**
 * @package Auto Post With Image Upload
 * @version 1.1
 */
/*
Plugin Name: Auto Post With Image Upload
Plugin URI: https://github.com/Magenta94?tab=repositories
Description: This plugin will provide you the facility to create automated post when you will upload an image to your wordpress media gallery. Each time after uploading one media file upload one post will be created with attached this uploaded image automatically
Author: G. M. Davide Pizzighella
Version: 1.1
Author URI: https://github.com/Magenta94
*/

add_action('add_attachment', 'auto_post_after_image_upload'); // Wordpress Hook
add_action('delete_attachment', 'auto_post_after_image_delete');

/**
* Creates a post after an upload of an image
*/
function auto_post_after_image_upload($attachId)
{
    $attachment = get_post($attachId);
    $attachment_title = get_the_title($attach_id);

    // if the attach is not an image profile i can create a post because it is a gallery image
    if ($attachment_title != "profile-picture") {
        $image = wp_get_attachment_image_src( $attachId, 'large');
        $image_tag = '<p><img src="'.$image[0].'" height="120" /></p>';

        $postData = array(
            'post_title' => 'Nuova foto nella galleria immagini',
            'post_type' => 'post',
            'post_content' => $image_tag . 'La foto è stata caricata da ' . get_userdata($attachment->post_author)->display_name . ' con nome ' . $attachment->post_title,
            'post_category' => array('0'),
            'post_status' => 'publish'
        );

        // creates post
        $post_id = wp_insert_post($postData);
        

        // attach media to post
        wp_update_post(array(
            'ID' => $attachId,
            'post_parent' => $post_id,
        ));
    }

    return $attachId;
}

/**
* Deletes the post attached to the image cancelled
*/
function auto_post_after_image_delete($attachId) {
    // get post to delete
    $postID = wp_get_post_parent_id($attachId);

/*-----------FOR TESTING-------------------------
    $postData = array(
        'post_title' => 'ooooooooooooooooo',
        'post_type' => 'post',
        'post_content' => '' . $postID,
        'post_category' => array('0'),
        'post_status' => 'publish'
    );

    $post_id = wp_insert_post($postData);
------------------------------------------------*/
   
    wp_delete_post($postID); 
}