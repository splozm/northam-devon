<?php
/**
 * SEO Enhancements for Northam Devon
 *
 * Structured data (Schema.org JSON-LD) for businesses, venues, and events.
 * Sitemap exclusions, footer sitemap link, and alt text fallbacks.
 *
 * @package Northam
 * @since 1.0.4
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * =============================================================================
 * BUSINESS SCHEMA (LocalBusiness)
 * =============================================================================
 */

/**
 * Output LocalBusiness schema for business single pages
 */
function northam_output_business_schema() {
	if ( ! is_singular( 'northam_business' ) ) {
		return;
	}

	$post_id = get_the_ID();

	// Skip archive-only businesses (they redirect anyway)
	$archive_only = get_post_meta( $post_id, '_northam_archive_only', true );
	if ( $archive_only ) {
		return;
	}

	// Get business category for @type selection
	$business_type = 'LocalBusiness';

	// Check for manual override meta field first
	$schema_override = get_post_meta( $post_id, '_northam_schema_type', true );
	if ( ! empty( $schema_override ) ) {
		$business_type = $schema_override;
	} else {
		// Check tags for "pub" indicator
		$tags = get_the_terms( $post_id, 'post_tag' );
		$is_pub = false;

		if ( $tags && ! is_wp_error( $tags ) ) {
			foreach ( $tags as $tag ) {
				if ( stripos( $tag->name, 'pub' ) !== false ) {
					$is_pub = true;
					break;
				}
			}
		}

		// Also check business title for "pub" as fallback
		if ( ! $is_pub && stripos( get_the_title(), 'pub' ) !== false ) {
			$is_pub = true;
		}

		if ( $is_pub ) {
			$business_type = 'BarOrPub';
		} else {
			// Map categories to specific schema types
			$categories = get_the_terms( $post_id, 'business_category' );

			if ( $categories && ! is_wp_error( $categories ) ) {
				$category_slug = strtolower( $categories[0]->slug );

				if ( strpos( $category_slug, 'restaurant' ) !== false || strpos( $category_slug, 'takeaway' ) !== false ) {
					$business_type = 'Restaurant';
				} elseif ( strpos( $category_slug, 'cafe' ) !== false || strpos( $category_slug, 'coffee' ) !== false ) {
					$business_type = 'CafeOrCoffeeShop';
				} elseif ( strpos( $category_slug, 'hair' ) !== false ) {
					$business_type = 'HairSalon';
				} elseif ( strpos( $category_slug, 'beauty' ) !== false || strpos( $category_slug, 'nail' ) !== false ) {
					$business_type = 'BeautySalon';
				}
			}
		}
	}

	// Build schema
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => $business_type,
		'name'     => get_the_title(),
		'url'      => get_permalink(),
	);

	// Description from excerpt
	if ( has_excerpt() ) {
		$schema['description'] = get_the_excerpt();
	}

	// Featured image
	if ( has_post_thumbnail() ) {
		$image_url = get_the_post_thumbnail_url( $post_id, 'full' );
		if ( $image_url ) {
			$schema['image'] = $image_url;
		}
	}

	// Address
	$address = get_post_meta( $post_id, '_northam_address', true );
	if ( ! empty( $address ) ) {
		$schema['address'] = array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $address,
			'addressLocality' => 'Northam',
			'addressRegion'   => 'Devon',
			'addressCountry'  => 'GB',
		);
	}

	// Geo coordinates
	$lat = get_post_meta( $post_id, '_northam_lat', true );
	$lng = get_post_meta( $post_id, '_northam_lng', true );
	if ( ! empty( $lat ) && ! empty( $lng ) ) {
		$schema['geo'] = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => $lat,
			'longitude' => $lng,
		);
	}

	// Contact details
	$phone = get_post_meta( $post_id, '_northam_phone', true );
	if ( ! empty( $phone ) ) {
		$schema['telephone'] = $phone;
	}

	$email = get_post_meta( $post_id, '_northam_email', true );
	if ( ! empty( $email ) ) {
		$schema['email'] = $email;
	}

	// Social media / sameAs
	$same_as = array();

	$website = get_post_meta( $post_id, '_northam_website', true );
	if ( ! empty( $website ) ) {
		$same_as[] = $website;
	}

	$facebook = get_post_meta( $post_id, '_northam_facebook', true );
	if ( ! empty( $facebook ) ) {
		$same_as[] = $facebook;
	}

	$instagram = get_post_meta( $post_id, '_northam_instagram', true );
	if ( ! empty( $instagram ) ) {
		// Convert handle to full URL if needed
		if ( strpos( $instagram, 'http' ) === false && strpos( $instagram, '@' ) === 0 ) {
			$same_as[] = 'https://www.instagram.com/' . ltrim( $instagram, '@' );
		} elseif ( strpos( $instagram, 'http' ) === false ) {
			$same_as[] = 'https://www.instagram.com/' . $instagram;
		} else {
			$same_as[] = $instagram;
		}
	}

	$twitter = get_post_meta( $post_id, '_northam_twitter', true );
	if ( ! empty( $twitter ) ) {
		$same_as[] = $twitter;
	}

	if ( ! empty( $same_as ) ) {
		$schema['sameAs'] = $same_as;
	}

	// Opening hours
	$opening_hours_json = get_post_meta( $post_id, '_northam_opening_hours', true );
	if ( ! empty( $opening_hours_json ) ) {
		$opening_hours = json_decode( wp_unslash( $opening_hours_json ), true );

		if ( is_array( $opening_hours ) && ! empty( $opening_hours ) ) {
			$opening_hours_spec = array();

			$day_map = array(
				'monday'    => 'Monday',
				'tuesday'   => 'Tuesday',
				'wednesday' => 'Wednesday',
				'thursday'  => 'Thursday',
				'friday'    => 'Friday',
				'saturday'  => 'Saturday',
				'sunday'    => 'Sunday',
			);

			foreach ( $day_map as $key => $day_name ) {
				if ( isset( $opening_hours[ $key ]['from'] ) && isset( $opening_hours[ $key ]['to'] ) ) {
					$from = $opening_hours[ $key ]['from'];
					$to   = $opening_hours[ $key ]['to'];

					// Handle midnight or later as 23:59
					if ( $to === '00:00' || strtotime( $to ) < strtotime( $from ) ) {
						$to = '23:59';
					}

					$opening_hours_spec[] = array(
						'@type'     => 'OpeningHoursSpecification',
						'dayOfWeek' => $day_name,
						'opens'     => $from,
						'closes'    => $to,
					);
				}
			}

			if ( ! empty( $opening_hours_spec ) ) {
				$schema['openingHoursSpecification'] = $opening_hours_spec;
			}
		}
	}

	// Output schema
	northam_output_json_ld( $schema );
}
add_action( 'wp_head', 'northam_output_business_schema' );

/**
 * =============================================================================
 * VENUE SCHEMA (Place)
 * =============================================================================
 */

/**
 * Output Place schema for venue single pages
 */
function northam_output_venue_schema() {
	if ( ! is_singular( 'northam_venue' ) ) {
		return;
	}

	$post_id = get_the_ID();

	// Build schema
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Place',
		'name'     => get_the_title(),
		'url'      => get_permalink(),
	);

	// Description from excerpt
	if ( has_excerpt() ) {
		$schema['description'] = get_the_excerpt();
	}

	// Featured image
	if ( has_post_thumbnail() ) {
		$image_url = get_the_post_thumbnail_url( $post_id, 'full' );
		if ( $image_url ) {
			$schema['image'] = $image_url;
		}
	}

	// Address (always include at minimum locality/region)
	$address = get_post_meta( $post_id, '_northam_address', true );
	$schema['address'] = array(
		'@type'           => 'PostalAddress',
		'addressLocality' => 'Northam',
		'addressRegion'   => 'Devon',
		'postalCode'      => 'EX39',
		'addressCountry'  => 'GB',
	);

	// Add street address if available
	if ( ! empty( $address ) ) {
		$schema['address']['streetAddress'] = $address;
	}

	// Geo coordinates
	$lat = get_post_meta( $post_id, '_northam_lat', true );
	$lng = get_post_meta( $post_id, '_northam_lng', true );
	if ( ! empty( $lat ) && ! empty( $lng ) ) {
		$schema['geo'] = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => $lat,
			'longitude' => $lng,
		);
	}

	// Contact telephone
	$phone = get_post_meta( $post_id, '_northam_phone', true );
	if ( ! empty( $phone ) ) {
		$schema['telephone'] = $phone;
	}

	// Capacity
	$capacity = get_post_meta( $post_id, '_northam_capacity', true );
	if ( ! empty( $capacity ) ) {
		$schema['maximumAttendeeCapacity'] = intval( $capacity );
	}

	// Output schema
	northam_output_json_ld( $schema );
}
add_action( 'wp_head', 'northam_output_venue_schema' );

/**
 * =============================================================================
 * EVENT SCHEMA (Event)
 * =============================================================================
 */

/**
 * Output Event schema for Events Manager events
 */
function northam_output_event_schema() {
	if ( ! is_singular( 'event' ) ) {
		return;
	}

	if ( ! class_exists( 'EM_Event' ) || ! function_exists( 'em_get_event' ) ) {
		return;
	}

	$post_id = get_the_ID();
	$event   = em_get_event( $post_id, 'post_id' );

	if ( ! $event || ! $event->event_id ) {
		return;
	}

	// Build schema
	$schema = array(
		'@context'            => 'https://schema.org',
		'@type'               => 'Event',
		'name'                => get_the_title(),
		'url'                 => get_permalink(),
		'eventStatus'         => 'https://schema.org/EventScheduled',
		'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
	);

	// Description from excerpt or content
	if ( has_excerpt() ) {
		$schema['description'] = get_the_excerpt();
	} elseif ( ! empty( $event->post_content ) ) {
		// Fallback to content excerpt (first 200 chars)
		$content = wp_strip_all_tags( $event->post_content );
		$schema['description'] = wp_trim_words( $content, 30, '...' );
	}

	// Featured image
	if ( has_post_thumbnail() ) {
		$image_url = get_the_post_thumbnail_url( $post_id, 'full' );
		if ( $image_url ) {
			$schema['image'] = $image_url;
		}
	}

	// Start date/time
	$start_date = $event->event_start_date;
	$start_time = $event->event_start_time;

	// End date/time
	$end_date = $event->event_end_date;
	$end_time = $event->event_end_time;

	// Check if it's an all-day event (no time set or 00:00:00)
	$is_all_day = empty( $start_time ) || $start_time === '00:00:00';

	if ( $is_all_day ) {
		// All-day event: date only
		$schema['startDate'] = $start_date;
		if ( ! empty( $end_date ) ) {
			$schema['endDate'] = $end_date;
		}
	} else {
		// Time-specific event: ISO 8601 with timezone
		$timezone = new DateTimeZone( 'Europe/London' );

		try {
			$start_dt = new DateTime( $start_date . ' ' . $start_time, $timezone );
			$schema['startDate'] = $start_dt->format( 'c' );
		} catch ( Exception $e ) {
			$schema['startDate'] = $start_date;
		}

		if ( ! empty( $end_date ) && ! empty( $end_time ) ) {
			try {
				$end_dt = new DateTime( $end_date . ' ' . $end_time, $timezone );
				$schema['endDate'] = $end_dt->format( 'c' );
			} catch ( Exception $e ) {
				// Fallback to just date
			}
		}
	}

	// Location
	$venue_id = get_post_meta( $post_id, '_northam_event_venue', true );

	if ( ! empty( $venue_id ) ) {
		$venue_post = get_post( $venue_id );

		if ( $venue_post ) {
			$location_schema = array(
				'@type' => 'Place',
				'name'  => $venue_post->post_title,
			);

			// Venue address (always include at minimum locality/region)
			$venue_address = get_post_meta( $venue_id, '_northam_address', true );
			$location_schema['address'] = array(
				'@type'           => 'PostalAddress',
				'addressLocality' => 'Northam',
				'addressRegion'   => 'Devon',
				'postalCode'      => 'EX39',
				'addressCountry'  => 'GB',
			);

			// Add street address if available
			if ( ! empty( $venue_address ) ) {
				$location_schema['address']['streetAddress'] = $venue_address;
			}

			// Venue geo
			$venue_lat = get_post_meta( $venue_id, '_northam_lat', true );
			$venue_lng = get_post_meta( $venue_id, '_northam_lng', true );
			if ( ! empty( $venue_lat ) && ! empty( $venue_lng ) ) {
				$location_schema['geo'] = array(
					'@type'     => 'GeoCoordinates',
					'latitude'  => $venue_lat,
					'longitude' => $venue_lng,
				);
			}

			$schema['location'] = $location_schema;

			// Organizer (same as venue)
			$schema['organizer'] = array(
				'@type' => 'Organization',
				'name'  => $venue_post->post_title,
				'url'   => get_permalink( $venue_id ),
			);
		}
	} else {
		// Fallback location: Northam, Devon (with address for valid schema)
		$schema['location'] = array(
			'@type'   => 'Place',
			'name'    => 'Northam, Devon',
			'address' => array(
				'@type'           => 'PostalAddress',
				'addressLocality' => 'Northam',
				'addressRegion'   => 'Devon',
				'postalCode'      => 'EX39',
				'addressCountry'  => 'GB',
			),
		);
	}

	// Output schema
	northam_output_json_ld( $schema );
}
add_action( 'wp_head', 'northam_output_event_schema' );

/**
 * =============================================================================
 * SITEMAP EXCLUSION
 * =============================================================================
 */

/**
 * Exclude archive-only businesses from Rank Math sitemap
 */
function northam_exclude_archive_only_from_sitemap( $entity, $object_type, $object ) {
	// Only apply to northam_business posts
	if ( ! isset( $object->post_type ) || $object->post_type !== 'northam_business' ) {
		return $entity;
	}

	// Check if archive-only flag is set
	$archive_only = get_post_meta( $object->ID, '_northam_archive_only', true );

	if ( $archive_only ) {
		// Return false to exclude from sitemap
		return false;
	}

	return $entity;
}

// Guard check for Rank Math
if ( function_exists( 'rank_math' ) ) {
	add_filter( 'rank_math/sitemap/entry', 'northam_exclude_archive_only_from_sitemap', 10, 3 );
}

/**
 * =============================================================================
 * FOOTER SITEMAP LINK
 * =============================================================================
 */

/**
 * Add Sitemap link to footer next to Privacy Policy
 */
function northam_footer_sitemap_link( $items ) {
	$sitemap_link = '<li class="menu-item"><a href="/sitemap/">Sitemap</a></li>';
	return $items . $sitemap_link;
}

/**
 * Try the_privacy_policy_link filter first (Kadence may use this)
 */
function northam_add_sitemap_to_privacy_policy( $privacy_link ) {
	$sitemap_link = '<a href="/sitemap/">Sitemap</a>';

	if ( ! empty( $privacy_link ) ) {
		return $privacy_link . ' | ' . $sitemap_link;
	}

	return $sitemap_link;
}
add_filter( 'the_privacy_policy_link', 'northam_add_sitemap_to_privacy_policy' );

/**
 * Fallback: Add to Kadence footer menu if privacy policy link filter doesn't work
 */
function northam_kadence_footer_navigation( $items, $args ) {
	// Only add to footer menu
	if ( isset( $args->theme_location ) && $args->theme_location === 'footer' ) {
		$items .= northam_footer_sitemap_link( $items );
	}

	return $items;
}

// Only add if Kadence is active
if ( function_exists( 'kadence' ) ) {
	add_filter( 'wp_nav_menu_items', 'northam_kadence_footer_navigation', 10, 2 );
}

/**
 * =============================================================================
 * ALT TEXT FALLBACK
 * =============================================================================
 */

/**
 * Provide fallback alt text for images with empty alt attributes
 */
function northam_image_alt_fallback( $attr, $attachment, $size ) {
	// Only process if alt is empty
	if ( empty( $attr['alt'] ) ) {
		// Try attachment title first
		$attachment_title = get_the_title( $attachment->ID );

		if ( ! empty( $attachment_title ) ) {
			$attr['alt'] = $attachment_title;
		} else {
			// Fallback to parent post title
			$parent_id = wp_get_post_parent_id( $attachment->ID );

			if ( $parent_id ) {
				$parent_title = get_the_title( $parent_id );

				if ( ! empty( $parent_title ) ) {
					$attr['alt'] = $parent_title;
				}
			}
		}
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'northam_image_alt_fallback', 10, 3 );

/**
 * =============================================================================
 * HELPER FUNCTION
 * =============================================================================
 */

/**
 * Output JSON-LD schema markup
 *
 * @param array $schema Schema data
 */
function northam_output_json_ld( $schema ) {
	if ( empty( $schema ) ) {
		return;
	}

	echo "\n<!-- Northam Schema -->\n";
	echo '<script type="application/ld+json">';
	echo wp_json_encode( $schema, JSON_PRETTY_PRINT );
	echo '</script>';
	echo "
<!-- /Northam Schema -->
";
}
