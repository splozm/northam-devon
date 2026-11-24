<?php
/**
 * Template Name: What's On in Northam
 * Description: Week-based calendar view for events (matches React implementation)
 */

get_header();

// Get current filters from URL
$current_view = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'events'; // 'events' or 'classes'
$current_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : 'all';
$current_venue = isset($_GET['venue']) ? absint($_GET['venue']) : 0;

// Query venues marked as "is a venue" (for dropdown filter)
$venues = get_posts( array(
	'post_type'      => 'northam_venue',
	'posts_per_page' => -1,
	'orderby'        => 'title',
	'order'          => 'ASC',
	'post_status'    => 'publish',
	'meta_query'     => array(
		array(
			'key'   => '_northam_is_venue',
			'value' => '1',
		),
	),
) );
?>

<div class="northam-events-calendar-page">
	<!-- Hero Section -->
	<div class="northam-archive-hero northam-gradient-coastal">
		<div class="northam-archive-hero-overlay"></div>
		<div class="northam-archive-hero-content northam-container">
			<h1>What's On in Northam</h1>
			<p>Special events, local happenings, and regular activities — without the clutter.</p>
		</div>
	</div>

	<!-- View Tabs -->
	<div class="northam-view-tabs-wrapper">
		<div class="northam-container">
			<div class="northam-view-tabs">
				<?php
				$events_url = add_query_arg( array( 'view' => 'events', 'category' => $current_category !== 'all' ? $current_category : false, 'venue' => $current_venue > 0 ? $current_venue : false ), remove_query_arg( array( 'view', 'category', 'venue', 'week' ) ) );
				$classes_url = add_query_arg( array( 'view' => 'classes', 'category' => $current_category !== 'all' ? $current_category : false, 'venue' => $current_venue > 0 ? $current_venue : false ), remove_query_arg( array( 'view', 'category', 'venue', 'week' ) ) );
				?>
				<a href="<?php echo esc_url( $events_url ); ?>" class="northam-view-tab <?php echo $current_view === 'events' ? 'active' : ''; ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
						<line x1="16" y1="2" x2="16" y2="6"></line>
						<line x1="8" y1="2" x2="8" y2="6"></line>
						<line x1="3" y1="10" x2="21" y2="10"></line>
					</svg>
					<div>
						<div class="tab-title">What's On</div>
						<div class="tab-subtitle">One-off events & special occasions</div>
					</div>
				</a>
				<a href="<?php echo esc_url( $classes_url ); ?>" class="northam-view-tab <?php echo $current_view === 'classes' ? 'active' : ''; ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"></path>
						<path d="M21 3v5h-5"></path>
					</svg>
					<div>
						<div class="tab-title">Classes & Activities</div>
						<div class="tab-subtitle">Weekly sessions & regular meet-ups</div>
					</div>
				</a>
			</div>
		</div>
	</div>

	<!-- Seasonal Collection -->
	<div class="northam-seasonal-section">
		<div class="northam-container">
			<div class="northam-seasonal-card">
				<div class="northam-seasonal-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
					</svg>
				</div>
				<div class="northam-seasonal-content">
					<div class="northam-seasonal-label">SEASONAL</div>
					<h2 class="northam-seasonal-title">Christmas in Northam</h2>
					<p class="northam-seasonal-description">Festive markets, carols, crafts, and family events — gathered in one place.</p>
				</div>
				<a href="<?php echo esc_url( add_query_arg( array( 'view' => 'events', 'category' => 'christmas' ), remove_query_arg( array( 'view', 'category', 'venue', 'week' ) ) ) ); ?>" class="northam-seasonal-btn">
					Explore Christmas events
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="5" y1="12" x2="19" y2="12"></line>
						<polyline points="12 5 19 12 12 19"></polyline>
					</svg>
				</a>
			</div>
		</div>
	</div>

	<?php
	// Helper functions for filter URLs
	function northam_filter_url( $category = null, $venue = null ) {
		$params = array();
		if ( isset( $_GET['view'] ) ) {
			$params['view'] = sanitize_text_field( $_GET['view'] );
		}
		if ( $category !== null ) {
			$params['category'] = $category;
		} elseif ( isset( $_GET['category'] ) ) {
			$params['category'] = sanitize_text_field( $_GET['category'] );
		}
		if ( $venue !== null ) {
			if ( $venue > 0 ) {
				$params['venue'] = $venue;
			}
		} elseif ( isset( $_GET['venue'] ) && absint( $_GET['venue'] ) > 0 ) {
			$params['venue'] = absint( $_GET['venue'] );
		}
		if ( isset( $_GET['week'] ) ) {
			$params['week'] = intval( $_GET['week'] );
		}
		return add_query_arg( $params, remove_query_arg( array( 'view', 'category', 'venue', 'week' ) ) );
	}

	function northam_venue_url( $venue_id ) {
		return northam_filter_url( null, $venue_id );
	}
	?>

	<!-- Events Content -->
	<div class="northam-archive-content" style="padding-top: 3rem; padding-bottom: 3rem;">
		<div class="northam-container">
			<?php
			// Query events based on filter
			if ( class_exists( 'EM_Events' ) ) {
				$current_time = current_time( 'timestamp' );

			// Deep link: if ?e= is present, find that event and calculate its week
			$deep_link_slug = isset( $_GET['e'] ) ? sanitize_title( $_GET['e'] ) : '';
			$week_offset = 0;

			if ( ! empty( $deep_link_slug ) ) {
				
				// Find event by slug
				$deep_link_event = get_page_by_path( $deep_link_slug, OBJECT, 'event' );
				
				if ( $deep_link_event && $deep_link_event->post_status === 'publish' ) {
					// Get all future events that match this event's name/slug
					// This handles both recurring and non-recurring events
					$all_future = EM_Events::get( array(
						'scope' => 'future',
						'limit' => 999,
						'orderby' => 'event_start',
						'order' => 'ASC',
					) );
					
					
					// Find the next occurrence with matching slug
					$next_event = null;
					foreach ( $all_future as $em_event ) {
						$event_post = get_post( $em_event->post_id );
						if ( $event_post && $event_post->post_name === $deep_link_slug ) {
							$next_event = $em_event;
							break;
						}
					}
					
					// Use the next occurrence's date to calculate week
					if ( $next_event ) {
						$this_monday = strtotime( 'monday this week', $current_time );
						$event_monday = strtotime( 'monday this week', $next_event->start );
						$week_offset = max( 0, floor( ( $event_monday - $this_monday ) / ( 7 * 24 * 60 * 60 ) ) );
					} else {
					}
				}
			}


			// Deep link: if ?c= is present, find that class and calculate its week
			$deep_link_class_id = isset( $_GET['c'] ) ? sanitize_text_field( $_GET['c'] ) : '';
			$resolved_class_id = ''; // Will hold the actual ID of the matched class

			// Transform category for regular classes lookup (same as main calendar logic)
			$class_category_lookup = ( $current_category === 'regular-classes' ) ? 'all' : $current_category;

			if ( ! empty( $deep_link_class_id ) ) {
				// Look up to 8 weeks ahead for this class
				$found_week = null;
				for ( $check_week = 0; $check_week <= 7; $check_week++ ) {
					$check_start = strtotime( 'monday this week', $current_time ) + ( $check_week * 7 * 24 * 60 * 60 );
					$check_end = strtotime( 'sunday this week', $current_time ) + ( $check_week * 7 * 24 * 60 * 60 ) + ( 24 * 60 * 60 ) - 1;

					// Get classes for this week
					$check_classes = northam_get_regular_classes_for_week( $check_start, $check_end, $class_category_lookup, $current_venue );

					// Look through each day's classes
					foreach ( $check_classes as $day_key => $day_classes ) {
						foreach ( $day_classes as $class_item ) {
							$class_item_id = northam_class_id( $class_item, $day_key );

							// Exact match found
							if ( $class_item_id === $deep_link_class_id ) {
								$found_week = $check_week;
								$resolved_class_id = $class_item_id;
								break 3; // Break out of all loops
							}
						}
					}
				}

				// If no exact match, try fallback matching (name + venue + day, ignoring time)
				if ( $found_week === null ) {
					// Parse the class ID to extract components
					$id_parts = explode( '-', $deep_link_class_id );
					if ( count( $id_parts ) >= 3 ) {
						// Look for name + venue + day match
						for ( $check_week = 0; $check_week <= 7; $check_week++ ) {
							$check_start = strtotime( 'monday this week', $current_time ) + ( $check_week * 7 * 24 * 60 * 60 );
							$check_end = strtotime( 'sunday this week', $current_time ) + ( $check_week * 7 * 24 * 60 * 60 ) + ( 24 * 60 * 60 ) - 1;

							$check_classes = northam_get_regular_classes_for_week( $check_start, $check_end, $class_category_lookup, $current_venue );

							foreach ( $check_classes as $day_key => $day_classes ) {
								$day_abbr = strtolower( date( 'D', strtotime( $day_key ) ) );

								foreach ( $day_classes as $class_item ) {
									$class_item_id = northam_class_id( $class_item, $day_key );
									$name_match = strpos( strtolower( $class_item_id ), strtolower( $id_parts[0] ) ) !== false;
									$venue_match = count( $id_parts ) > 1 && strpos( strtolower( $class_item_id ), strtolower( $id_parts[1] ) ) !== false;
									$day_match = count( $id_parts ) > 2 && strpos( $class_item_id, $id_parts[2] ) !== false;

									if ( $name_match && $venue_match && $day_match ) {
										$found_week = $check_week;
										$resolved_class_id = $class_item_id;
										break 3;
									}
								}
							}
						}
					}

					// Final fallback: name + venue only
					if ( $found_week === null && count( $id_parts ) >= 2 ) {
						for ( $check_week = 0; $check_week <= 7; $check_week++ ) {
							$check_start = strtotime( 'monday this week', $current_time ) + ( $check_week * 7 * 24 * 60 * 60 );
							$check_end = strtotime( 'sunday this week', $current_time ) + ( $check_week * 7 * 24 * 60 * 60 ) + ( 24 * 60 * 60 ) - 1;

							$check_classes = northam_get_regular_classes_for_week( $check_start, $check_end, $class_category_lookup, $current_venue );

							foreach ( $check_classes as $day_key => $day_classes ) {
								foreach ( $day_classes as $class_item ) {
									$class_item_id = northam_class_id( $class_item, $day_key );
									$name_match = strpos( strtolower( $class_item_id ), strtolower( $id_parts[0] ) ) !== false;
									$venue_match = strpos( strtolower( $class_item_id ), strtolower( $id_parts[1] ) ) !== false;

									if ( $name_match && $venue_match ) {
										$found_week = $check_week;
										$resolved_class_id = $class_item_id;
										break 3;
									}
								}
							}
						}
					}
				}

				// Set week offset if we found the class
				if ( $found_week !== null ) {
					// Check if the class day/time has already passed this week
					// If so, show the next occurrence instead
					$class_week_start = strtotime( 'monday this week', $current_time ) + ( $found_week * 7 * 24 * 60 * 60 );

					// Get the matched class's day and time
					$check_classes = northam_get_regular_classes_for_week( $class_week_start, $class_week_start + ( 7 * 24 * 60 * 60 ), $class_category_lookup, $current_venue );
					foreach ( $check_classes as $day_key => $day_classes ) {
						foreach ( $day_classes as $class_item ) {
							if ( northam_class_id( $class_item, $day_key ) === $resolved_class_id ) {
								// Found the class - parse its time
								$class_timestamp = strtotime( $day_key );

								// Extract start time from "9:30 - 10:30" format
								$time_str = isset( $class_item['time'] ) ? $class_item['time'] : '';
								if ( preg_match( '/(\d{1,2}):(\d{2})/', $time_str, $matches ) ) {
									$hour = intval( $matches[1] );
									$minute = intval( $matches[2] );
									$class_timestamp = strtotime( $day_key . ' ' . $hour . ':' . $minute );
								}

								// If this class time has already passed, show next week instead
								if ( $class_timestamp < $current_time ) {
									$found_week++;
								}
								break 2;
							}
						}
					}

					$week_offset = $found_week;
				}
				// If no match found, week_offset stays 0 (current week, normal calendar)
			}

			// Allow manual week override via ?week= (only if no ?e= or ?c= present)
			if ( isset( $_GET['week'] ) && ! isset( $_GET['e'] ) && ! isset( $_GET['c'] ) ) {
				$week_offset = absint( $_GET['week'] );
			}

			// Calculate week start (Monday) and end (Sunday)
				$week_start = strtotime( 'monday this week', $current_time ) + ( $week_offset * 7 * 24 * 60 * 60 );
				$week_end = strtotime( 'sunday this week', $current_time ) + ( $week_offset * 7 * 24 * 60 * 60 ) + ( 24 * 60 * 60 ) - 1;

				// If "Classes" view is selected, skip EM events entirely
				if ( $current_view === 'classes' ) {
					$all_events = array();
				} else {
					// Get all future events (for "What's On" view)
					$query_args = array(
						'scope'   => 'future',
						'limit'   => 999,
						'orderby' => 'event_start',
						'order'   => 'ASC',
					);

					$all_events = EM_Events::get( $query_args );

					// Filter events by category if not "all"
					if ( $current_category !== 'all' ) {
						$filtered_events = array();
						foreach ( $all_events as $em_event ) {
							$categories = get_the_terms( $em_event->post_id, 'event-categories' );
							if ( $categories && ! is_wp_error( $categories ) ) {
								foreach ( $categories as $cat ) {
									if ( $cat->slug === $current_category ) {
										$filtered_events[] = $em_event;
										break;
									}
								}
							}
						}
						$all_events = $filtered_events;
					}
				}

				// Filter events by venue if selected
				if ( $current_venue > 0 ) {
					$filtered_events = array();
					foreach ( $all_events as $em_event ) {
						$linked_venue_id = get_post_meta( $em_event->post_id, '_northam_event_venue', true );
						if ( absint( $linked_venue_id ) === $current_venue ) {
							$filtered_events[] = $em_event;
						}
					}
					$all_events = $filtered_events;
				}

				// Group events by day of the week
				$events_by_day = array();
				$week_days = array();

				// Generate array of 7 days (Mon-Sun)
				for ( $i = 0; $i < 7; $i++ ) {
					$day_timestamp = $week_start + ( $i * 24 * 60 * 60 );
					$day_key = date( 'Y-m-d', $day_timestamp );
					$week_days[] = array(
						'timestamp' => $day_timestamp,
						'key' => $day_key,
						'day_name' => date( 'l', $day_timestamp ),
						'day_short' => date( 'D', $day_timestamp ),
						'day_num' => date( 'j', $day_timestamp ),
						'is_today' => date( 'Y-m-d', $day_timestamp ) === date( 'Y-m-d', $current_time ),
						'is_weekend' => in_array( date( 'N', $day_timestamp ), array( 6, 7 ) )
					);
					$events_by_day[$day_key] = array();
				}

				// Filter events into days
				$total_week_events = 0;
				foreach ( $all_events as $em_event ) {
					if ( $em_event->start >= $week_start && $em_event->start <= $week_end ) {
						$event_day = date( 'Y-m-d', $em_event->start );
						if ( isset( $events_by_day[$event_day] ) ) {
							// Wrap EM_Event in a standardized array format
							$events_by_day[$event_day][] = array(
								'type'        => 'event',
								'em_event'    => $em_event,
								'name'        => $em_event->event_name,
								'time'        => $em_event->output( '#_EVENTTIMES' ),
								'start_time'  => $em_event->event_start_time,
							);
							$total_week_events++;
						}
					}
				}

				// Merge regular classes if on "Classes" view
				$total_week_classes = 0;
				if ( $current_view === 'classes' ) {
					// On Classes view, filter by category if selected
					$class_category_filter = ( $current_category !== 'all' ) ? $current_category : 'all';
					$regular_classes = northam_get_regular_classes_for_week( $week_start, $week_end, $class_category_filter, $current_venue );
					// DEBUG: Output what we got
					echo '<!-- DEBUG: regular_classes count = ' . count($regular_classes) . ' -->';
					echo '<!-- DEBUG: regular_classes = ' . esc_html(print_r($regular_classes, true)) . ' -->';
					foreach ( $regular_classes as $day_key => $day_classes ) {
						if ( isset( $events_by_day[$day_key] ) ) {
							foreach ( $day_classes as $class ) {
								$events_by_day[$day_key][] = $class;
								$total_week_classes++;
							}
						}
					}

					// Sort each day's items by start time (numerically, not alphabetically)
					foreach ( $events_by_day as $day_key => &$day_items ) {
						usort( $day_items, function( $a, $b ) {
							// Extract time strings
							$time_a = isset( $a['start_time'] ) ? $a['start_time'] : ( isset( $a['time'] ) ? explode( ' - ', $a['time'] )[0] : '00:00' );
							$time_b = isset( $b['start_time'] ) ? $b['start_time'] : ( isset( $b['time'] ) ? explode( ' - ', $b['time'] )[0] : '00:00' );

							// Convert to minutes for numerical comparison
							$minutes_a = northam_time_to_minutes( trim( $time_a ) );
							$minutes_b = northam_time_to_minutes( trim( $time_b ) );

							return $minutes_a - $minutes_b;
						});
					}
					unset( $day_items );
				}

				// Format week heading like React: "d — d MMMM yyyy"
				$week_start_day = date( 'j', $week_start );
				$week_end_formatted = date( 'j F Y', $week_end );
				$week_heading = $week_start_day . ' — ' . $week_end_formatted;
				?>

				<!-- Calendar Header with Navigation -->
				<div class="northam-calendar-header">
					<div class="northam-calendar-header-info">
						<div class="northam-calendar-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M12 3v18m0-18l6 6m-6-6l-6 6"></path>
							</svg>
						</div>
						<div>
							<h3 class="northam-calendar-week-range"><?php echo esc_html( $week_heading ); ?></h3>
							<p class="northam-calendar-event-count">
								<?php
								$count_parts = array();
								if ( $total_week_events > 0 ) {
									$count_parts[] = $total_week_events . ' event' . ( $total_week_events !== 1 ? 's' : '' );
								}
								if ( $current_view === 'classes' && $total_week_classes > 0 ) {
									$count_parts[] = $total_week_classes . ' class' . ( $total_week_classes !== 1 ? 'es' : '' );
								}
								echo ! empty( $count_parts ) ? implode( ', ', $count_parts ) . ' this week' : 'No events this week';
								?>
							</p>
						</div>
					</div>

					<div class="northam-calendar-nav-buttons">
						<?php if ( $week_offset > 0 ) : ?>
							<a href="<?php echo esc_url( add_query_arg( 'week', $week_offset - 1 ) ); ?>"
							   class="northam-calendar-nav-btn">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<polyline points="15 18 9 12 15 6"></polyline>
								</svg>
							</a>
						<?php endif; ?>

						<a href="<?php echo esc_url( remove_query_arg( 'week' ) ); ?>"
						   class="northam-calendar-today-btn">
							Today
						</a>

						<a href="<?php echo esc_url( add_query_arg( 'week', $week_offset + 1 ) ); ?>"
						   class="northam-calendar-nav-btn">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="9 18 15 12 9 6"></polyline>
							</svg>
						</a>
					</div>
				</div>

				<!-- Category Filters (Moved Inside Calendar) -->
				<div class="northam-calendar-filters">
					<?php
					// Category options
					$categories = array(
						'all'              => array( 'label' => 'All categories', 'class' => '' ),
						'kids-family'      => array( 'label' => 'Kids & Family', 'class' => 'northam-filter-kids' ),
						'active-outdoors'  => array( 'label' => 'Active & Outdoors', 'class' => 'northam-filter-active' ),
						'food-drink'       => array( 'label' => 'Food & Drink', 'class' => 'northam-filter-food' ),
						'arts-culture'     => array( 'label' => 'Arts & Culture', 'class' => 'northam-filter-arts' ),
						'community-social' => array( 'label' => 'Community & Social', 'class' => 'northam-filter-community' ),
					);
					?>

					<!-- Mobile Category Dropdown -->
					<div class="northam-filter-mobile-dropdown">
						<select id="northam-category-select" class="northam-category-select" aria-label="Filter by category">
							<?php foreach ( $categories as $slug => $cat ) : ?>
								<option value="<?php echo esc_url( northam_filter_url( $slug ) ); ?>" <?php selected( $current_category, $slug ); ?>>
									<?php echo esc_html( $cat['label'] ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<svg class="select-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="6 9 12 15 18 9"></polyline>
						</svg>
					</div>

					<!-- Desktop Category Filters -->
					<div class="northam-category-filters">
						<?php foreach ( $categories as $slug => $cat ) : ?>
							<a href="<?php echo esc_url( northam_filter_url( $slug ) ); ?>"
							   class="northam-filter-btn <?php echo esc_attr( $cat['class'] ); ?> <?php echo $current_category === $slug ? 'active' : ''; ?>">
								<?php if ( $slug !== 'all' ) : ?>
									<span class="filter-dot"></span>
								<?php endif; ?>
								<?php echo esc_html( $cat['label'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>

					<?php if ( ! empty( $venues ) && $current_view === 'classes' ) : ?>
					<!-- Venue Filter -->
					<div class="northam-venue-dropdown">
						<select id="northam-venue-select-inline" class="northam-venue-select" aria-label="Filter by venue">
							<option value="<?php echo esc_attr( northam_venue_url( 0 ) ); ?>" <?php selected( $current_venue, 0 ); ?>>
								All Venues
							</option>
							<?php foreach ( $venues as $venue ) : ?>
								<option value="<?php echo esc_attr( northam_venue_url( $venue->ID ) ); ?>" <?php selected( $current_venue, $venue->ID ); ?>>
									<?php echo esc_html( $venue->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<svg class="select-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="6 9 12 15 18 9"></polyline>
						</svg>
					</div>
					<?php endif; ?>
				</div>

				<!-- Desktop: Calendar Grid -->
				<div class="northam-calendar-grid northam-calendar-desktop" data-resolved-class-id="<?php echo esc_attr( $resolved_class_id ); ?>">
					<!-- Day Headers -->
					<div class="northam-calendar-day-headers">
						<?php foreach ( $week_days as $day ) : ?>
							<div class="northam-calendar-day-header <?php echo $day['is_weekend'] ? 'is-weekend' : ''; ?> <?php echo $day['is_today'] ? 'is-today' : ''; ?>">
								<span class="day-short"><?php echo esc_html( $day['day_short'] ); ?></span>
								<span class="day-num"><?php echo esc_html( $day['day_num'] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>

					<!-- Day Cells -->
					<div class="northam-calendar-day-cells">
						<?php foreach ( $week_days as $day ) :
							$day_items = $events_by_day[$day['key']];
							?>
							<div class="northam-calendar-day-cell <?php echo $day['is_today'] ? 'is-today' : ''; ?> <?php echo $day['is_weekend'] ? 'is-weekend' : ''; ?>">
								<?php if ( ! empty( $day_items ) ) : ?>
									<?php foreach ( $day_items as $item ) :
										$is_class = ( $item['type'] === 'regular_class' );
										$item_type = 'community'; // default
										$item_name = $item['name'];
										$item_time = $item['time'];
										$item_description = '';
										$item_map_url = '';

										// Initialize venue info
										$venue_name = '';
										$venue_url = '';
										$event_image_url = '';
										$event_permalink = '';
										$event_full_content = '';
										$event_slug = '';

										if ( $is_class ) {
											// Map class category to display type
											$class_cat = isset( $item['category'] ) ? $item['category'] : 'community-social';
											$cat_to_type = array(
												'kids-family'      => 'kids',
												'active-outdoors'  => 'active',
												'food-drink'       => 'food',
												'arts-culture'     => 'arts',
												'music-nightlife'  => 'arts',
												'community-social' => 'community',
											);
											$item_type = isset( $cat_to_type[ $class_cat ] ) ? $cat_to_type[ $class_cat ] : 'community';
											$venue_name = isset( $item['venue_name'] ) ? $item['venue_name'] : '';
											$venue_url = isset( $item['venue_url'] ) ? $item['venue_url'] : '';
											// Pass contact details separately for JS to build links
											$item_description = ''; // Built in JS
											$item_frequency = isset( $item['frequency'] ) ? $item['frequency'] : '';
											$item_contact = isset( $item['contact'] ) ? $item['contact'] : '';
											$item_phone = isset( $item['phone'] ) ? $item['phone'] : '';
											// Get map URL from the venue
											if ( isset( $item['venue_id'] ) ) {
												$item_map_url = get_post_meta( $item['venue_id'], '_northam_map_url', true );
											}
										} else {
											// Get event category for color coding
											$em_event = $item['em_event'];
											$categories = $em_event->get_categories();

											if ( $categories && is_object( $categories ) ) {
												$categories = $categories->categories;
											}

											if ( ! empty( $categories ) && is_array( $categories ) ) {
												$first_cat = reset( $categories );
												if ( is_object( $first_cat ) ) {
													$cat_slug = isset( $first_cat->slug ) ? $first_cat->slug : '';

													if ( strpos( $cat_slug, 'kids' ) !== false || strpos( $cat_slug, 'family' ) !== false ) {
														$item_type = 'kids';
													} elseif ( strpos( $cat_slug, 'active' ) !== false || strpos( $cat_slug, 'outdoors' ) !== false ) {
														$item_type = 'active';
													} elseif ( strpos( $cat_slug, 'food' ) !== false || strpos( $cat_slug, 'drink' ) !== false ) {
														$item_type = 'food';
													} elseif ( strpos( $cat_slug, 'arts' ) !== false || strpos( $cat_slug, 'culture' ) !== false ) {
														$item_type = 'arts';
													} elseif ( strpos( $cat_slug, 'music' ) !== false || strpos( $cat_slug, 'nightlife' ) !== false ) {
														$item_type = 'arts';
													}
												}
											}

											// Get venue from custom meta field (linked business/venue)
											$linked_venue_id = get_post_meta( $em_event->post_id, '_northam_event_venue', true );
											if ( $linked_venue_id ) {
												$linked_venue = get_post( $linked_venue_id );
												if ( $linked_venue ) {
													$venue_name = $linked_venue->post_title;
													$venue_url = get_permalink( $linked_venue_id );
													// Get map URL from linked venue/business
													$item_map_url = get_post_meta( $linked_venue_id, '_northam_map_url', true );
												}
											} else {
												// Fallback to EM location if no linked venue
												$location = $em_event->get_location();
												if ( $location && ! empty( $location->location_name ) ) {
													$venue_name = $location->location_name;
												}
											}

											// If no map URL from venue, try event's own map URL
											if ( empty( $item_map_url ) ) {
												$item_map_url = get_post_meta( $em_event->post_id, '_northam_event_map_url', true );
											}

											$item_description = wp_trim_words( $em_event->post_content, 20 );

											// Get featured image for modal (large or medium_large size)
											if ( has_post_thumbnail( $em_event->post_id ) ) {
												$image_id = get_post_thumbnail_id( $em_event->post_id );
												$image_data = wp_get_attachment_image_src( $image_id, 'large' );
												if ( ! $image_data ) {
													$image_data = wp_get_attachment_image_src( $image_id, 'medium_large' );
												}
												if ( $image_data ) {
													$event_image_url = $image_data[0];
												}
											}

											// Get event permalink and slug
											$event_permalink = get_permalink( $em_event->post_id );
											$event_post = get_post( $em_event->post_id );
											$event_slug = $event_post ? $event_post->post_name : '';

											// Get full event content, sanitized
											$event_full_content = wp_kses_post( $em_event->post_content );

											// Get button customization fields
											$event_button_url = get_post_meta( $em_event->post_id, '_northam_event_button_url', true );
											$event_button_label = get_post_meta( $em_event->post_id, '_northam_event_button_label', true );
											$event_button_hide = get_post_meta( $em_event->post_id, '_northam_event_button_hide', true );
										}

										// Format date for display
										$item_date = date( 'l, j F Y', strtotime( $day['key'] ) );

						// Generate stable class ID for deep links (classes only)
						$class_id = $is_class ? northam_class_id( $item, $day['key'] ) : '';
										?>
										<div class="northam-calendar-event northam-event-<?php echo esc_attr( $item_type ); ?> <?php echo $is_class ? 'is-regular-class' : ''; ?>"
											 data-event-title="<?php echo esc_attr( $item_name ); ?>"
											 data-event-date="<?php echo esc_attr( $item_date ); ?>"
											 data-event-time="<?php echo esc_attr( $item_time ); ?>"
											 data-event-venue="<?php echo esc_attr( $venue_name ); ?>"
											 data-event-type="<?php echo esc_attr( $item_type ); ?>"
											 data-event-description="<?php echo esc_attr( $item_description ); ?>"
											 data-event-frequency="<?php echo $is_class ? esc_attr( $item_frequency ) : ''; ?>"
											 data-event-contact="<?php echo $is_class ? esc_attr( $item_contact ) : ''; ?>"
											 data-event-phone="<?php echo $is_class ? esc_attr( $item_phone ) : ''; ?>"
											 data-event-map-url="<?php echo esc_url( $item_map_url ); ?>"
											 data-event-image="<?php echo $is_class ? esc_url( isset( $item['image'] ) ? $item['image'] : '' ) : esc_url( $event_image_url ); ?>"
											 data-event-url="<?php echo $is_class ? '' : esc_url( $event_permalink ); ?>"
											 data-event-content-html="<?php echo $is_class ? '' : esc_attr( $event_full_content ); ?>"
											 data-event-button-url="<?php echo $is_class ? '' : esc_url( $event_button_url ); ?>"
											 data-event-button-label="<?php echo $is_class ? '' : esc_attr( $event_button_label ); ?>"
											 data-event-button-hide="<?php echo $is_class ? '' : esc_attr( $event_button_hide ); ?>"
											 data-event-slug="<?php echo $is_class ? '' : esc_attr( $event_slug ); ?>"
									 data-class-id="<?php echo $is_class ? esc_attr( $class_id ) : ''; ?>">
											<span class="event-dot"></span>
											<div class="event-content">
												<p class="event-title"><?php echo esc_html( $item_name ); ?></p>
												<p class="event-time">
													<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
														<circle cx="12" cy="12" r="10"></circle>
														<polyline points="12 6 12 12 16 14"></polyline>
													</svg>
													<?php echo esc_html( $item_time ); ?>
												</p>
												<?php if ( ! empty( $venue_name ) ) : ?>
													<p class="event-venue">
														<?php if ( ! empty( $venue_url ) ) : ?>
															<a href="<?php echo esc_url( $venue_url ); ?>">
																<?php echo esc_html( $venue_name ); ?>
															</a>
														<?php else : ?>
															<?php echo esc_html( $venue_name ); ?>
														<?php endif; ?>
													</p>
												<?php endif; ?>
											</div>
										</div>
									<?php endforeach; ?>
								<?php else : ?>
									<div class="northam-calendar-no-events">—</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Mobile: Vertical Day List -->
				<div class="northam-calendar-mobile">
					<?php foreach ( $week_days as $day ) :
						$day_items = $events_by_day[$day['key']];
						?>
						<div class="northam-mobile-day-card <?php echo $day['is_today'] ? 'is-today' : ''; ?>">
							<div class="mobile-day-header">
								<div class="mobile-day-info">
									<span class="mobile-day-num"><?php echo esc_html( $day['day_num'] ); ?></span>
									<span class="mobile-day-name"><?php echo esc_html( $day['day_name'] ); ?></span>
									<?php if ( $day['is_today'] ) : ?>
										<span class="mobile-today-badge">Today</span>
									<?php endif; ?>
								</div>
								<?php if ( ! empty( $day_items ) ) : ?>
									<span class="mobile-event-count">
										<?php echo count( $day_items ); ?> item<?php echo count( $day_items ) !== 1 ? 's' : ''; ?>
									</span>
								<?php endif; ?>
							</div>

							<?php if ( ! empty( $day_items ) ) : ?>
								<div class="mobile-day-events">
									<?php foreach ( $day_items as $item ) :
										$is_class = ( $item['type'] === 'regular_class' );
										$item_type = 'community';
										$item_name = $item['name'];
										$item_time = $item['time'];
										$item_map_url = '';
										$event_image_url = '';
										$event_permalink = '';
										$event_full_content = '';

										if ( $is_class ) {
											// Map class category to display type
											$class_cat = isset( $item['category'] ) ? $item['category'] : 'community-social';
											$cat_to_type = array(
												'kids-family'      => 'kids',
												'active-outdoors'  => 'active',
												'food-drink'       => 'food',
												'arts-culture'     => 'arts',
												'music-nightlife'  => 'arts',
												'community-social' => 'community',
											);
											$item_type = isset( $cat_to_type[ $class_cat ] ) ? $cat_to_type[ $class_cat ] : 'community';
											$location_name = $item['venue_name'];
											$location_url = $item['venue_url'];
											// Pass contact details separately for JS to build links
											$description = ''; // Built in JS
											$item_frequency = isset( $item['frequency'] ) ? $item['frequency'] : '';
											$item_contact = isset( $item['contact'] ) ? $item['contact'] : '';
											$item_phone = isset( $item['phone'] ) ? $item['phone'] : '';
											// Get map URL from the venue
											if ( isset( $item['venue_id'] ) ) {
												$item_map_url = get_post_meta( $item['venue_id'], '_northam_map_url', true );
											}
										} else {
											$em_event = $item['em_event'];
											$categories = $em_event->get_categories();

											if ( $categories && is_object( $categories ) ) {
												$categories = $categories->categories;
											}

											if ( ! empty( $categories ) && is_array( $categories ) ) {
												$first_cat = reset( $categories );
												if ( is_object( $first_cat ) ) {
													$cat_slug = isset( $first_cat->slug ) ? $first_cat->slug : '';

													if ( strpos( $cat_slug, 'kids' ) !== false || strpos( $cat_slug, 'family' ) !== false ) {
														$item_type = 'kids';
													} elseif ( strpos( $cat_slug, 'active' ) !== false || strpos( $cat_slug, 'outdoors' ) !== false ) {
														$item_type = 'active';
													} elseif ( strpos( $cat_slug, 'food' ) !== false || strpos( $cat_slug, 'drink' ) !== false ) {
														$item_type = 'food';
													} elseif ( strpos( $cat_slug, 'arts' ) !== false || strpos( $cat_slug, 'culture' ) !== false ) {
														$item_type = 'arts';
													} elseif ( strpos( $cat_slug, 'music' ) !== false || strpos( $cat_slug, 'nightlife' ) !== false ) {
														$item_type = 'arts';
													}
												}
											}

											// Get venue from custom meta field (linked business/venue)
											$linked_venue_id = get_post_meta( $em_event->post_id, '_northam_event_venue', true );
											$location_name = '';
											$location_url = '';
											if ( $linked_venue_id ) {
												$linked_venue = get_post( $linked_venue_id );
												if ( $linked_venue ) {
													$location_name = $linked_venue->post_title;
													$location_url = get_permalink( $linked_venue_id );
													// Get map URL from linked venue/business
													$item_map_url = get_post_meta( $linked_venue_id, '_northam_map_url', true );
												}
											} else {
												// Fallback to EM location if no linked venue
												$location = $em_event->get_location();
												if ( $location && ! empty( $location->location_name ) ) {
													$location_name = $location->location_name;
												}
											}

											// If no map URL from venue, try event's own map URL
											if ( empty( $item_map_url ) ) {
												$item_map_url = get_post_meta( $em_event->post_id, '_northam_event_map_url', true );
											}

											$description = wp_trim_words( $em_event->post_content, 15 );

											// Get featured image for modal (large or medium_large size)
											if ( has_post_thumbnail( $em_event->post_id ) ) {
												$image_id = get_post_thumbnail_id( $em_event->post_id );
												$image_data = wp_get_attachment_image_src( $image_id, 'large' );
												if ( ! $image_data ) {
													$image_data = wp_get_attachment_image_src( $image_id, 'medium_large' );
												}
												if ( $image_data ) {
													$event_image_url = $image_data[0];
												}
											}

											// Get event permalink and slug
											$event_permalink = get_permalink( $em_event->post_id );
											$event_post = get_post( $em_event->post_id );
											$event_slug = $event_post ? $event_post->post_name : '';

											// Get full event content, sanitized
											$event_full_content = wp_kses_post( $em_event->post_content );

											// Get button customization fields
											$event_button_url = get_post_meta( $em_event->post_id, '_northam_event_button_url', true );
											$event_button_label = get_post_meta( $em_event->post_id, '_northam_event_button_label', true );
											$event_button_hide = get_post_meta( $em_event->post_id, '_northam_event_button_hide', true );
										}

										// Format date for display

						// Generate stable class ID for deep links (classes only)
						$class_id = $is_class ? northam_class_id( $item, $day['key'] ) : '';
										$item_date = date( 'l, j F Y', strtotime( $day['key'] ) );
										?>
										<div class="mobile-event-card northam-event-<?php echo esc_attr( $item_type ); ?> <?php echo $is_class ? 'is-regular-class' : ''; ?>"
											 data-event-title="<?php echo esc_attr( $item_name ); ?>"
											 data-event-date="<?php echo esc_attr( $item_date ); ?>"
											 data-event-time="<?php echo esc_attr( $item_time ); ?>"
											 data-event-venue="<?php echo esc_attr( $location_name ); ?>"
											 data-event-type="<?php echo esc_attr( $item_type ); ?>"
											 data-event-description="<?php echo esc_attr( $description ); ?>"
											 data-event-frequency="<?php echo $is_class ? esc_attr( $item_frequency ) : ''; ?>"
											 data-event-contact="<?php echo $is_class ? esc_attr( $item_contact ) : ''; ?>"
											 data-event-phone="<?php echo $is_class ? esc_attr( $item_phone ) : ''; ?>"
											 data-event-map-url="<?php echo esc_url( $item_map_url ); ?>"
											 data-event-image="<?php echo $is_class ? esc_url( isset( $item['image'] ) ? $item['image'] : '' ) : esc_url( $event_image_url ); ?>"
											 data-event-url="<?php echo $is_class ? '' : esc_url( $event_permalink ); ?>"
											 data-event-content-html="<?php echo $is_class ? '' : esc_attr( $event_full_content ); ?>"
											 data-event-button-url="<?php echo $is_class ? '' : esc_url( $event_button_url ); ?>"
											 data-event-button-label="<?php echo $is_class ? '' : esc_attr( $event_button_label ); ?>"
											 data-event-button-hide="<?php echo $is_class ? '' : esc_attr( $event_button_hide ); ?>"
											 data-event-slug="<?php echo $is_class ? '' : esc_attr( $event_slug ); ?>"
									 data-class-id="<?php echo $is_class ? esc_attr( $class_id ) : ''; ?>">
											<span class="mobile-event-dot"></span>
											<div class="mobile-event-content">
												<p class="mobile-event-title">
													<?php echo esc_html( $item_name ); ?>
													<?php if ( $is_class ) : ?>
														<span class="mobile-class-badge">Class</span>
													<?php endif; ?>
												</p>
												<p class="mobile-event-description"><?php echo esc_html( $description ); ?></p>
												<div class="mobile-event-meta">
													<span>
														<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
															<circle cx="12" cy="12" r="10"></circle>
															<polyline points="12 6 12 12 16 14"></polyline>
														</svg>
														<?php echo esc_html( $item_time ); ?>
													</span>
													<?php if ( $location_name ) : ?>
														<span>
															<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
																<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
																<circle cx="12" cy="10" r="3"></circle>
															</svg>
															<?php if ( ! empty( $location_url ) ) : ?>
																<a href="<?php echo esc_url( $location_url ); ?>"><?php echo esc_html( $location_name ); ?></a>
															<?php else : ?>
																<?php echo esc_html( $location_name ); ?>
															<?php endif; ?>
														</span>
													<?php endif; ?>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php else : ?>
								<div class="mobile-no-events">
									<p>No events</p>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<?php
			} else {
				?>
				<p>Events Manager plugin is not active.</p>
				<?php
			}
			?>
		</div>
	</div>

	<!-- Submit Event CTA -->
	<div class="northam-cta-section" style="background-color: hsla(45, 55%, 80%, 0.5); padding: 3rem 0;">
		<div class="northam-container" style="text-align: center;">
			<h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 0.75rem;">Have an event to share?</h2>
			<p style="color: hsl(200, 30%, 45%); margin-bottom: 1.5rem; max-width: 32rem; margin-left: auto; margin-right: auto;">
				If you're hosting an event in Northam, let us know and we'll add it to the calendar. We would love to give you your own page if you need it. Just get in touch with us via this form and someone will get back to you.
			</p>

			<div style="max-width: 500px; margin: 1.5rem auto 0; text-align: left;">
				<?php echo do_shortcode( '[fluentform id="7"]' ); ?>
			</div>
		</div>
	</div>

	<!-- Event Detail Modal/Drawer -->
	<div id="northam-event-detail-modal" class="northam-event-modal">
		<div class="modal-overlay"></div>
		<div class="modal-content">
			<button class="modal-close" aria-label="Close">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>

			<div class="modal-body">
				<div class="event-type-badge">
					<span class="badge-dot"></span>
					<span class="badge-label">Event Type</span>
				</div>

				<h2 class="event-modal-title">Event Title</h2>

				<div class="event-modal-details">
					<div class="modal-detail-item">
						<div class="detail-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
								<line x1="16" y1="2" x2="16" y2="6"></line>
								<line x1="8" y1="2" x2="8" y2="6"></line>
								<line x1="3" y1="10" x2="21" y2="10"></line>
							</svg>
						</div>
						<div>
							<p class="detail-label">Date</p>
							<p class="detail-value detail-date">—</p>
						</div>
					</div>

					<div class="modal-detail-item">
						<div class="detail-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="12" cy="12" r="10"></circle>
								<polyline points="12 6 12 12 16 14"></polyline>
							</svg>
						</div>
						<div>
							<p class="detail-label">Time</p>
							<p class="detail-value detail-time">—</p>
						</div>
					</div>

					<a href="#" target="_blank" rel="noopener noreferrer" class="modal-detail-item detail-venue-link">
						<div class="detail-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
								<circle cx="12" cy="10" r="3"></circle>
							</svg>
						</div>
						<div class="detail-venue-info">
							<p class="detail-label">Venue</p>
							<p class="detail-value detail-venue">—</p>
						</div>
						<span class="detail-map-arrow">Map →</span>
					</a>
				</div>

				<div class="event-modal-description-section">
					<h4>About this event</h4>
					<p class="event-modal-description">Event description will appear here.</p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
// Enqueue event modal JavaScript
wp_enqueue_script(
	'northam-event-modal',
	get_stylesheet_directory_uri() . '/assets/js/event-detail-modal.js',
	array(),
	filemtime( get_stylesheet_directory() . '/assets/js/event-detail-modal.js' ),
	true
);
?>

<script>
document.getElementById('northam-category-select').addEventListener('change', function() {
	window.location.href = this.value;
});
<?php if ( ! empty( $venues ) && $current_view === 'classes' ) : ?>
document.getElementById('northam-venue-select-inline').addEventListener('change', function() {
	window.location.href = this.value;
});
<?php endif; ?>
</script>

<?php get_footer(); ?>
