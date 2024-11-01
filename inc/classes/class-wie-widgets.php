<?php
/**
 * Widget Functions
 *
 * @package    Widget_Importer_Exporter
 * @subpackage Functions
 * @copyright  Copyright (c) 2013 - 2017, ChurchThemes.com
 * @link       https://churchthemes.com/plugins/widget-importer-exporter/
 * @license    GPLv2 or later
 * @since      0.4
 */

// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Show result
function TOC_wie_have_import_results() {

				global $wie_import_results;

				if ( ! empty( $wie_import_results ) ) {
					return true;
				}

				return false;

			}

			/**
			 * Show import results
			 *
			 * This is shown in place of import/export page's regular content.
			 *
			 * @since 0.3
			 * @global string $wie_import_results
			 */
			function TOC_wie_show_import_results() {

				global $wie_import_results;

				?>

				<h3 class="title"><?php echo esc_html_x( 'Widgets Import Results', 'heading', 'widget-importer-exporter' ); ?></h3>

				<p>
					<?php
					printf(
						wp_kses(
							/* translators: %1$s is URL for widgets screen, %2$s is URL to go back */
							__( 'You can manage your <a href="%1$s">Widgets</a> or <a href="%2$s">Go Back</a>.', 'widget-importer-exporter' ),
							array(
								'a' => array(
									'href' => array(),
								),
							)
						),
						esc_url( admin_url( 'widgets.php' ) ),
						esc_url( admin_url( basename( $_SERVER['PHP_SELF'] ) . '?page=' . $_GET['page'] ) )
					);
					?>
				</p>

				<table id="wie-import-results" class="table table-bordered">

					<?php
					// Loop sidebars.
					$results = $wie_import_results;
					foreach ( $results as $sidebar ) :
					?>

						<tr class="wie-import-results-sidebar">
							<th colspan="2" class="wie-import-results-sidebar-name">
								<?php
								// Sidebar name if theme supports it; otherwise ID.
								echo esc_html( $sidebar['name'] );
								?>
							</th>
							<th class="wie-import-results-sidebar-message wie-import-results-message wie-import-results-message-<?php echo esc_attr( $sidebar['message_type'] ); ?>">
								<?php
								// Sidebar may not exist in theme.
								echo esc_html( $sidebar['message'] );
								?>
							</th>
						</tr>

						<?php
						// Loop widgets.
						foreach ( $sidebar['widgets'] as $widget ) :
						?>

							<tr class="wie-import-results-widget">
								<td class="wie-import-results-widget-name">
									<?php
									// Widget name or ID if name not available (not supported by site).
									echo esc_html( $widget['name'] );
									?>
								</td>
								<td class="wie-import-results-widget-title">
									<?php
									// Shows "No Title" if widget instance is untitled.
									echo esc_html( $widget['title'] );
									?>
								</td>
								<td class="wie-import-results-widget-message wie-import-results-message wie-import-results-message-<?php echo esc_attr( $widget['message_type'] ); ?>">
									<?php
									// Sidebar may not exist in theme.
									echo esc_html( $widget['message'] );
									?>
								</td>
							</tr>

						<?php endforeach; ?>

						<tr class="wie-import-results-space">
							<td colspan="100%"></td>
						</tr>

					<?php endforeach; ?>

				</table>
				<?php
			}
