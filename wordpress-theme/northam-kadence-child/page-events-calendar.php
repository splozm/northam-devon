<?php
/**
 * Template Name: Events Calendar
 * Description: Week-based calendar view for events (matches React implementation)
 */

get_header();

// Get current filter from URL
$current_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : 'all';
$show_classes = isset($_GET['classes']) ? $_GET['classes'] === '1' : true; // Show classes by default
?>

<div class="northam-events-calendar-page">
	<!-- Hero Section -->
	<div class="northam-archive-hero northam-gradient-coastal">
		<div class="northam-archive-hero-overlay"></div>
		<div class="northam-archive-hero-content northam-container">
			<div class="northam-hero-badge">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
					<line x1="16" y1="2" x2="16" y2="6"></line>
					<line x1="8" y1="2" x2="8" y2="6"></line>
					<line x1="3" y1="10" x2="21" y2="10"></line>
				</svg>
				What's On
			</div>
			<h1>Events Calendar</h1>
			<p>Discover what's happening in Northam — from community gatherings to live entertainment</p>
		</div>
	</div>

	<!-- Filters Bar -->
	<div class="northam-filters-bar">
		<div class="northam-container">
			<div class="northam-filters-form">
				<!-- Filter Icon (Mobile) -->
				<button class="northam-filter-mobile-toggle" aria-label="Toggle filters">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
					</svg>
				</button>

				<!-- Category Filters -->
				<div class="northam-category-filters northam-category-filters-center">
					<a href="<?php echo esc_url(add_query_arg('category', 'all', remove_query_arg('category'))); ?>"
					   class="northam-filter-btn <?php echo $current_category === 'all' ? 'active' : ''; ?>">
						All Events
					</a>
					<a href="<?php echo esc_url(add_query_arg('category', 'kids-family', remove_query_arg('category'))); ?>"
					   class="northam-filter-btn <?php echo $current_category === 'kids-family' ? 'active' : ''; ?>">
						Kids & Family
					</a>
					<a href="<?php echo esc_url(add_query_arg('category', 'active-outdoors', remove_query_arg('category'))); ?>"
					   class="northam-filter-btn <?php echo $current_category === 'active-outdoors' ? 'active' : ''; ?>">
						Active & Outdoors
					</a>
					<a href="<?php echo esc_url(add_query_arg('category', 'food-drink', remove_query_arg('category'))); ?>"
					   class="northam-filter-btn <?php echo $current_category === 'food-drink' ? 'active' : ''; ?>">
						Food & Drink
					</a>
					<a href="<?php echo esc_url(add_query_arg('category', 'arts-culture', remove_query_arg('category'))); ?>"
					   class="northam-filter-btn <?php echo $current_category === 'arts-culture' ? 'active' : ''; ?>">
						Arts & Culture
					</a>
					<a href="<?php echo esc_url(add_query_arg('category', 'music-nightlife', remove_query_arg('category'))); ?>"
					   class="northam-filter-btn <?php echo $current_category === 'music-nightlife' ? 'active' : ''; ?>">
						Music & Nightlife
					</a>
					<a href="<?php echo esc_url(add_query_arg('category', 'community-social', remove_query_arg('category'))); ?>"
					   class="northam-filter-btn <?php echo $current_category === 'community-social' ? 'active' : ''; ?>">
						Community & Social
					</a>
					<span class="northam-filter-divider"></span>
					<a href="<?php echo esc_url(add_query_arg('classes', $show_classes ? '0' : '1')); ?>"
					   class="northam-filter-btn northam-filter-toggle <?php echo $show_classes ? 'active' : ''; ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
							<path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
						</svg>
						Regular Classes
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Events Content -->
	<div class="northam-archive-content" style="padding-top: 3rem; padding-bottom: 3rem;">
		<div class="northam-container">
			<?php
			// Query events based on filter
			if ( class_exists( 'EM_Events' ) ) {
				// Week-based pagination
				$week_offset = isset( $_GET['week'] ) ? absint( $_GET['week'] ) : 0;
				$current_time = current_time( 'timestamp' );

				// Calculate week start (Monday) and end (Sunday)
				$week_start = strtotime( 'monday this week', $current_time ) + ( $week_offset * 7 * 24 * 60 * 60 );
				$week_end = strtotime( 'sunday this week', $current_time ) + ( $week_offset * 7 * 24 * 60 * 60 ) + ( 24 * 60 * 60 ) - 1;

				// Get all future events
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

				// Merge regular classes if enabled
				$total_week_classes = 0;
				if ( $show_classes ) {
					$regular_classes = northam_get_regular_classes_for_week( $week_start, $week_end );
					foreach ( $regular_classes as $day_key => $day_classes ) {
						if ( isset( $events_by_day[$day_key] ) ) {
							foreach ( $day_classes as $class ) {
								$events_by_day[$day_key][] = $class;
								$total_week_classes++;
							}
						}
					}

					// Sort each day's items by start time
					foreach ( $events_by_day as $day_key => &$day_items ) {
						usort( $day_items, function( $a, $b ) {
							$time_a = isset( $a['start_time'] ) ? $a['start_time'] : ( isset( $a['time'] ) ? explode( ' - ', $a['time'] )[0] : '00:00' );
							$time_b = isset( $b['start_time'] ) ? $b['start_time'] : ( isset( $b['time'] ) ? explode( ' - ', $b['time'] )[0] : '00:00' );
							return strcmp( $time_a, $time_b );
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
								if ( $show_classes && $total_week_classes > 0 ) {
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

				<!-- Desktop: Calendar Grid -->
				<div class="northam-calendar-grid northam-calendar-desktop">
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

										if ( $is_class ) {
											$item_type = 'class';
											$item_name = $item['name'];
											$item_time = $item['time'];
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

											$item_name = $item['name'];
											$item_time = $item['time'];
										}
										?>
										<div class="northam-calendar-event northam-event-<?php echo esc_attr( $item_type ); ?> <?php echo $is_class ? 'is-regular-class' : ''; ?>">
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
												<?php if ( $is_class && ! empty( $item['venue_name'] ) ) : ?>
													<p class="event-venue">
														<a href="<?php echo esc_url( $item['venue_url'] ); ?>">
															<?php echo esc_html( $item['venue_name'] ); ?>
														</a>
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

										if ( $is_class ) {
											$item_type = 'class';
											$item_name = $item['name'];
											$item_time = $item['time'];
											$location_name = $item['venue_name'];
											$location_url = $item['venue_url'];
											$description = $item['frequency'] . ( ! empty( $item['contact'] ) ? ' • ' . $item['contact'] : '' );
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

											$item_name = $item['name'];
											$item_time = $item['time'];
											$location = $em_event->get_location();
											$location_name = $location ? $location->location_name : '';
											$location_url = '';
											$description = wp_trim_words( $em_event->post_content, 15 );
										}
										?>
										<div class="mobile-event-card northam-event-<?php echo esc_attr( $item_type ); ?> <?php echo $is_class ? 'is-regular-class' : ''; ?>">
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
															<?php if ( $is_class && $location_url ) : ?>
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

				<!-- Legend -->
				<div class="northam-calendar-legend">
					<div class="legend-item northam-event-kids">
						<span class="legend-dot"></span>
						<span>Kids & Family</span>
					</div>
					<div class="legend-item northam-event-active">
						<span class="legend-dot"></span>
						<span>Active & Outdoors</span>
					</div>
					<div class="legend-item northam-event-food">
						<span class="legend-dot"></span>
						<span>Food & Drink</span>
					</div>
					<div class="legend-item northam-event-arts">
						<span class="legend-dot"></span>
						<span>Arts & Culture</span>
					</div>
					<div class="legend-item northam-event-community">
						<span class="legend-dot"></span>
						<span>Community & Social</span>
					</div>
					<?php if ( $show_classes ) : ?>
					<div class="legend-item northam-event-class">
						<span class="legend-dot"></span>
						<span>Regular Classes</span>
					</div>
					<?php endif; ?>
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
				If you're hosting an event in Northam, let us know and we'll add it to the calendar.
			</p>

			<div data-fs-success style="color: var(--northam-primary); font-weight: 500; padding: 1rem; background: #d4edda; border-radius: 0.5rem; margin-top: 1rem; display: none;"></div>
			<div data-fs-error style="color: #dc3545; padding: 1rem; background: #f8d7da; border-radius: 0.5rem; margin-top: 1rem; display: none;"></div>

			<form id="submit-event-form" style="display: flex; flex-direction: column; gap: 1rem; max-width: 500px; margin: 1.5rem auto 0; text-align: left;">
				<input type="hidden" name="form_type" value="submit_event" />
				<input type="hidden" name="page_url" id="submit-event-page-url" />
				<input type="text" name="_gotcha" style="display: none;" />

				<div>
					<label for="submit-event-name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Your Name</label>
					<input type="text" id="submit-event-name" name="name" class="northam-form-input" required data-fs-field />
					<span data-fs-error="name" style="color: #dc3545; font-size: 0.875rem;"></span>
				</div>

				<div>
					<label for="submit-event-email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email</label>
					<input type="email" id="submit-event-email" name="email" class="northam-form-input" required data-fs-field />
					<span data-fs-error="email" style="color: #dc3545; font-size: 0.875rem;"></span>
				</div>

				<div>
					<label for="submit-event-event-name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Event Name</label>
					<input type="text" id="submit-event-event-name" name="event_name" class="northam-form-input" required data-fs-field />
					<span data-fs-error="event_name" style="color: #dc3545; font-size: 0.875rem;"></span>
				</div>

				<div>
					<label for="submit-event-message" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Event Details</label>
					<textarea id="submit-event-message" name="message" class="northam-form-textarea" rows="4" placeholder="Date, time, location, description of your event..." required data-fs-field></textarea>
					<span data-fs-error="message" style="color: #dc3545; font-size: 0.875rem;"></span>
				</div>

				<button type="submit" class="northam-btn northam-btn-accent" data-fs-submit-btn>Submit Event</button>
			</form>

			<script>
				document.getElementById('submit-event-page-url').value = window.location.href;
				window.formspree = window.formspree || function () { (formspree.q = formspree.q || []).push(arguments); };
				formspree('initForm', { formElement: '#submit-event-form', formId: 'xgorqwyn' });
			</script>
			<script src="https://unpkg.com/@formspree/ajax@1" defer></script>
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

<?php get_footer(); ?>
