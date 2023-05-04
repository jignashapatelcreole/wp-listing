<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://creolestudios.com
 * @since      1.0.0
 *
 * @package    Wp_Listing
 * @subpackage Wp_Listing/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @author     Creole Studios <mohip.patel@creolestudios.com>
 */
class Wp_Listing {

	/**
	 * Add actions.
	 */
	public function __construct() {
		add_shortcode( 'custom_shortcode', array( $this, 'wplisting_shortcode_handler' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wplisting_scripts' ) );
	}

	/**
	 * Enqueue custom field assets.
	 *
	 * @return void
	 */
	public function wplisting_scripts() {
		wp_enqueue_style( 'stylesheet', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), filemtime( get_template_directory() . '/style.css' ) );
		wp_enqueue_style( 'listing-style', WP_LISTING_PLUGIN_URL . 'includes/css/wp-listing.css', array(), filemtime( get_template_directory() . '/style.css' ) );
	}

	/**
	 * Shortcode handler for listing.
	 *
	 * @param string $atts Sorting attributes .
	 *
	 * @return string
	 */
	public function wplisting_shortcode_handler( $atts ) {

		// Shortcode attributes.
		$atts = shortcode_atts(
			array(
				'sorting' => 'a',
			),
			$atts
		);

		// Load JSON data from file.
		$json_file = WP_LISTING_PLUGIN_PATH . 'data.json';
		$json_data = wp_json_file_decode( $json_file, true );

		$site_url    = get_site_url();
		$casino_data = array();

		if ( null === $json_data ) {
			// return custom message for JSON parsing errors.
			return __( 'The JSON file does not exist or not valid content!', 'wp-listing' );
		} else {
			// Set array based on json data.
			foreach ( $json_data->toplists as $data ) {
				foreach ( $data as $json_array ) {
					$casino_data[] = $json_array;
				}
			}
			ob_start();

			// Set array key and order based on shortcode attributes.
			if ( '0' === strval( $atts['sorting'] ) ) {
				$key_values = array_column( $casino_data, 'position' );
				$sortorder  = SORT_ASC;

			} elseif ( '1' === strval( $atts['sorting'] ) ) {
				$key_values = array_column( $casino_data, 'position' );
				$sortorder  = SORT_DESC;
			} else {
				$key_values = array_map(
					function ( $value ) {
						return $value->info->bonus;
					},
					$casino_data
				);
				$sortorder  = SORT_NATURAL;
			}

			// sort multidimensional array based on array key bonus or position.
			array_multisort( $key_values, $sortorder, $casino_data );
			if ( isset( $casino_data ) && ! empty( $casino_data ) ) {
				?>
				<div class="listing-table-wrapper">
					<table class="listing-table">
						<thead class="listing-table--head">
							<tr>
								<th><?php echo esc_html__( 'Casino', 'wp-listing' ); ?></th>
								<th><?php echo esc_html__( 'bonus', 'wp-listing' ); ?></th>
								<th><?php echo esc_html__( 'features', 'wp-listing' ); ?></th>
								<th><?php echo esc_html__( 'Play', 'wp-listing' ); ?></th>
							</tr>
						</thead>
				<?php
				// Main table content.
				foreach ( $casino_data as $json_array ) {
					$data_array[]         = $json_array;
					$logo                 = $json_array->logo;
					$revire_url           = $site_url . '/' . $json_array->brand_id;
					$rating               = $json_array->info->rating;
					$bonus                = $json_array->info->bonus;
					$play_url             = $json_array->play_url;
					$terms_and_conditions = $json_array->terms_and_conditions;
					?>
					<tr class="listing-table--content">
						<td class="listing-table--content_casino" >
							<?php
							if ( ! empty( $logo ) ) {
								?>
								<img class="listing-table--content__image" src="<?php echo esc_url( $logo ); ?>" alt="">
								<?php
							}
							if ( ! empty( $revire_url ) ) {
								?>
								<a class="listing-table--content__review" href="<?php echo esc_url( $revire_url ); ?>"><?php echo esc_html__( 'Review', 'wp-listing' ); ?></a>
								<?php
							}
							?>
						</td>
						<td class="listing-table--content_bonus">
							<?php
							if ( ! empty( $rating ) ) {
								for ( $count = 0; $count < 5; $count++ ) {
									$checked = $count < $rating ? 'checked' : '';
									?>
									<span class="fa fa-star <?php echo esc_attr( $checked ); ?>"></span>
									<?php
								}
							}
							if ( ! empty( $rating ) ) {
								?>
								<p class="listing-table--content__bonus"><?php echo esc_attr( $bonus ); ?></p>
								<?php
							}
							?>
						</td>
						<td class="listing-table--content_feature">
							<?php
							if ( ! empty( $json_array->info->features ) ) {
								?>
								<ul>
									<?php
									foreach ( $json_array->info->features as $feature ) {
										?>
										<li class="listing-table--content__feature"><?php echo esc_attr( $feature ); ?></li>
										<?php
									}
									?>
								</ul>
								<?php
							}
							?>
						</td>
						<td class="listing-table--content_play">
							<?php
							if ( ! empty( $play_url ) ) {
								?>
								<button class="listing-table--content__playurl"> <a href="<?php echo esc_url( $play_url ); ?>"><?php echo esc_html__( 'PLAY NOW', 'wp-listing' ); ?></a></button>
								<?php
							}
							if ( ! empty( $terms_and_conditions ) ) {
								?>
								<p class="listing-table--content__tnc"><?php echo wp_kses_post( $terms_and_conditions ); ?></p>
								<?php
							}
							?>
						</td>
					</tr>
					<?php
				}
			} else {
				return __( 'Data Not Found!', 'wp-listing' );
			}
			?>
					</table>
				</div>
			<?php
		}

		$output = ob_get_contents();
		ob_end_clean();
		return wp_kses_post( $output );
	}
}
