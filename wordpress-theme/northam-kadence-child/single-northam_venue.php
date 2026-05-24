<?php
/**
 * Single Community Venue Template
 *
 * @package Northam
 * @since 1.0.0
 */

get_header();

while ( have_posts() ) :
	the_post();

	// Get venue meta
	$phone = get_post_meta( get_the_ID(), '_northam_phone', true );
	$address = get_post_meta( get_the_ID(), '_northam_address', true );
	$website = get_post_meta( get_the_ID(), '_northam_website', true );
	$email = get_post_meta( get_the_ID(), '_northam_email', true );
	$capacity = get_post_meta( get_the_ID(), '_northam_capacity', true );
	$facilities = get_post_meta( get_the_ID(), '_northam_facilities', true );
	$lat = get_post_meta( get_the_ID(), '_northam_lat', true );
	$lng = get_post_meta( get_the_ID(), '_northam_lng', true );
	$enquiry_form = get_post_meta( get_the_ID(), '_northam_enquiry_form', true );

	// Generate map URL from lat/lng if not provided
	$map_url = '';
	if ( $lat && $lng ) {
		$map_url = 'https://www.google.com/maps?q=' . urlencode( $lat . ',' . $lng );
	}

	// Parse facilities (one per line)
	$facilities_array = $facilities ? array_filter( array_map( 'trim', explode( "\n", $facilities ) ) ) : array();

	// Get venue types (taxonomy)
	$venue_types = get_the_terms( get_the_ID(), 'venue_type' );
	$venue_type = $venue_types && ! is_wp_error( $venue_types ) ? $venue_types[0] : null;
	$venue_type_slug = $venue_type ? $venue_type->slug : 'general';

	// Type colors
	$type_colors = array(
		'halls' => 'bg-event-venue',
		'sports' => 'bg-event-business',
		'outdoor' => 'bg-primary',
	);
	$badge_class = isset( $type_colors[ $venue_type_slug ] ) ? $type_colors[ $venue_type_slug ] : 'bg-primary';
?>

<!-- Hero Section -->
<section class="northam-business-hero">
	<?php
	// Use featured image if set, otherwise use first gallery image
	$hero_image_url = '';
	if ( has_post_thumbnail() ) {
		$hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
	} else {
		$gallery_images = northam_get_gallery( get_the_ID() );
		if ( ! empty( $gallery_images ) ) {
			$hero_image_url = $gallery_images[0]['url'];
		}
	}
	?>
	<?php if ( $hero_image_url ) : ?>
		<div class="northam-business-hero-image" style="background-image: url('<?php echo esc_url( $hero_image_url ); ?>')"></div>
	<?php else : ?>
		<div class="northam-business-hero-image northam-business-hero-placeholder"></div>
	<?php endif; ?>
	<div class="northam-business-hero-overlay"></div>

	<!-- Back Button -->
	<div class="northam-business-back">
		<a href="<?php echo esc_url( get_post_type_archive_link( 'northam_group' ) ); ?>" class="northam-btn-ghost">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<polyline points="15 18 9 12 15 6"></polyline>
			</svg>
			Back to Community
		</a>
	</div>

	<!-- Hero Content -->
	<div class="northam-business-hero-content">
		<div class="northam-container">
			<?php if ( $venue_types && ! is_wp_error( $venue_types ) ) : ?>
				<div class="northam-categories-badges">
					<?php foreach ( $venue_types as $single_type ) :
						$single_type_slug = $single_type->slug;
						$single_badge_class = isset( $type_colors[ $single_type_slug ] ) ? $type_colors[ $single_type_slug ] : 'bg-primary';
					?>
						<span class="northam-badge <?php echo esc_attr( $single_badge_class ); ?>">
							<?php echo esc_html( $single_type->name ); ?>
						</span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<h1 class="northam-business-title"><?php the_title(); ?></h1>

			<!-- Venue Highlights -->
			<div class="northam-accessibility-badges">
				<?php if ( $capacity ) : ?>
					<span class="northam-accessibility-badge">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
							<circle cx="9" cy="7" r="4"></circle>
							<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
							<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
						</svg>
						Capacity: <?php echo esc_html( $capacity ); ?>
					</span>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<!-- Main Content -->
<section class="northam-business-content">
	<div class="northam-container">
		<div class="northam-business-grid">
			<!-- Left Column - Main Content -->
			<div class="northam-business-main">
				<!-- About Section -->
				<div class="northam-business-about">
					<h2>About <?php the_title(); ?></h2>
					<div class="northam-business-description">
						<?php the_content(); ?>
					</div>
				</div>

				<!-- Facilities Section -->
				<?php if ( ! empty( $facilities_array ) ) : ?>
					<div class="northam-facilities-section">
						<h2>
							<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-accent">
								<path d="M9 11l3 3L22 4"></path>
								<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
							</svg>
							Facilities
						</h2>
						<ul class="northam-facilities-list">
							<?php foreach ( $facilities_array as $facility ) : ?>
								<li>
									<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<polyline points="20 6 9 17 4 12"></polyline>
									</svg>
									<?php echo esc_html( $facility ); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<!-- Regular Classes Section -->
				<?php
				$classes_json = get_post_meta( get_the_ID(), '_northam_regular_classes', true );
				$classes = $classes_json ? json_decode( wp_unslash( $classes_json ), true ) : array();

				if ( ! empty( $classes ) ) :
				?>
					<div class="northam-regular-classes-section">
						<h2>
							<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
								<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
								<line x1="16" y1="2" x2="16" y2="6"></line>
								<line x1="8" y1="2" x2="8" y2="6"></line>
								<line x1="3" y1="10" x2="21" y2="10"></line>
							</svg>
							Regular Classes
						</h2>

						<div class="northam-classes-table-wrapper">
							<table class="northam-classes-table">
								<thead>
									<tr>
										<th>Time</th>
										<th>Class</th>
										<th>Frequency</th>
										<th>Contact</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $classes as $day => $day_classes ) : ?>
										<tr class="northam-classes-day-header">
											<td colspan="4"><?php echo esc_html( $day ); ?></td>
										</tr>
										<?php foreach ( $day_classes as $class ) :
											$is_weekly = strtolower( $class['frequency'] ?? '' ) === 'weekly';
											$contact = $class['contact'] ?? '';
											$contact_url = '';
											if ( ! empty( $contact ) ) {
												// Check if it's a URL or domain
												if ( filter_var( $contact, FILTER_VALIDATE_URL ) ) {
													$contact_url = $contact;
												} elseif ( preg_match( '/^[a-z0-9]+([\-\.][a-z0-9]+)*\.[a-z]{2,}$/i', $contact ) ) {
													// Looks like a domain
													$contact_url = 'https://' . $contact;
												} elseif ( strtolower( $contact ) === 'facebook' ) {
													$contact_url = '';
												}
											}
										?>
											<tr>
												<td><?php echo esc_html( $class['time'] ?? '' ); ?></td>
												<td><?php echo esc_html( $class['name'] ?? '' ); ?></td>
												<td>
													<span class="northam-class-frequency <?php echo $is_weekly ? 'weekly' : ''; ?>">
														<?php echo esc_html( $class['frequency'] ?? '' ); ?>
													</span>
												</td>
												<td class="northam-class-contact">
													<?php if ( $contact_url ) : ?>
														<a href="<?php echo esc_url( $contact_url ); ?>" target="_blank" rel="noopener noreferrer">
															<?php echo esc_html( $contact ); ?>
														</a>
													<?php elseif ( $contact ) : ?>
														<?php echo esc_html( $contact ); ?>
													<?php else : ?>
														-
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				<?php endif; ?>

				<!-- Photo Gallery Section -->
				<?php
				$gallery_images = northam_get_gallery( get_the_ID() );

				// If no featured image is set, skip the first gallery image (it's used as hero)
				if ( ! has_post_thumbnail() && ! empty( $gallery_images ) ) {
					array_shift( $gallery_images ); // Remove first image
				}

				if ( ! empty( $gallery_images ) ) :
				?>
					<div class="northam-gallery-section">
						<h2>
							<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-accent">
								<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
								<circle cx="8.5" cy="8.5" r="1.5"></circle>
								<polyline points="21 15 16 10 5 21"></polyline>
							</svg>
							Photo Gallery
						</h2>

						<div class="northam-gallery-grid">
							<?php foreach ( $gallery_images as $image ) : ?>
								<div class="northam-gallery-item">
									<img src="<?php echo esc_url( $image['url'] ); ?>"
									     alt="<?php echo esc_attr( $image['alt'] ?: get_the_title() ); ?>"
									     loading="lazy">
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php
				// Get events at this venue if Events Manager is active
				$events = array();
				$week_offset = isset( $_GET['week'] ) ? absint( $_GET['week'] ) : 0;

				if ( class_exists( 'EM_Events' ) ) {
					// Calculate start and end of the week to display
					$current_time = current_time( 'timestamp' );
					$week_start = strtotime( 'monday this week', $current_time ) + ( $week_offset * 7 * 24 * 60 * 60 );
					$week_end = strtotime( 'sunday this week', $current_time ) + ( $week_offset * 7 * 24 * 60 * 60 ) + ( 24 * 60 * 60 ) - 1;

					// Get all future events using Events Manager API
					$all_events = EM_Events::get( array(
						'scope' => 'future',
						'status' => 1,
					) );

					// Filter events to only those linked to this venue and in the current week
					if ( ! empty( $all_events ) ) {
						foreach ( $all_events as $em_event ) {
							// Check if this event's post has our meta field
							$venue_id = get_post_meta( $em_event->post_id, '_northam_event_venue', true );

							if ( $venue_id == get_the_ID() ) {
								// Check if event is within the current week
								if ( $em_event->start >= $week_start && $em_event->start <= $week_end ) {
									$events[] = $em_event;
								}
							}
						}
					}
				}

				// Show events section if there are events
				if ( ! empty( $events ) ) :
					$week_start_date = date_i18n( 'j M', $week_start );
					$week_end_date = date_i18n( 'j M', $week_end );
				?>
				<!-- Upcoming Events Section -->
				<div id="events" class="northam-events-section">
					<div class="northam-events-header">
						<h2>
							<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary">
								<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
								<line x1="16" y1="2" x2="16" y2="6"></line>
								<line x1="8" y1="2" x2="8" y2="6"></line>
								<line x1="3" y1="10" x2="21" y2="10"></line>
							</svg>
							<?php
							if ( $week_offset === 0 ) {
								echo esc_html__( 'This Week\'s Events', 'northam' );
							} else {
								echo esc_html( sprintf( __( 'Events: %s - %s', 'northam' ), $week_start_date, $week_end_date ) );
							}
							?>
						</h2>
						<div class="northam-events-pagination">
							<?php if ( $week_offset > 0 ) : ?>
								<button class="northam-events-nav northam-events-prev" data-week="<?php echo esc_attr( max( 0, $week_offset - 1 ) ); ?>">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<polyline points="15 18 9 12 15 6"></polyline>
									</svg>
									Previous Week
								</button>
							<?php endif; ?>
							<button class="northam-events-nav northam-events-next" data-week="<?php echo esc_attr( $week_offset + 1 ); ?>">
								Next Week
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<polyline points="9 18 15 12 9 6"></polyline>
								</svg>
							</button>
						</div>
					</div>

					<div class="northam-events-grid-small">
						<?php foreach ( $events as $event ) :
							$event_map_url = get_post_meta( $event->post_id, '_northam_event_map_url', true );
						?>
							<div class="northam-event-card-small">
								<div class="northam-event-card-image">
									<?php if ( has_post_thumbnail( $event->post_id ) ) : ?>
										<?php echo get_the_post_thumbnail( $event->post_id, 'thumbnail' ); ?>
									<?php else : ?>
										<div class="northam-event-placeholder"></div>
									<?php endif; ?>
								</div>
								<div class="northam-event-card-content">
									<h3><?php echo esc_html( $event->event_name ); ?></h3>
									<p>
										<?php echo esc_html( date_i18n( 'D, jS M', strtotime( $event->event_start_date ) ) ); ?>
										at <?php echo esc_html( date_i18n( 'g:i A', strtotime( $event->event_start_time ) ) ); ?>
									</p>
									<?php if ( $event_map_url ) : ?>
										<a href="<?php echo esc_url( $event_map_url ); ?>" target="_blank" rel="noopener noreferrer" class="northam-map-link northam-map-link-small">
											<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
												<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
												<circle cx="12" cy="10" r="3"></circle>
											</svg>
											View on Map
										</a>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<!-- Right Column - Sidebar -->
			<div class="northam-business-sidebar">
				<div class="northam-business-sidebar-card">
					<!-- Capacity Highlight -->
					<?php if ( $capacity ) : ?>
						<div class="northam-hours-today">
							<div class="northam-hours-today-label">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="9" cy="7" r="4"></circle>
									<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
									<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
								</svg>
								Capacity
							</div>
							<p class="northam-hours-today-value"><?php echo esc_html( $capacity ); ?> people</p>
						</div>
					<?php endif; ?>

					<!-- Contact Details -->
					<div class="northam-contact-details">
						<?php if ( $phone ) : ?>
							<a href="tel:<?php echo esc_attr( $phone ); ?>" class="northam-contact-item northam-contact-clickable">
								<div class="northam-contact-icon bg-primary-light">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
									</svg>
								</div>
								<span><?php echo esc_html( $phone ); ?></span>
							</a>
						<?php endif; ?>

						<?php if ( $email ) : ?>
							<a href="mailto:<?php echo esc_attr( $email ); ?>" class="northam-contact-item northam-contact-clickable">
								<div class="northam-contact-icon bg-primary-light">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
										<polyline points="22,6 12,13 2,6"></polyline>
									</svg>
								</div>
								<span><?php echo esc_html( $email ); ?></span>
							</a>
						<?php endif; ?>

						<?php if ( $address ) : ?>
							<div class="northam-contact-item">
								<div class="northam-contact-icon">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
										<circle cx="12" cy="10" r="3"></circle>
									</svg>
								</div>
								<span><?php echo esc_html( $address ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( $website ) : ?>
							<a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer" class="northam-contact-item northam-contact-clickable">
								<div class="northam-contact-icon bg-primary-light">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
										<circle cx="12" cy="12" r="10"></circle>
										<line x1="2" y1="12" x2="22" y2="12"></line>
										<path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
									</svg>
								</div>
								<span class="truncate"><?php echo esc_html( preg_replace( '#^https?://#', '', $website ) ); ?></span>
							</a>
						<?php endif; ?>
					</div>

					<!-- Map -->
					<?php if ( $address || $map_url ) : ?>
						<div class="northam-map-static">
							<div class="northam-map-bg"></div>
							<div class="northam-map-overlay">
								<?php if ( $address ) : ?>
									<div class="northam-map-address">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
											<circle cx="12" cy="10" r="3"></circle>
										</svg>
										<span><?php echo esc_html( $address ); ?></span>
									</div>
								<?php endif; ?>
								<?php if ( $map_url ) : ?>
									<a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener noreferrer" class="northam-map-btn">
										View on Google Maps
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
											<polyline points="15 3 21 3 21 9"></polyline>
											<line x1="10" y1="14" x2="21" y2="3"></line>
										</svg>
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
endwhile;

get_footer();
