/**
 * Event Detail Modal - WordPress implementation matching React drawer
 */

(function() {
	'use strict';

	// Wait for DOM to be ready
	document.addEventListener('DOMContentLoaded', function() {
		const modal = document.getElementById('northam-event-detail-modal');
		if (!modal) return;

		const modalOverlay = modal.querySelector('.modal-overlay');
		const modalContent = modal.querySelector('.modal-content');
		const closeBtn = modal.querySelector('.modal-close');

		// Event type styling mapping
		const typeStyles = {
			kids: {
				gradient: 'linear-gradient(to bottom right, hsla(38, 92%, 50%, 0.2), hsla(27, 87%, 67%, 0.1))',
				text: 'hsl(25, 50%, 35%)',
				dot: 'linear-gradient(to bottom right, hsl(38, 92%, 50%), hsl(27, 87%, 67%))',
				label: 'Kids & Family'
			},
			active: {
				gradient: 'linear-gradient(to bottom right, hsla(152, 69%, 60%, 0.2), hsla(173, 58%, 60%, 0.1))',
				text: 'hsl(152, 60%, 30%)',
				dot: 'linear-gradient(to bottom right, hsl(152, 69%, 60%), hsl(173, 58%, 60%))',
				label: 'Active & Outdoors'
			},
			food: {
				gradient: 'linear-gradient(to bottom right, hsla(27, 87%, 67%, 0.2), hsla(0, 85%, 60%, 0.1))',
				text: 'hsl(20, 75%, 35%)',
				dot: 'linear-gradient(to bottom right, hsl(27, 87%, 67%), hsl(0, 85%, 60%))',
				label: 'Food & Drink'
			},
			arts: {
				gradient: 'linear-gradient(to bottom right, hsla(258, 90%, 66%, 0.2), hsla(282, 85%, 60%, 0.1))',
				text: 'hsl(265, 60%, 35%)',
				dot: 'linear-gradient(to bottom right, hsl(258, 90%, 66%), hsl(282, 85%, 60%))',
				label: 'Arts & Culture'
			},
			community: {
				gradient: 'linear-gradient(to bottom right, hsla(199, 89%, 65%, 0.2), hsla(211, 85%, 60%, 0.1))',
				text: 'hsl(205, 70%, 35%)',
				dot: 'linear-gradient(to bottom right, hsl(199, 89%, 65%), hsl(211, 85%, 60%))',
				label: 'Community & Social'
			}
		};

		// Open modal with event data
		function openModal(eventData) {
			const style = typeStyles[eventData.type] || typeStyles.community;
			const isEvent = !eventData.frequency && !eventData.contact && !eventData.phone;

			// Update badge
			const badge = modal.querySelector('.event-type-badge');
			badge.style.background = style.gradient;
			badge.style.color = style.text;
			badge.querySelector('.badge-dot').style.background = style.dot;
			badge.querySelector('.badge-label').textContent = style.label;

			// Update title
			modal.querySelector('.event-modal-title').textContent = eventData.title;

			// Update details
			modal.querySelector('.detail-date').textContent = eventData.date || '—';
			modal.querySelector('.detail-time').textContent = eventData.time || '—';
			modal.querySelector('.detail-venue').textContent = eventData.venue || '—';

			// Handle featured image for events (not classes)
			let existingImage = modal.querySelector('.northam-event-modal-image');
			if (isEvent && eventData.image && eventData.image.length > 0) {
				// Create or update image
				if (!existingImage) {
					existingImage = document.createElement('img');
					existingImage.className = 'northam-event-modal-image';
					modal.querySelector('.modal-body').insertBefore(existingImage, modal.querySelector('.event-type-badge'));
				}
				existingImage.src = eventData.image;
				existingImage.alt = eventData.title || 'Event image';
				existingImage.style.display = 'block';
			} else if (existingImage) {
				existingImage.style.display = 'none';
			}

			// Update description - build from frequency/contact/phone for classes
			const descriptionEl = modal.querySelector('.event-modal-description');
			descriptionEl.textContent = '';

			// If this is a class with structured data, build clickable links
			if (eventData.frequency || eventData.contact || eventData.phone) {
				const parts = [];
				
				// Add frequency as plain text
				if (eventData.frequency) {
					const freqText = document.createTextNode(eventData.frequency);
					parts.push(freqText);
				}
				
				// Process contact (website or plain text)
				if (eventData.contact) {
					const contactPart = buildContactElement(eventData.contact);
					parts.push(contactPart);
				}
				
				// Process phone (tel: link)
				if (eventData.phone) {
					const phonePart = buildPhoneElement(eventData.phone);
					parts.push(phonePart);
				}
				
				// Join with bullet separator
				parts.forEach((part, index) => {
					if (index > 0) {
						descriptionEl.appendChild(document.createTextNode(' • '));
					}
					if (typeof part === 'string') {
						descriptionEl.appendChild(document.createTextNode(part));
					} else {
						descriptionEl.appendChild(part);
					}
				});
			} else if (eventData.contentHtml && eventData.contentHtml.length > 0) {
				// For Events Manager events: use full HTML content
				descriptionEl.innerHTML = eventData.contentHtml;
				// Make all links open in new tab
				const links = descriptionEl.querySelectorAll('a');
				links.forEach(link => {
					link.setAttribute('target', '_blank');
					link.setAttribute('rel', 'noopener noreferrer');
				});
			} else {
				// Fallback to trimmed description
				descriptionEl.textContent = eventData.description || 'No description available.';
			}

			// Handle booking button for events (not classes) - only show if custom URL is set
			let existingBookingButton = modal.querySelector('.northam-event-booking-btn');
			
			// Only show booking button if hide is NOT checked AND custom URL exists
			const hasCustomUrl = isEvent && eventData.buttonUrl && eventData.buttonUrl.length > 0;
			const hideButton = isEvent && eventData.buttonHide === '1';
			
			if (hasCustomUrl && !hideButton) {
				// Determine button label
				let buttonLabel = eventData.buttonLabel && eventData.buttonLabel.length > 0 
					? eventData.buttonLabel 
					: 'Book now →';
				
				// Create or update booking button
				if (!existingBookingButton) {
					existingBookingButton = document.createElement('a');
					existingBookingButton.className = 'northam-event-booking-btn';
					modal.querySelector('.event-modal-description-section').appendChild(existingBookingButton);
				}
				
				existingBookingButton.href = eventData.buttonUrl;
				existingBookingButton.target = '_blank';
				existingBookingButton.rel = 'noopener noreferrer';
				existingBookingButton.textContent = buttonLabel;
				existingBookingButton.style.display = 'inline-block';
			} else if (existingBookingButton) {
				existingBookingButton.style.display = 'none';
			}
			
			// Handle share button for events (not classes)
			let existingShareButton = modal.querySelector('.northam-event-share-btn');
			
			if (isEvent && eventData.slug) {
				const shareUrl = `${window.location.origin}/events-calendar/?e=${eventData.slug}`;
				const shareTitle = eventData.title || 'Event';
				
				// Create or update share button
				if (!existingShareButton) {
					existingShareButton = document.createElement('button');
					existingShareButton.className = 'northam-event-share-btn';
					existingShareButton.textContent = 'Share';
					modal.querySelector('.event-modal-description-section').appendChild(existingShareButton);
				}
				
				existingShareButton.style.display = 'inline-block';
				
				// Share button click handler
				existingShareButton.onclick = function() {
					// Try native share API first
					if (navigator.share) {
						navigator.share({
							title: shareTitle,
							url: shareUrl
						}).catch(() => {
							// User cancelled, do nothing
						});
					} else {
						// Fallback: copy to clipboard
						navigator.clipboard.writeText(shareUrl).then(() => {
							const originalText = existingShareButton.textContent;
							existingShareButton.textContent = 'Link copied ✓';
							setTimeout(() => {
								existingShareButton.textContent = originalText;
							}, 2000);
						});
					}
					
					// Analytics: track share
					if (typeof window.gtag === 'function') {
						window.gtag('event', 'event_share', {
							event_title: shareTitle
						});
					}
				};
			} else if (existingShareButton) {
				existingShareButton.style.display = 'none';
			}

			// Update map link - use stored URL if available, otherwise generate search URL
			const mapLink = modal.querySelector('.detail-venue-link');
			if (eventData.mapUrl && eventData.mapUrl.length > 0) {
				// Validate protocol before setting href
				try {
					const parsed = new URL(eventData.mapUrl);
					if (parsed.protocol === 'http:' || parsed.protocol === 'https:') {
						mapLink.setAttribute('href', parsed.href);
					} else {
						mapLink.removeAttribute('href');
					}
				} catch (e) {
					mapLink.removeAttribute('href');
				}
			} else if (eventData.venue) {
				const searchQuery = encodeURIComponent(eventData.venue + ', Northam, Devon');
				mapLink.setAttribute('href', `https://www.google.com/maps/search/?api=1&query=${searchQuery}`);
			} else {
				mapLink.removeAttribute('href');
			}

			// Show modal
			modal.classList.add('modal-open');
			document.body.style.overflow = 'hidden';

			// Trigger slide-up animation
			setTimeout(() => {
				modalContent.classList.add('modal-slide-in');
			}, 10);

			modal.classList.add('modal-open');

			// Update URL with event slug for shareable deep links (EM events only)
			if (isEvent && eventData.slug && eventData.slug.length > 0) {
				const url = new URL(window.location);
				url.searchParams.set('e', eventData.slug);
				window.history.replaceState({}, '', url);
			}

			// Analytics: record which event/class was opened
			if (typeof window.gtag === 'function') {
				window.gtag('event', 'event_open', {
					event_title: eventData.title || '',
					event_venue: eventData.venue || ''
				});
			}

		}

		// Close modal
		function closeModal() {
			modalContent.classList.remove('modal-slide-in');
			setTimeout(() => {
				modal.classList.remove('modal-open');
				document.body.style.overflow = '';
			}, 300);

			// Remove ?e= from URL
			const url = new URL(window.location);
			if (url.searchParams.has('e')) {
				url.searchParams.delete('e');
				window.history.replaceState({}, '', url);
			}
		}

		// Add click event to all event cards
		const eventCards = document.querySelectorAll('.northam-calendar-event, .mobile-event-card');
		eventCards.forEach(card => {
			card.style.cursor = 'pointer';
			card.addEventListener('click', function() {
				// Get event data from parent element or card itself
				let eventData = {};

				// Try to get data from the card
				const dataElement = this.closest('[data-event-title]') || this;

				if (dataElement.dataset.eventTitle) {
					eventData = {
						title: dataElement.dataset.eventTitle || '',
						date: dataElement.dataset.eventDate || '',
						time: dataElement.dataset.eventTime || '',
						venue: dataElement.dataset.eventVenue || '',
						type: dataElement.dataset.eventType || 'community',
						description: dataElement.dataset.eventDescription || '',
						frequency: dataElement.dataset.eventFrequency || '',
						contact: dataElement.dataset.eventContact || '',
						phone: dataElement.dataset.eventPhone || '',
						mapUrl: dataElement.dataset.eventMapUrl || '',
						image: dataElement.dataset.eventImage || '',
						url: dataElement.dataset.eventUrl || '',
						contentHtml: dataElement.dataset.eventContentHtml || '',
						buttonUrl: dataElement.dataset.eventButtonUrl || '',
						buttonLabel: dataElement.dataset.eventButtonLabel || '',
						buttonHide: dataElement.dataset.eventButtonHide || '',
						slug: dataElement.dataset.eventSlug || ''
					};
				} else {
					// Fallback: extract from DOM
					eventData = {
						title: this.querySelector('.event-title, .mobile-event-title')?.textContent || '',
						time: this.querySelector('.event-time, .mobile-event-meta span:first-child')?.textContent.replace(/\s+/g, ' ').trim() || '',
						venue: this.querySelector('.mobile-event-meta span:last-child')?.textContent || '',
						date: '',
						type: this.className.match(/northam-event-(\w+)/)?.[1] || 'community',
						description: this.querySelector('.mobile-event-description')?.textContent || '',
						mapUrl: ''
					};
				}

				openModal(eventData);
			});
		});

		// Close button click
		if (closeBtn) {
			closeBtn.addEventListener('click', closeModal);
		}

		// Overlay click
		if (modalOverlay) {
			modalOverlay.addEventListener('click', closeModal);
		}

		// Escape key
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && modal.classList.contains('modal-open')) {
				closeModal();
			}
		});

		// Prevent modal content clicks from closing
		if (modalContent) {
			modalContent.addEventListener('click', function(e) {
				e.stopPropagation();
			});
		}
		
		/**
		 * Build a contact element (link or plain text)
		 * @param {string} contact - Contact string (URL, domain, or other text)
		 * @returns {Node} - Text node or anchor element
		 */
		function buildContactElement(contact) {
			if (!contact) return document.createTextNode('');
			
			// Check if it's a URL or domain
			const urlPattern = /^(https?:\/\/|www\.|[a-z0-9-]+\.[a-z]{2,})/i;
			const isUrl = urlPattern.test(contact);
			
			if (isUrl) {
				// Build a proper URL
				let fullUrl = contact;
				if (!fullUrl.startsWith('http')) {
					fullUrl = 'https://' + fullUrl;
				}
				
				// Append UTM parameters for tracking
				const urlObj = new URL(fullUrl);
				urlObj.searchParams.set('utm_source', 'northamdevon');
				urlObj.searchParams.set('utm_medium', 'events_calendar');
				urlObj.searchParams.set('utm_campaign', 'class_contact');
				
				// Clean display text: remove protocol, www, trailing slash
				let displayText = contact
					.replace(/^https?:\/\//, '')
					.replace(/^www\./, '')
					.replace(/\/$/, '');
				
				const link = document.createElement('a');
				link.href = urlObj.toString();
				link.textContent = displayText;
				link.target = '_blank';
				link.rel = 'noopener noreferrer';
				return link;
			}
			
			// Not a URL - return as plain text
			return document.createTextNode(contact);
		}
		
		/**
		 * Build a phone element (tel: link)
		 * @param {string} phone - Phone number
		 * @returns {Node} - Anchor element or text node
		 */
		function buildPhoneElement(phone) {
			if (!phone) return document.createTextNode('');
			
			// Check if it looks like a phone number
			const phonePattern = /[\d\s\-+()]{7,}/;
			const isPhone = phonePattern.test(phone);
			
			if (isPhone) {
				// Clean phone for tel: link (remove spaces, dashes, brackets)
				const cleanPhone = phone.replace(/[\s\-()]/g, '');
				
				const link = document.createElement('a');
				link.href = 'tel:' + cleanPhone;
				link.textContent = phone; // Keep original formatting for display
				return link;
			}
			
			// Not a phone number - return as plain text
			return document.createTextNode(phone);
		}

	// Auto-open modal if ?e= parameter matches a visible event slug
	const urlParams = new URLSearchParams(window.location.search);
	const deepLinkSlug = urlParams.get('e');
	if (deepLinkSlug) {
		// Find all matching event cards by slug
		const allMatchingCards = document.querySelectorAll(
			`.northam-calendar-event[data-event-slug="${deepLinkSlug}"], ` +
			`.mobile-event-card[data-event-slug="${deepLinkSlug}"]`
		);
		
		// Find the VISIBLE card (offsetParent !== null means it's visible)
		let visibleCard = null;
		allMatchingCards.forEach(card => {
			if (card.offsetParent !== null && !visibleCard) {
				visibleCard = card;
			}
		});
		
		
		if (visibleCard) {
			// Trigger the click event to open the modal
			visibleCard.click();
		}
		// If no match found, just show the calendar normally (no error)
	}
	});
})();
