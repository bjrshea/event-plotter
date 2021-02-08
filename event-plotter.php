<?php
/*
Plugin Name: Event Plotter
Description: Loops through WordPress Posts that have the "Event" category, creates calendar cards on the frontend, and plots the events on an interactive Google Map.
Author: Brendan Shea
Version: 0.1
*/
function event_plotter() {
	ob_start();
  ?>

	<div id="homepage-calendar">
		<div id="calendar-inner">
			<?php
			$currentDay = date("F jS, Y");
			$args = array(
				'category_name' => 'Event',
				'post_status' => array('publish', 'future'),
				'posts_per_page' => '6',
				'order' => 'ASC',
				'date_query' => array(
					array(
						'after' => $currentDay
					)
				)
			);
			$loop = new WP_Query($args);
			if ($loop->have_posts()) : while ($loop->have_posts()) : $loop->the_post();
			$id = get_the_ID();
			$title = get_the_title();
			$date = get_the_date( 'F j' );
			$dayOfWeek = get_the_date( 'l' );
			$street = get_post_meta($id, 'Street Address', true);
			$cityStateZip = get_post_meta($id, 'City, State, Zip', true);
			$start = get_post_meta($id, 'Start Time', true);
			$end = get_post_meta($id, 'End Time', true);
			$location = get_post_meta($id, 'Location', true);
			$link = get_post_meta($id, 'Link', true);
			$directions = get_post_meta($id, 'Directions', true);
			?>
			<div class="calendar-card" id="<?php echo $id ?>">
				<h2 class="event-date"><?php echo $date ?></h2>
				<h3 class="event-title"><?php echo $title ?></h3>
				<div class="event-section">
					<div class="event-section-title">
						<i class="fas fa-map-marker-alt"></i>
						<h4>Time</h4>
					</div>
					<p class="event-text">(<?php echo $dayOfWeek ?>) <?php echo $start ?> - <?php echo $end ?></p>
				</div>
				<div class="event-section">
					<div class="event-section-title">
						<i class="far fa-clock"></i>
						<h4>Location</h4>
					</div>
					<p class="event-text event-location"><?php echo $location ?></p>
					<p class="event-text event-address"><?php echo $street ?>, <?php echo $cityStateZip ?></p>
				</div>
				<?php if ($link != null) { ?>
				<div class="event-link">
					<i class="fas fa-plus"></i>
					<a class="event-link" href="<?php echo $link ?>">Visit Event Page</a>
				</div>
				<?php } ?>
				<?php if ($directions != null) { ?>
				<div class="event-link">
					<p class="directions"><em>*<?php echo $directions ?></em></p>
				</div>
				<?php } ?>
			</div>
			<?php endwhile; endif; ?>
		</div>
		<button id="view-map" href="#">View Map</button>
		<div id="g-map" style="max-height: 0; height: 500px;">

		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('event_plotter','event_plotter');

function event_plotter_assets() {
  wp_enqueue_script( 'scripts',  plugin_dir_url( __FILE__ ) . '/js/scripts.js' );
	wp_enqueue_style( 'styles',  plugin_dir_url( __FILE__ ) . '/css/styles.css' );
}
add_action('wp_enqueue_scripts', 'event_plotter_assets');
