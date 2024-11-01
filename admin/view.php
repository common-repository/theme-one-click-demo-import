<?php
$my_theme = wp_get_theme();
$current_theme = esc_html( $my_theme->get( 'Name' ) );
$current_theme_author = esc_html( $my_theme->get( 'Author' ) );
?>
<?php
	if( $pre_demo_options[0]['import_file'] != "No File"):?>
		<div class="wrap">
			<?php echo '<h1 class="wp-heading-inline">' . esc_html__( 'Theme One Click Demo Setup', 'TOC-demo-import' ) . '</h1>';	   ?>
			<hr class="wp-header-end">
			<div class="wp-filter hide-if-no-js">
				<ul class="filter-links nav nav-pills " id="pills-tab" role="tablist">
					<li><a class="active" id="pills-all-tab" data-toggle="pill" href="#pills-all" role="tab" aria-controls="pills-home" aria-selected="true"><?php echo esc_html__( 'All', 'TOC-demo-import' ); ?></a></li>
				</ul>

			</div>
			<div class="theme-browser rendered">
				<div class="tab-content themes wp-clearfix" id="pills-tabContent">
					<div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-home-tab">
						<?php
						foreach ($pre_demo_options as $key => $import_file) {
							$import_demo_number = $import_file['import_file'];			
							$theme_name	= $import_file['theme_name'];
							$import_file_url = $import_file['import_file_url'];
							$import_widget_file_url = $import_file['import_widget_file_url'];
							$import_customizer_file_url = $import_file['import_customizer_file_url'];
							$import_preview_image_url = $import_file['import_preview_image_url'];
							$import_preview_url = $import_file['import_preview_url'];
							$demo_key = $key;

							if (file_exists($import_preview_image_url))
							{
								
							}	 	
							$img_src = isset( $import_file['import_preview_image_url'] ) ? $import_file['import_preview_image_url'] : '';	
							if($current_theme == $theme_name):
								$word ="Pro"	?>
								<div class="theme" tabindex="0">
									<div class="theme-screenshot">
										<img src="<?php echo $img_src?>" alt="">
									</div><?php 
									if(strpos($import_demo_number, $word) == false): ?>
										<span class="more-details" id="mag-news-action" data-toggle="modal" data-target="#exampleModal<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Import Theme', 'TOC-demo-import' ); ?></span><?php
									else: ?>
										<span class="more-details" id="mag-news-action"><a href="<?php echo esc_url( $import_file['import_theme_url'] ); ?>" target="_blank" class="buy-theme"><?php esc_html_e( 'Buy Now', 'TOC-demo-import' ); ?></a></span><?php  
									endif; ?>
									<div class="theme-author"> <?php esc_html_e( 'By Theme 404', 'TOC-demo-import' ); ?></div>
									<div class="theme-id-container">
										<h2 class="theme-name" id="mag-news-name">
											<?php esc_html_e( $import_demo_number, 'TOC-demo-import' ); ?>
										</h2>
										<div class="theme-actions">
											<a class="button button-primary" href="<?php echo esc_url( $import_preview_url ); ?>" target="_blank" ><?php esc_html_e( 'Preview', 'TOC-demo-import' ); ?></a>
										</div>
									</div>
								</div><?php 
							endif; ?>

							<div class="modal fade" id="exampleModal<?php echo esc_attr( $key ); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e( 'Required Plugins', 'TOC-demo-import' ); ?></h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="ajax-loader ajax-modal-loader">
									<div class="loader modal-loader"></div>
								</div>
								<div class="modal-body">
									<?php $plugins = apply_filters( 'plugin_list', $import_demo_number); 

										//print_r($plugins);
										if(!empty($plugins)):
											foreach ($plugins as $key => $plugin) {
												?>
												<div class="list-plugin">
													<?php echo $plugin['name']; 
													$plugin_data = json_encode($plugin);
													?>
													<?php 
														if ( is_plugin_active( $plugin['install'] ) ):?>
															<button class="btn btn-secondary" style="float: right;" disabled="disabled"><?php esc_html_e( 'Active', 'TOC-demo-import' ); ?></button> <?php 
														else: 
															if(strpos($plugin['name'], $word) == false): ?>
																<button class="btn btn-primary install-plugin" style="float: right;" value='<?php echo $plugin_data;?>'><?php  esc_html_e( 'Install', 'TOC-demo-import' ); ?></button> <?php 
															else:?>
																<a href="<?php echo esc_url( $plugin['url'] ); ?>" target="_blank" class="btn btn-primary" style="float: right;"><?php esc_html_e( 'Get Now', 'TOC-demo-import' ); ?></a> <?php 
															endif;
														endif; 
													?>
												</div>

												<?php
											}
										else:
											echo "No Required Plugin";
										endif;
									?>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#secondModalCenter<?php echo esc_attr( $demo_key ); ?>"><?php esc_html_e( 'Next Step', 'TOC-demo-import' ); ?></button>
								</div>
								</div>
							</div>
							</div>

							<div class="modal fade" id="secondModalCenter<?php echo esc_attr( $demo_key ); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLongTitle"><?php esc_html_e( 'Import Options', 'TOC-demo-import' ); ?></h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="ajax-loader ajax-xml-loader">
									<div class="loader modal-loader"></div>
									<p class="loading-text"><span class="badge badge-secondary message"><?php esc_html_e( 'Importing XML File', 'TOC-demo-import' ); ?></span><br>
										<span class="badge badge-secondary no-message"><?php esc_html_e( 'Loading File. Please Wait.', 'TOC-demo-import' ); ?></span>
									</p>
								</div>
								<div class="ajax-loader ajax-wie-loader">
									<div class="loader modal-loader"></div>
									<p class="loading-text"><span class="badge badge-secondary message"><?php esc_html_e( 'Importing WIE File', 'TOC-demo-import' ); ?></span><br>
										<span class="badge badge-secondary no-message"><?php esc_html_e( 'Loading File. Please Wait.', 'TOC-demo-import' ); ?></span>
									</p>
									
								</div>
								<div class="ajax-loader ajax-dat-loader">
									<div class="loader modal-loader"></div>
									<p class="loading-text">
										<span class="badge badge-secondary message"><?php esc_html_e( 'Importing DAT File', 'TOC-demo-import' ); ?></span><br>
										<span class="badge badge-secondary no-message"><?php esc_html_e( 'Loading File. Please Wait.', 'TOC-demo-import' ); ?></span></p>
									
								</div>
								<div class="modal-body import-result<?php echo esc_attr( $demo_key ); ?>" style="min-height: 170px;">
									<p>
										<input type="checkbox" id="download-xml<?php echo esc_attr( $demo_key ); ?>" value="yes"><?php esc_html_e( 'Download XML File', 'TOC-demo-import' ); ?>
									</p>
									<p>
										<input type="checkbox" id="download-wie<?php echo esc_attr( $demo_key ); ?>" value="yes"><?php esc_html_e( 'Download WIE File', 'TOC-demo-import' ); ?>
									</p>
									<p>
										<input type="checkbox" id="download-dat<?php echo esc_attr( $demo_key ); ?>" value="yes"><?php esc_html_e( 'Download DAT File', 'TOC-demo-import' ); ?>
									</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary import-option" value="<?php echo esc_attr( $demo_key ); ?>" data-xmlid="#download-xml<?php echo esc_attr( $demo_key ); ?>"><?php esc_html_e( 'Import', 'TOC-demo-import' ); ?></button>
									<a href="<?php echo get_site_url(); ?>" class="btn btn-primary import-preview" target="_blank" >Preview</a>
								</div>
								</div>
							</div>
							</div>

							<?php	   
						}?>
					</div>
				</div>
			</div>
		</div>			
<?php 	else: ?>


<?php 	endif; ?>