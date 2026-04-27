<?php
/**
 * Custom Meta Boxes for Northam CPTs
 *
 * @package Northam
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check if ACF is active
 */
function northam_is_acf_active() {
    return class_exists( 'ACF' );
}

/**
 * Register all meta boxes
 */
function northam_register_meta_boxes() {
    // Temporarily disabled ACF check to force meta boxes to show
    // if ( northam_is_acf_active() ) {
    //     return;
    // }

    // Debug: Log that function is being called
    error_log( 'Northam: Registering meta boxes for screen: ' . get_current_screen()->post_type );

    add_meta_box(
        'northam_business_details',
        __( 'Business Details', 'northam' ),
        'northam_business_meta_box_callback',
        'northam_business',
        'normal',
        'high'
    );
    add_meta_box(
        'northam_venue_details',
        __( 'Venue Details', 'northam' ),
        'northam_venue_meta_box_callback',
        'northam_venue',
        'normal',
        'high'
    );
    add_meta_box(
        'northam_attraction_details',
        __( 'Attraction Details', 'northam' ),
        'northam_attraction_meta_box_callback',
        'northam_attraction',
        'normal',
        'high'
    );
    add_meta_box(
        'northam_group_details',
        __( 'Group Details', 'northam' ),
        'northam_group_meta_box_callback',
        'northam_group',
        'normal',
        'high'
    );

    // Gallery for businesses, venues, attractions, and groups
    $gallery_post_types = array( 'northam_business', 'northam_venue', 'northam_attraction', 'northam_group' );
    foreach ( $gallery_post_types as $post_type ) {
        add_meta_box(
            'northam_gallery',
            __( 'Photo Gallery (max 10 images)', 'northam' ),
            'northam_gallery_meta_box_callback',
            $post_type,
            'normal',
            'default'
        );
    }

    // Regular Classes for venues
    add_meta_box(
        'northam_regular_classes',
        __( 'Regular Classes Schedule', 'northam' ),
        'northam_regular_classes_meta_box_callback',
        'northam_venue',
        'normal',
        'default'
    );

    error_log( 'Northam: Meta boxes registered successfully' );
}
add_action( 'add_meta_boxes', 'northam_register_meta_boxes' );

/**
 * Generate time select options
 */
function northam_time_select_options( $selected = '' ) {
    $times = array( '' => '-- Select --', 'closed' => 'Closed' );
    // Generate times from 00:00 to 23:30 in 30-minute intervals
    for ( $h = 0; $h < 24; $h++ ) {
        for ( $m = 0; $m < 60; $m += 30 ) {
            $time_24 = sprintf( '%02d:%02d', $h, $m );
            $time_12 = date( 'g:i A', strtotime( $time_24 ) );
            $times[ $time_24 ] = $time_12;
        }
    }
    $output = '';
    foreach ( $times as $value => $label ) {
        $output .= sprintf(
            '<option value="%s"%s>%s</option>',
            esc_attr( $value ),
            selected( $selected, $value, false ),
            esc_html( $label )
        );
    }
    return $output;
}

/**
 * Business meta box
 */
function northam_business_meta_box_callback( $post ) {
    wp_nonce_field( 'northam_business_meta', 'northam_business_nonce' );
    $address = get_post_meta( $post->ID, '_northam_address', true );
    $phone = get_post_meta( $post->ID, '_northam_phone', true );
    $email = get_post_meta( $post->ID, '_northam_email', true );
    $website = get_post_meta( $post->ID, '_northam_website', true );
    $facebook = get_post_meta( $post->ID, '_northam_facebook', true );
    $instagram = get_post_meta( $post->ID, '_northam_instagram', true );
    $twitter = get_post_meta( $post->ID, '_northam_twitter', true );
    $map_url = get_post_meta( $post->ID, '_northam_map_url', true );
    $archive_only = get_post_meta( $post->ID, '_northam_archive_only', true );

    // Get structured opening hours
    $hours_json = get_post_meta( $post->ID, '_northam_opening_hours', true );
    $hours = is_string( $hours_json ) ? json_decode( $hours_json, true ) : array();
    if ( ! is_array( $hours ) ) {
        $hours = array();
    }
    $days = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
    ?>
    <table class="form-table northam-meta-table">
        <tr>
            <th><label for="northam_archive_only"><?php esc_html_e( 'Listing Type', 'northam' ); ?></label></th>
            <td>
                <label>
                    <input type="checkbox" id="northam_archive_only" name="northam_archive_only" value="1" <?php checked( $archive_only, '1' ); ?> />
                    <?php esc_html_e( 'Archive only (no detail page - links to external website instead)', 'northam' ); ?>
                </label>
                <p class="description"><?php esc_html_e( 'Check this for simple directory listings that should link externally rather than have their own page.', 'northam' ); ?></p>
            </td>
        </tr>
        <tr><th><label for="northam_address"><?php esc_html_e( 'Address', 'northam' ); ?></label></th>
        <td><textarea id="northam_address" name="northam_address" rows="3" class="large-text"><?php echo esc_textarea( $address ); ?></textarea></td></tr>
        <tr><th><label for="northam_phone"><?php esc_html_e( 'Phone', 'northam' ); ?></label></th>
        <td><input type="tel" id="northam_phone" name="northam_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="northam_email"><?php esc_html_e( 'Email', 'northam' ); ?></label></th>
        <td><input type="email" id="northam_email" name="northam_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text"></td></tr>
        <tr><th colspan="2"><strong><?php esc_html_e( 'Opening Hours', 'northam' ); ?></strong></th></tr>
        <?php foreach ( $days as $day ) :
            $day_data = isset( $hours[ $day ] ) ? $hours[ $day ] : array();
            $from = is_array( $day_data ) && isset( $day_data['from'] ) ? $day_data['from'] : '';
            $to = is_array( $day_data ) && isset( $day_data['to'] ) ? $day_data['to'] : '';
            $is_closed = ( $from === 'closed' );
        ?>
        <tr>
            <th><label><?php echo esc_html( ucfirst( $day ) ); ?></label></th>
            <td>
                <select name="northam_hours[<?php echo $day; ?>][from]" class="northam-hours-from" style="width: 120px;">
                    <?php echo northam_time_select_options( $from ); ?>
                </select>
                <span class="northam-hours-separator" <?php echo $is_closed ? 'style="display:none;"' : ''; ?>>to</span>
                <select name="northam_hours[<?php echo $day; ?>][to]" class="northam-hours-to" style="width: 120px;" <?php echo $is_closed ? 'style="display:none;"' : ''; ?>>
                    <?php echo northam_time_select_options( $to ); ?>
                </select>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr><th colspan="2"><strong><?php esc_html_e( 'Location', 'northam' ); ?></strong></th></tr>
        <tr><th><label for="northam_map_url"><?php esc_html_e( 'Google Maps URL', 'northam' ); ?></label></th>
        <td><input type="url" id="northam_map_url" name="northam_map_url" value="<?php echo esc_url( $map_url ); ?>" class="large-text" placeholder="https://maps.google.com/?q=Your+Business+Name">
        <p class="description"><?php esc_html_e( 'Paste your Google Maps link here (optional - for "View on Map" button)', 'northam' ); ?></p></td></tr>
        <tr><th colspan="2"><strong><?php esc_html_e( 'Social Media', 'northam' ); ?></strong></th></tr>
        <tr><th><label for="northam_website"><?php esc_html_e( 'Website', 'northam' ); ?></label></th>
        <td><input type="url" id="northam_website" name="northam_website" value="<?php echo esc_url( $website ); ?>" class="large-text"></td></tr>
        <tr><th><label for="northam_facebook"><?php esc_html_e( 'Facebook', 'northam' ); ?></label></th>
        <td><input type="url" id="northam_facebook" name="northam_facebook" value="<?php echo esc_url( $facebook ); ?>" class="large-text"></td></tr>
        <tr><th><label for="northam_instagram"><?php esc_html_e( 'Instagram URL', 'northam' ); ?></label></th>
        <td><input type="url" id="northam_instagram" name="northam_instagram" value="<?php echo esc_url( $instagram ); ?>" class="large-text" placeholder="https://www.instagram.com/yourbusiness/"></td></tr>
        <tr><th><label for="northam_twitter"><?php esc_html_e( 'Twitter/X', 'northam' ); ?></label></th>
        <td><input type="url" id="northam_twitter" name="northam_twitter" value="<?php echo esc_url( $twitter ); ?>" class="large-text"></td></tr>
    </table>
    <script>
    jQuery(document).ready(function($) {
        $('.northam-hours-from').on('change', function() {
            var $row = $(this).closest('tr');
            var $separator = $row.find('.northam-hours-separator');
            var $toSelect = $row.find('.northam-hours-to');
            if ($(this).val() === 'closed' || $(this).val() === '') {
                $separator.hide();
                $toSelect.hide();
            } else {
                $separator.show();
                $toSelect.show();
            }
        });
    });
    </script>
    <?php
}

/**
 * Venue meta box
 */
function northam_venue_meta_box_callback( $post ) {
    wp_nonce_field( 'northam_venue_meta', 'northam_venue_nonce' );
    $address = get_post_meta( $post->ID, '_northam_address', true );
    $phone = get_post_meta( $post->ID, '_northam_phone', true );
    $email = get_post_meta( $post->ID, '_northam_email', true );
    $website = get_post_meta( $post->ID, '_northam_website', true );
    $facilities = get_post_meta( $post->ID, '_northam_facilities', true );
    $capacity = get_post_meta( $post->ID, '_northam_capacity', true );
    $lat = get_post_meta( $post->ID, '_northam_lat', true );
    $lng = get_post_meta( $post->ID, '_northam_lng', true );
    $enquiry_form = get_post_meta( $post->ID, '_northam_enquiry_form', true );
    ?>
    <table class="form-table northam-meta-table">
        <tr><th><label for="northam_address"><?php esc_html_e( 'Address', 'northam' ); ?></label></th>
        <td><textarea id="northam_address" name="northam_address" rows="3" class="large-text"><?php echo esc_textarea( $address ); ?></textarea></td></tr>
        <tr><th><label for="northam_phone"><?php esc_html_e( 'Phone', 'northam' ); ?></label></th>
        <td><input type="tel" id="northam_phone" name="northam_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="northam_email"><?php esc_html_e( 'Email', 'northam' ); ?></label></th>
        <td><input type="email" id="northam_email" name="northam_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="northam_website"><?php esc_html_e( 'Website', 'northam' ); ?></label></th>
        <td><input type="url" id="northam_website" name="northam_website" value="<?php echo esc_url( $website ); ?>" class="large-text"></td></tr>
        <tr><th><label for="northam_capacity"><?php esc_html_e( 'Capacity', 'northam' ); ?></label></th>
        <td><input type="number" id="northam_capacity" name="northam_capacity" value="<?php echo esc_attr( $capacity ); ?>" class="small-text"></td></tr>
        <tr><th><label for="northam_facilities"><?php esc_html_e( 'Facilities', 'northam' ); ?></label></th>
        <td><textarea id="northam_facilities" name="northam_facilities" rows="5" class="large-text"><?php echo esc_textarea( $facilities ); ?></textarea>
        <p class="description"><?php esc_html_e( 'One per line', 'northam' ); ?></p></td></tr>
        <tr><th colspan="2"><strong><?php esc_html_e( 'Location', 'northam' ); ?></strong></th></tr>
        <tr><th><label for="northam_lat"><?php esc_html_e( 'Latitude', 'northam' ); ?></label></th>
        <td><input type="text" id="northam_lat" name="northam_lat" value="<?php echo esc_attr( $lat ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="northam_lng"><?php esc_html_e( 'Longitude', 'northam' ); ?></label></th>
        <td><input type="text" id="northam_lng" name="northam_lng" value="<?php echo esc_attr( $lng ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="northam_enquiry_form"><?php esc_html_e( 'Fluent Form ID', 'northam' ); ?></label></th>
        <td><input type="number" id="northam_enquiry_form" name="northam_enquiry_form" value="<?php echo esc_attr( $enquiry_form ); ?>" class="small-text"></td></tr>
    </table>
    <?php
}


/**
 * Attraction meta box
 */
function northam_attraction_meta_box_callback( $post ) {
    wp_nonce_field( 'northam_attraction_meta', 'northam_attraction_nonce' );
    $website = get_post_meta( $post->ID, '_northam_website', true );
    $highlights = get_post_meta( $post->ID, '_northam_highlights', true );
    $lat = get_post_meta( $post->ID, '_northam_lat', true );
    $lng = get_post_meta( $post->ID, '_northam_lng', true );
    $map_url = get_post_meta( $post->ID, '_northam_map_url', true );
    $badge_text = get_post_meta( $post->ID, '_northam_badge_text', true );
    $badge_type = get_post_meta( $post->ID, '_northam_badge_type', true );
    $duration = get_post_meta( $post->ID, '_northam_duration', true );
    $distance = get_post_meta( $post->ID, '_northam_distance', true );
    ?>
    <table class="form-table northam-meta-table">
        <tr><th><label for="northam_website"><?php esc_html_e( 'External Website', 'northam' ); ?></label></th>
        <td><input type="url" id="northam_website" name="northam_website" value="<?php echo esc_url( $website ); ?>" class="large-text">
        <p class="description"><?php esc_html_e( 'If provided, "Learn More" button will link here instead of single page', 'northam' ); ?></p></td></tr>
        <tr><th colspan="2"><strong><?php esc_html_e( 'Card Display Information', 'northam' ); ?></strong></th></tr>
        <tr><th><label for="northam_duration"><?php esc_html_e( 'Duration', 'northam' ); ?></label></th>
        <td><input type="text" id="northam_duration" name="northam_duration" value="<?php echo esc_attr( $duration ); ?>" class="regular-text" placeholder="e.g. 2-4 hours, Half day">
        <p class="description"><?php esc_html_e( 'How long to spend at this attraction', 'northam' ); ?></p></td></tr>
        <tr><th><label for="northam_distance"><?php esc_html_e( 'Distance from Village', 'northam' ); ?></label></th>
        <td><input type="text" id="northam_distance" name="northam_distance" value="<?php echo esc_attr( $distance ); ?>" class="regular-text" placeholder="e.g. 0.5 miles from village">
        <p class="description"><?php esc_html_e( 'Distance from Northam village center', 'northam' ); ?></p></td></tr>
        <tr><th colspan="2"><strong><?php esc_html_e( 'Seasonal Badge (optional)', 'northam' ); ?></strong></th></tr>
        <tr><th><label for="northam_badge_text"><?php esc_html_e( 'Badge Text', 'northam' ); ?></label></th>
        <td><input type="text" id="northam_badge_text" name="northam_badge_text" value="<?php echo esc_attr( $badge_text ); ?>" class="regular-text" placeholder="e.g. Free Entry, Lifeguards May-Sept">
        <p class="description"><?php esc_html_e( 'Optional badge to display in top-right corner of card', 'northam' ); ?></p></td></tr>
        <tr><th><label for="northam_badge_type"><?php esc_html_e( 'Badge Style', 'northam' ); ?></label></th>
        <td><select id="northam_badge_type" name="northam_badge_type">
            <option value="default" <?php selected( $badge_type, 'default' ); ?>><?php esc_html_e( 'Default (Grey)', 'northam' ); ?></option>
            <option value="success" <?php selected( $badge_type, 'success' ); ?>><?php esc_html_e( 'Success (Green)', 'northam' ); ?></option>
            <option value="warning" <?php selected( $badge_type, 'warning' ); ?>><?php esc_html_e( 'Warning (Amber)', 'northam' ); ?></option>
            <option value="info" <?php selected( $badge_type, 'info' ); ?>><?php esc_html_e( 'Info (Blue)', 'northam' ); ?></option>
        </select></td></tr>
        <tr><th><label for="northam_highlights"><?php esc_html_e( 'Highlights', 'northam' ); ?></label></th>
        <td><textarea id="northam_highlights" name="northam_highlights" rows="5" class="large-text"><?php echo esc_textarea( $highlights ); ?></textarea>
        <p class="description"><?php esc_html_e( 'One per line', 'northam' ); ?></p></td></tr>
        <tr><th colspan="2"><strong><?php esc_html_e( 'Location', 'northam' ); ?></strong></th></tr>
        <tr><th><label for="northam_lat"><?php esc_html_e( 'Latitude', 'northam' ); ?></label></th>
        <td><input type="text" id="northam_lat" name="northam_lat" value="<?php echo esc_attr( $lat ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="northam_lng"><?php esc_html_e( 'Longitude', 'northam' ); ?></label></th>
        <td><input type="text" id="northam_lng" name="northam_lng" value="<?php echo esc_attr( $lng ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="northam_map_url"><?php esc_html_e( 'Google Maps URL', 'northam' ); ?></label></th>
        <td><input type="url" id="northam_map_url" name="northam_map_url" value="<?php echo esc_url( $map_url ); ?>" class="large-text" placeholder="https://maps.google.com/?q=Your+Attraction+Name">
        <p class="description"><?php esc_html_e( 'Paste your Google Maps link here (optional - for "View on Map" button)', 'northam' ); ?></p></td></tr>
    </table>
    <?php
}

/**
 * Community Group meta box
 */
function northam_group_meta_box_callback( $post ) {
    wp_nonce_field( 'northam_group_meta', 'northam_group_nonce' );
    $contact_name = get_post_meta( $post->ID, '_northam_contact_name', true );
    $contact_email = get_post_meta( $post->ID, '_northam_contact_email', true );
    $contact_phone = get_post_meta( $post->ID, '_northam_contact_phone', true );
    $meeting_time = get_post_meta( $post->ID, '_northam_meeting_time', true );
    $meeting_location = get_post_meta( $post->ID, '_northam_meeting_location', true );
    $website = get_post_meta( $post->ID, '_northam_website', true );
    $facebook = get_post_meta( $post->ID, '_northam_facebook', true );
    ?>
    <table class="form-table northam-meta-table">
        <tr><th colspan="2"><strong><?php esc_html_e( 'Contact', 'northam' ); ?></strong></th></tr>
        <tr><th><label for="northam_contact_name"><?php esc_html_e( 'Contact Name', 'northam' ); ?></label></th>
        <td><input type="text" id="northam_contact_name" name="northam_contact_name" value="<?php echo esc_attr( $contact_name ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="northam_contact_email"><?php esc_html_e( 'Contact Email', 'northam' ); ?></label></th>
        <td><input type="email" id="northam_contact_email" name="northam_contact_email" value="<?php echo esc_attr( $contact_email ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="northam_contact_phone"><?php esc_html_e( 'Contact Phone', 'northam' ); ?></label></th>
        <td><input type="tel" id="northam_contact_phone" name="northam_contact_phone" value="<?php echo esc_attr( $contact_phone ); ?>" class="regular-text"></td></tr>
        <tr><th colspan="2"><strong><?php esc_html_e( 'Meetings', 'northam' ); ?></strong></th></tr>
        <tr><th><label for="northam_meeting_time"><?php esc_html_e( 'Meeting Time', 'northam' ); ?></label></th>
        <td><input type="text" id="northam_meeting_time" name="northam_meeting_time" value="<?php echo esc_attr( $meeting_time ); ?>" class="regular-text"></td></tr>
        <tr><th><label for="northam_meeting_location"><?php esc_html_e( 'Meeting Location', 'northam' ); ?></label></th>
        <td><input type="text" id="northam_meeting_location" name="northam_meeting_location" value="<?php echo esc_attr( $meeting_location ); ?>" class="large-text"></td></tr>
        <tr><th colspan="2"><strong><?php esc_html_e( 'Online', 'northam' ); ?></strong></th></tr>
        <tr><th><label for="northam_website"><?php esc_html_e( 'Website', 'northam' ); ?></label></th>
        <td><input type="url" id="northam_website" name="northam_website" value="<?php echo esc_url( $website ); ?>" class="large-text"></td></tr>
        <tr><th><label for="northam_facebook"><?php esc_html_e( 'Facebook', 'northam' ); ?></label></th>
        <td><input type="url" id="northam_facebook" name="northam_facebook" value="<?php echo esc_url( $facebook ); ?>" class="large-text"></td></tr>
    </table>
    <?php
}

/**
 * Gallery meta box - Clean implementation
 */
function northam_gallery_meta_box_callback( $post ) {
    wp_nonce_field( 'northam_gallery_save', 'northam_gallery_nonce' );
    $gallery_ids = get_post_meta( $post->ID, '_northam_gallery', true );
    $ids_array = !empty($gallery_ids) ? explode(',', $gallery_ids) : array();

    wp_enqueue_media();
    ?>
    <div class="northam-gallery-container">
        <div id="northam-gallery-images" style="margin-bottom:15px;">
            <?php foreach($ids_array as $attachment_id):
                $attachment_id = intval($attachment_id);
                if($attachment_id > 0):
                    $image = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                    if($image): ?>
                        <div class="gallery-image" data-attachment-id="<?php echo $attachment_id; ?>" style="display:inline-block;margin:5px;position:relative;">
                            <img src="<?php echo esc_url($image[0]); ?>" style="width:100px;height:100px;object-fit:cover;border:1px solid #ddd;">
                            <button type="button" class="remove-image" style="position:absolute;top:-5px;right:-5px;background:#dc3232;color:#fff;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;">&times;</button>
                            <input type="hidden" name="northam_gallery_ids[]" value="<?php echo $attachment_id; ?>">
                        </div>
                    <?php endif;
                endif;
            endforeach; ?>
        </div>
        <button type="button" class="button northam-upload-gallery-button"><?php _e('Add Images'); ?></button>
        <p class="description"><?php _e('Maximum 10 images'); ?></p>
    </div>

    <script>
    jQuery(document).ready(function($){
        var mediaUploader;

        $('.northam-upload-gallery-button').on('click', function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media({
                title: 'Select Images',
                button: { text: 'Add to Gallery' },
                multiple: true
            });

            mediaUploader.on('select', function() {
                var attachments = mediaUploader.state().get('selection').toJSON();
                var currentCount = $('#northam-gallery-images .gallery-image').length;

                $.each(attachments, function(index, attachment) {
                    if (currentCount >= 10) return false;

                    // Check if already exists
                    if ($('.gallery-image[data-attachment-id="'+attachment.id+'"]').length > 0) return;

                    var imgUrl = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                    var html = '<div class="gallery-image" data-attachment-id="'+attachment.id+'" style="display:inline-block;margin:5px;position:relative;">' +
                        '<img src="'+imgUrl+'" style="width:100px;height:100px;object-fit:cover;border:1px solid #ddd;">' +
                        '<button type="button" class="remove-image" style="position:absolute;top:-5px;right:-5px;background:#dc3232;color:#fff;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;">&times;</button>' +
                        '<input type="hidden" name="northam_gallery_ids[]" value="'+attachment.id+'">' +
                        '</div>';

                    $('#northam-gallery-images').append(html);
                    currentCount++;
                });
            });

            mediaUploader.open();
        });

        $('#northam-gallery-images').on('click', '.remove-image', function() {
            $(this).parent().remove();
        });
    });
    </script>
    <?php
}

// Save functions
function northam_save_business_meta($post_id){
    if(!isset($_POST['northam_business_nonce'])||!wp_verify_nonce($_POST['northam_business_nonce'],'northam_business_meta')||defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE||!current_user_can('edit_post',$post_id))return;

    // Save archive_only checkbox
    $archive_only = isset($_POST['northam_archive_only']) ? '1' : '';
    update_post_meta($post_id, '_northam_archive_only', $archive_only);

    $f=['northam_address'=>'_northam_address','northam_phone'=>'_northam_phone','northam_email'=>'_northam_email','northam_website'=>'_northam_website','northam_facebook'=>'_northam_facebook','northam_instagram'=>'_northam_instagram','northam_twitter'=>'_northam_twitter','northam_map_url'=>'_northam_map_url'];
    foreach($f as $k=>$m){
        if(isset($_POST[$k])){
            $v=sanitize_textarea_field($_POST[$k]);
            if(strpos($m,'website')!==false||strpos($m,'facebook')!==false||strpos($m,'twitter')!==false||strpos($m,'map_url')!==false)$v=esc_url_raw($_POST[$k]);
            elseif(strpos($m,'email')!==false)$v=sanitize_email($_POST[$k]);
            elseif(strpos($m,'instagram')!==false)$v=sanitize_text_field($_POST[$k]);
            update_post_meta($post_id,$m,$v);
        }
    }

    // Save structured opening hours (from/to format)
    if ( isset( $_POST['northam_hours'] ) && is_array( $_POST['northam_hours'] ) ) {
        $hours = array();
        foreach ( $_POST['northam_hours'] as $day => $day_data ) {
            if ( is_array( $day_data ) ) {
                $from = isset( $day_data['from'] ) ? sanitize_text_field( $day_data['from'] ) : '';
                $to = isset( $day_data['to'] ) ? sanitize_text_field( $day_data['to'] ) : '';
                if ( ! empty( $from ) ) {
                    $hours[ sanitize_key( $day ) ] = array( 'from' => $from, 'to' => $to );
                }
            }
        }
        update_post_meta( $post_id, '_northam_opening_hours', wp_json_encode( $hours ) );
    }

    // Save gallery - new clean implementation
    if(isset($_POST['northam_gallery_nonce']) && wp_verify_nonce($_POST['northam_gallery_nonce'], 'northam_gallery_save')){
        if(isset($_POST['northam_gallery_ids']) && is_array($_POST['northam_gallery_ids'])){
            $gallery_ids = array_slice(array_map('absint', $_POST['northam_gallery_ids']), 0, 10);
            update_post_meta($post_id, '_northam_gallery', implode(',', $gallery_ids));
        } else {
            delete_post_meta($post_id, '_northam_gallery');
        }
    }
}
add_action('save_post_northam_business','northam_save_business_meta');

function northam_save_venue_meta($post_id){
    if(!isset($_POST['northam_venue_nonce'])||!wp_verify_nonce($_POST['northam_venue_nonce'],'northam_venue_meta')||defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE||!current_user_can('edit_post',$post_id))return;

    $f=['northam_address'=>'_northam_address','northam_phone'=>'_northam_phone','northam_email'=>'_northam_email','northam_website'=>'_northam_website','northam_facilities'=>'_northam_facilities','northam_capacity'=>'_northam_capacity','northam_lat'=>'_northam_lat','northam_lng'=>'_northam_lng','northam_enquiry_form'=>'_northam_enquiry_form'];
    foreach($f as $k=>$m){
        if(isset($_POST[$k])){
            $v=sanitize_textarea_field($_POST[$k]);
            if(strpos($m,'website')!==false)$v=esc_url_raw($_POST[$k]);
            elseif(strpos($m,'email')!==false)$v=sanitize_email($_POST[$k]);
            elseif(strpos($m,'capacity')!==false||strpos($m,'form')!==false)$v=absint($_POST[$k]);
            update_post_meta($post_id,$m,$v);
        }
    }

    // Save gallery
    if(isset($_POST['northam_gallery_nonce']) && wp_verify_nonce($_POST['northam_gallery_nonce'], 'northam_gallery_save')){
        if(isset($_POST['northam_gallery_ids']) && is_array($_POST['northam_gallery_ids'])){
            $gallery_ids = array_slice(array_map('absint', $_POST['northam_gallery_ids']), 0, 10);
            update_post_meta($post_id, '_northam_gallery', implode(',', $gallery_ids));
        } else {
            delete_post_meta($post_id, '_northam_gallery');
        }
    }
}
add_action('save_post_northam_venue','northam_save_venue_meta');

/**
 * Regular Classes meta box callback
 */
function northam_regular_classes_meta_box_callback( $post ) {
    wp_nonce_field( 'northam_regular_classes_save', 'northam_regular_classes_nonce' );

    $classes_json = get_post_meta( $post->ID, '_northam_regular_classes', true );
    $classes = $classes_json ? json_decode( $classes_json, true ) : array();
    $source_url = get_post_meta( $post->ID, '_northam_classes_source_url', true );

    $days = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );
    $categories = northam_get_class_categories();
    ?>
    <p class="description"><?php esc_html_e( 'Add regular classes/activities that take place at this venue. These will display in a schedule table on the venue page.', 'northam' ); ?></p>

    <!-- Auto-fetch section -->
    <div style="margin-bottom: 20px; padding: 15px; background: #e7f5f5; border: 1px solid #1d8a8a; border-radius: 4px;">
        <label for="northam_classes_source_url" style="font-weight: 600; display: block; margin-bottom: 8px;">
            Auto-fetch from URL (optional)
        </label>
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="url" id="northam_classes_source_url" name="northam_classes_source_url"
                   value="<?php echo esc_url( $source_url ); ?>"
                   placeholder="https://www.northamtowncouncil.gov.uk/..."
                   style="flex: 1;">
            <button type="button" id="northam-fetch-classes" class="button button-primary">
                Fetch Classes
            </button>
        </div>
        <p class="description" style="margin-top: 8px;">
            Enter a Northam Town Council URL to auto-populate classes. Click "Fetch Classes" to import, then save the post.
        </p>
        <div id="northam-fetch-status" style="margin-top: 10px; display: none;"></div>
    </div>

    <div id="northam-classes-container">
        <?php foreach ( $days as $day ) :
            $day_classes = isset( $classes[ $day ] ) ? $classes[ $day ] : array();
        ?>
        <div class="northam-day-section" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">
            <h4 style="margin: 0 0 10px 0; color: #1d8a8a;"><?php echo esc_html( $day ); ?></h4>
            <div class="northam-day-classes" data-day="<?php echo esc_attr( $day ); ?>">
                <?php if ( ! empty( $day_classes ) ) :
                    foreach ( $day_classes as $index => $class ) :
                        $class_category = isset( $class['category'] ) ? $class['category'] : northam_guess_class_category( $class['name'] ?? '' );
                    ?>
                    <div class="northam-class-row" style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center; flex-wrap: wrap;">
                        <input type="text" name="northam_classes[<?php echo esc_attr( $day ); ?>][<?php echo $index; ?>][time]"
                               value="<?php echo esc_attr( $class['time'] ?? '' ); ?>"
                               placeholder="e.g. 9:30 - 10:30" style="width: 110px;">
                        <input type="text" name="northam_classes[<?php echo esc_attr( $day ); ?>][<?php echo $index; ?>][name]"
                               value="<?php echo esc_attr( $class['name'] ?? '' ); ?>"
                               placeholder="Class name" style="width: 160px;">
                        <select name="northam_classes[<?php echo esc_attr( $day ); ?>][<?php echo $index; ?>][category]" style="width: 140px;">
                            <?php foreach ( $categories as $cat_slug => $cat_label ) : ?>
                                <option value="<?php echo esc_attr( $cat_slug ); ?>" <?php selected( $class_category, $cat_slug ); ?>>
                                    <?php echo esc_html( $cat_label ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="northam_classes[<?php echo esc_attr( $day ); ?>][<?php echo $index; ?>][frequency]"
                               value="<?php echo esc_attr( $class['frequency'] ?? 'Weekly' ); ?>"
                               placeholder="e.g. Weekly" style="width: 100px;">
                        <input type="text" name="northam_classes[<?php echo esc_attr( $day ); ?>][<?php echo $index; ?>][contact]"
                               value="<?php echo esc_attr( $class['contact'] ?? '' ); ?>"
                               placeholder="Contact" style="width: 140px;">
                        <button type="button" class="button northam-remove-class" style="color: #a00;">&times;</button>
                    </div>
                    <?php endforeach;
                endif; ?>
            </div>
            <button type="button" class="button northam-add-class" data-day="<?php echo esc_attr( $day ); ?>">+ Add Class</button>
        </div>
        <?php endforeach; ?>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Category options HTML
        var categoryOptions = '<?php
            $options = '';
            foreach ( $categories as $cat_slug => $cat_label ) {
                $options .= '<option value="' . esc_attr( $cat_slug ) . '">' . esc_html( $cat_label ) . '</option>';
            }
            echo $options;
        ?>';

        // Add class row
        $('.northam-add-class').on('click', function() {
            var day = $(this).data('day');
            var container = $(this).siblings('.northam-day-classes');
            var index = container.find('.northam-class-row').length;

            var row = '<div class="northam-class-row" style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center; flex-wrap: wrap;">' +
                '<input type="text" name="northam_classes[' + day + '][' + index + '][time]" placeholder="e.g. 9:30 - 10:30" style="width: 110px;">' +
                '<input type="text" name="northam_classes[' + day + '][' + index + '][name]" placeholder="Class name" style="width: 160px;">' +
                '<select name="northam_classes[' + day + '][' + index + '][category]" style="width: 140px;">' + categoryOptions + '</select>' +
                '<input type="text" name="northam_classes[' + day + '][' + index + '][frequency]" value="Weekly" placeholder="e.g. Weekly" style="width: 100px;">' +
                '<input type="text" name="northam_classes[' + day + '][' + index + '][contact]" placeholder="Contact" style="width: 140px;">' +
                '<button type="button" class="button northam-remove-class" style="color: #a00;">&times;</button>' +
                '</div>';

            container.append(row);
        });

        // Remove class row
        $(document).on('click', '.northam-remove-class', function() {
            $(this).closest('.northam-class-row').remove();
        });

        // Fetch classes from URL
        $('#northam-fetch-classes').on('click', function() {
            var url = $('#northam_classes_source_url').val();
            var $btn = $(this);
            var $status = $('#northam-fetch-status');

            if (!url) {
                $status.html('<span style="color: #a00;">Please enter a URL first.</span>').show();
                return;
            }

            $btn.prop('disabled', true).text('Fetching...');
            $status.html('<span style="color: #666;">Fetching classes from Town Council website...</span>').show();

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'northam_fetch_classes',
                    url: url,
                    nonce: '<?php echo wp_create_nonce( 'northam_fetch_classes' ); ?>'
                },
                success: function(response) {
                    if (response.success && response.data.classes) {
                        // Clear existing classes
                        $('.northam-day-classes').each(function() {
                            $(this).empty();
                        });

                        // Populate with fetched data
                        var classes = response.data.classes;
                        var totalClasses = 0;

                        $.each(classes, function(day, dayClasses) {
                            var $container = $('.northam-day-classes[data-day="' + day + '"]');
                            $.each(dayClasses, function(index, cls) {
                                // Build category select with correct option selected
                                var catSelect = '<select name="northam_classes[' + day + '][' + index + '][category]" style="width: 140px;">';
                                var catOptionsArray = categoryOptions.split('</option>');
                                catOptionsArray.forEach(function(opt) {
                                    if (opt.trim()) {
                                        var optVal = opt.match(/value="([^"]+)"/);
                                        if (optVal && optVal[1] === (cls.category || 'community-social')) {
                                            catSelect += opt.replace('<option', '<option selected') + '</option>';
                                        } else {
                                            catSelect += opt + '</option>';
                                        }
                                    }
                                });
                                catSelect += '</select>';

                                var row = '<div class="northam-class-row" style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center; flex-wrap: wrap;">' +
                                    '<input type="text" name="northam_classes[' + day + '][' + index + '][time]" value="' + (cls.time || '') + '" placeholder="e.g. 9:30 - 10:30" style="width: 110px;">' +
                                    '<input type="text" name="northam_classes[' + day + '][' + index + '][name]" value="' + (cls.name || '') + '" placeholder="Class name" style="width: 160px;">' +
                                    catSelect +
                                    '<input type="text" name="northam_classes[' + day + '][' + index + '][frequency]" value="' + (cls.frequency || 'Weekly') + '" placeholder="e.g. Weekly" style="width: 100px;">' +
                                    '<input type="text" name="northam_classes[' + day + '][' + index + '][contact]" value="' + (cls.contact || '') + '" placeholder="Contact" style="width: 140px;">' +
                                    '<button type="button" class="button northam-remove-class" style="color: #a00;">&times;</button>' +
                                    '</div>';
                                $container.append(row);
                                totalClasses++;
                            });
                        });

                        $status.html('<span style="color: #166534;">Success! Imported ' + totalClasses + ' classes with auto-categorization. Review categories and save the post.</span>').show();
                    } else {
                        $status.html('<span style="color: #a00;">Error: ' + (response.data.message || 'Failed to fetch classes') + '</span>').show();
                    }
                },
                error: function() {
                    $status.html('<span style="color: #a00;">Error: Could not connect to server.</span>').show();
                },
                complete: function() {
                    $btn.prop('disabled', false).text('Fetch Classes');
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * Save Regular Classes meta
 */
function northam_save_regular_classes_meta( $post_id ) {
    if ( ! isset( $_POST['northam_regular_classes_nonce'] ) ||
         ! wp_verify_nonce( $_POST['northam_regular_classes_nonce'], 'northam_regular_classes_save' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['northam_classes'] ) && is_array( $_POST['northam_classes'] ) ) {
        $classes = array();

        foreach ( $_POST['northam_classes'] as $day => $day_classes ) {
            $day = sanitize_text_field( $day );
            $classes[ $day ] = array();

            if ( is_array( $day_classes ) ) {
                foreach ( $day_classes as $class ) {
                    // Only save if at least name is filled
                    if ( ! empty( $class['name'] ) ) {
                        $classes[ $day ][] = array(
                            'time'      => sanitize_text_field( $class['time'] ?? '' ),
                            'name'      => sanitize_text_field( $class['name'] ?? '' ),
                            'frequency' => sanitize_text_field( $class['frequency'] ?? 'Weekly' ),
                            'contact'   => sanitize_text_field( $class['contact'] ?? '' ),
                        );
                    }
                }
            }

            // Remove empty days
            if ( empty( $classes[ $day ] ) ) {
                unset( $classes[ $day ] );
            }
        }

        if ( ! empty( $classes ) ) {
            update_post_meta( $post_id, '_northam_regular_classes', wp_json_encode( $classes ) );
        } else {
            delete_post_meta( $post_id, '_northam_regular_classes' );
        }
    }

    // Save source URL
    if ( isset( $_POST['northam_classes_source_url'] ) ) {
        $source_url = esc_url_raw( $_POST['northam_classes_source_url'] );
        if ( ! empty( $source_url ) ) {
            update_post_meta( $post_id, '_northam_classes_source_url', $source_url );
        } else {
            delete_post_meta( $post_id, '_northam_classes_source_url' );
        }
    }
}
add_action( 'save_post_northam_venue', 'northam_save_regular_classes_meta' );


function northam_save_attraction_meta($post_id){
    if(!isset($_POST['northam_attraction_nonce'])||!wp_verify_nonce($_POST['northam_attraction_nonce'],'northam_attraction_meta')||defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE||!current_user_can('edit_post',$post_id))return;

    foreach(['northam_website'=>'_northam_website','northam_highlights'=>'_northam_highlights','northam_lat'=>'_northam_lat','northam_lng'=>'_northam_lng','northam_map_url'=>'_northam_map_url','northam_badge_text'=>'_northam_badge_text','northam_badge_type'=>'_northam_badge_type','northam_duration'=>'_northam_duration','northam_distance'=>'_northam_distance'] as $k=>$m){
        if(isset($_POST[$k])){
            $v=sanitize_textarea_field($_POST[$k]);
            if(strpos($m,'website')!==false||strpos($m,'map_url')!==false)$v=esc_url_raw($_POST[$k]);
            elseif(strpos($m,'badge_text')!==false||strpos($m,'badge_type')!==false)$v=sanitize_text_field($_POST[$k]);
            update_post_meta($post_id,$m,$v);
        }
    }

    // Save gallery
    if(isset($_POST['northam_gallery_nonce']) && wp_verify_nonce($_POST['northam_gallery_nonce'], 'northam_gallery_save')){
        if(isset($_POST['northam_gallery_ids']) && is_array($_POST['northam_gallery_ids'])){
            $gallery_ids = array_slice(array_map('absint', $_POST['northam_gallery_ids']), 0, 10);
            update_post_meta($post_id, '_northam_gallery', implode(',', $gallery_ids));
        } else {
            delete_post_meta($post_id, '_northam_gallery');
        }
    }
}
add_action('save_post_northam_attraction','northam_save_attraction_meta');

function northam_save_group_meta($post_id){
    if(!isset($_POST['northam_group_nonce'])||!wp_verify_nonce($_POST['northam_group_nonce'],'northam_group_meta')||defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE||!current_user_can('edit_post',$post_id))return;

    foreach(['northam_contact_name'=>'_northam_contact_name','northam_contact_email'=>'_northam_contact_email','northam_contact_phone'=>'_northam_contact_phone','northam_meeting_time'=>'_northam_meeting_time','northam_meeting_location'=>'_northam_meeting_location','northam_website'=>'_northam_website','northam_facebook'=>'_northam_facebook'] as $k=>$m){
        if(isset($_POST[$k])){
            $v=sanitize_text_field($_POST[$k]);
            if(strpos($m,'website')!==false||strpos($m,'facebook')!==false)$v=esc_url_raw($_POST[$k]);
            elseif(strpos($m,'email')!==false)$v=sanitize_email($_POST[$k]);
            update_post_meta($post_id,$m,$v);
        }
    }

    // Save gallery
    if(isset($_POST['northam_gallery_nonce']) && wp_verify_nonce($_POST['northam_gallery_nonce'], 'northam_gallery_save')){
        if(isset($_POST['northam_gallery_ids']) && is_array($_POST['northam_gallery_ids'])){
            $gallery_ids = array_slice(array_map('absint', $_POST['northam_gallery_ids']), 0, 10);
            update_post_meta($post_id, '_northam_gallery', implode(',', $gallery_ids));
        } else {
            delete_post_meta($post_id, '_northam_gallery');
        }
    }
}
add_action('save_post_northam_group','northam_save_group_meta');

// Gallery saving is now integrated into each post type's save function above

/**
 * =============================================================================
 * EVENTS MANAGER - CUSTOM META BOX FOR MAPS URL
 * =============================================================================
 */

/**
 * Register meta box for Events Manager events
 */
function northam_register_event_meta_box() {
    if ( ! class_exists( 'EM_Events' ) ) {
        return;
    }

    add_meta_box(
        'northam_event_details',
        __( 'Event Location Link', 'northam' ),
        'northam_event_meta_box_callback',
        'event',
        'side',
        'default'
    );

    add_meta_box(
        'northam_event_venue',
        __( 'Event Venue/Host', 'northam' ),
        'northam_event_venue_meta_box_callback',
        'event',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'northam_register_event_meta_box' );

/**
 * Event meta box callback
 */
function northam_event_meta_box_callback( $post ) {
    wp_nonce_field( 'northam_event_meta', 'northam_event_nonce' );

    $map_url = get_post_meta( $post->ID, '_northam_event_map_url', true );
    ?>
    <p>
        <label for="northam_event_map_url"><strong><?php esc_html_e( 'Google Maps URL', 'northam' ); ?></strong></label>
        <input type="url" id="northam_event_map_url" name="northam_event_map_url"
               value="<?php echo esc_url( $map_url ); ?>"
               class="widefat"
               placeholder="https://maps.google.com/..." />
        <span class="description"><?php esc_html_e( 'Paste a Google Maps link for the event location.', 'northam' ); ?></span>
    </p>
    <?php
}

/**
 * Event venue/host meta box callback
 */
function northam_event_venue_meta_box_callback( $post ) {
    wp_nonce_field( 'northam_event_venue_meta', 'northam_event_venue_nonce' );

    $venue_id = get_post_meta( $post->ID, '_northam_event_venue', true );
    $venue_type = get_post_meta( $post->ID, '_northam_event_venue_type', true );

    // Get all businesses
    $businesses = get_posts( array(
        'post_type' => 'northam_business',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'post_status' => 'publish',
    ) );

    // Get all community venues
    $venues = get_posts( array(
        'post_type' => 'northam_venue',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'post_status' => 'publish',
    ) );
    ?>
    <p>
        <label for="northam_event_venue"><strong><?php esc_html_e( 'Select Business or Venue Hosting This Event', 'northam' ); ?></strong></label>
        <select id="northam_event_venue" name="northam_event_venue" class="widefat">
            <option value=""><?php esc_html_e( '-- None Selected --', 'northam' ); ?></option>

            <?php if ( ! empty( $businesses ) ) : ?>
                <optgroup label="<?php esc_attr_e( 'Businesses', 'northam' ); ?>">
                    <?php foreach ( $businesses as $business ) : ?>
                        <option value="<?php echo esc_attr( $business->ID ); ?>"
                                data-type="business"
                                <?php selected( $venue_id, $business->ID ); ?>>
                            <?php echo esc_html( $business->post_title ); ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endif; ?>

            <?php if ( ! empty( $venues ) ) : ?>
                <optgroup label="<?php esc_attr_e( 'Community Venues', 'northam' ); ?>">
                    <?php foreach ( $venues as $venue ) : ?>
                        <option value="<?php echo esc_attr( $venue->ID ); ?>"
                                data-type="venue"
                                <?php selected( $venue_id, $venue->ID ); ?>>
                            <?php echo esc_html( $venue->post_title ); ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endif; ?>
        </select>
        <input type="hidden" id="northam_event_venue_type" name="northam_event_venue_type" value="<?php echo esc_attr( $venue_type ); ?>" />
    </p>
    <p class="description"><?php esc_html_e( 'Select which business or community venue is hosting this event. This will make the event appear on their page.', 'northam' ); ?></p>

    <script>
    jQuery(document).ready(function($) {
        $('#northam_event_venue').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var venueType = selectedOption.data('type') || '';
            $('#northam_event_venue_type').val(venueType);
        });
    });
    </script>
    <?php
}

/**
 * Save event meta
 */
function northam_save_event_meta( $post_id ) {
    // Save map URL
    if ( isset( $_POST['northam_event_nonce'] ) && wp_verify_nonce( $_POST['northam_event_nonce'], 'northam_event_meta' ) ) {
        if ( ! defined( 'DOING_AUTOSAVE' ) && current_user_can( 'edit_post', $post_id ) ) {
            if ( isset( $_POST['northam_event_map_url'] ) ) {
                update_post_meta( $post_id, '_northam_event_map_url', esc_url_raw( $_POST['northam_event_map_url'] ) );
            }
        }
    }

    // Save venue relationship
    if ( isset( $_POST['northam_event_venue_nonce'] ) && wp_verify_nonce( $_POST['northam_event_venue_nonce'], 'northam_event_venue_meta' ) ) {
        if ( ! defined( 'DOING_AUTOSAVE' ) && current_user_can( 'edit_post', $post_id ) ) {
            if ( isset( $_POST['northam_event_venue'] ) ) {
                $venue_id = absint( $_POST['northam_event_venue'] );
                if ( $venue_id > 0 ) {
                    update_post_meta( $post_id, '_northam_event_venue', $venue_id );
                } else {
                    delete_post_meta( $post_id, '_northam_event_venue' );
                }
            }

            if ( isset( $_POST['northam_event_venue_type'] ) ) {
                $venue_type = sanitize_text_field( $_POST['northam_event_venue_type'] );
                if ( ! empty( $venue_type ) ) {
                    update_post_meta( $post_id, '_northam_event_venue_type', $venue_type );
                } else {
                    delete_post_meta( $post_id, '_northam_event_venue_type' );
                }
            }
        }
    }
}
add_action( 'save_post_event', 'northam_save_event_meta' );
