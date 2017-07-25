<?php
/*
Template Name: Home Page Template
*/
global $trav_options, $search_max_rooms, $search_max_adults, $search_max_kids, $def_currency;
$all_features = array( 'acc', 'tour', 'real');
$enabled_features = array();
foreach( $all_features as $feature ) {
	if ( empty( $trav_options['disable_' . $feature ] ) ) $enabled_features[] = $feature;
}
get_header();
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$slider_active = get_post_meta( get_the_ID(), 'trav_page_slider', true );
		$slider        = ( $slider_active == '' ) ? 'Deactivated' : $slider_active;
		if ( class_exists( 'RevSlider' ) && $slider != 'Deactivated' ) {
			echo '<div id="slideshow">';
			putRevSlider( $slider );
			echo '</div>';
		} ?>
		<section id="content"<?php if ( count( $enabled_features ) > 1 ) echo ' class="no-padding"' ?>>
			<div class="search-box-wrapper">
				<div class="search-box container">
					<?php if ( count( $enabled_features ) > 1 ) : ?>
						<ul class="search-tabs clearfix">
							<?php if ( in_array('acc', $enabled_features) ) : ?>
								<li <?php if ( $enabled_features[0] == 'acc' ) echo 'class="active"' ?> ><a href="#hotels-tab" data-toggle="tab"><?php _e( 'HOTELS', 'trav' ) ?></a></li>
							<?php endif; ?>
							<?php if ( in_array('tour', $enabled_features) ) : ?>
								<li <?php if ( $enabled_features[0] == 'tour' ) echo 'class="active"' ?> ><a href="#tours-tab" data-toggle="tab"><?php _e( 'TOURS', 'trav' ) ?></a></li>
							<?php endif; ?>
							<?php if ( in_array('real', $enabled_features) ) : ?>
								<li <?php if ( $enabled_features[0] == 'real' ) echo 'class="active"' ?> ><a href="#real-tab" data-toggle="tab"><?php _e( 'REAL STATE', 'trav' ) ?></a></li>
							<?php endif; ?>
						</ul>
						<div class="visible-mobile">
						<ul id="mobile-search-tabs" class="search-tabs clearfix">
							<?php if ( in_array('acc', $enabled_features) ) : ?>
								<li <?php if ( $enabled_features[0] == 'acc' ) echo 'class="active"' ?> ><a href="#hotels-tab" data-toggle="tab"><?php _e( 'HOTELS', 'trav' ) ?></a></li>
							<?php endif; ?>
							<?php if ( in_array('tour', $enabled_features) ) : ?>
								<li <?php if ( $enabled_features[0] == 'tour' ) echo 'class="active"' ?> ><a href="#tours-tab" data-toggle="tab"><?php _e( 'TOURS', 'trav' ) ?></a></li>
							<?php endif; ?>
							<?php if ( in_array('real', $enabled_features) ) : ?>
								<li <?php if ( $enabled_features[0] == 'real' ) echo 'class="active"' ?> ><a href="#real-tab" data-toggle="tab"><?php _e( 'REAL STATE', 'trav' ) ?></a></li>
							<?php endif; ?>
						</ul>
					</div>
					<?php endif; ?>
					<div class="search-tab-content">
						<?php if ( in_array('acc', $enabled_features) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'acc' ) echo ' active in' ?>" id="hotels-tab">
							<?php endif; ?>
							<form role="search" method="get" id="searchform" class="acc-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="accommodation">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Your Destination','trav' ); ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or hotel name', 'trav') ?>" />
									</div>

									<div class="form-group col-sm-6 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row search-when" data-error-message1="<?php echo __( 'Your check-out date is before your check-in date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for check-in and check-out.' , 'trav') ?>">
											<div class="col-xs-6">
												<label><?php _e( 'CHECK IN','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'CHECK OUT','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Who','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-4">
												<label><?php _e( 'Rooms','trav' ); ?></label>
												<div class="selector">
													<select name="rooms" class="full-width">
														<?php
															$rooms = ( isset( $_GET['rooms'] ) && is_numeric( (int) $_GET['rooms'] ) )?(int) $_GET['rooms']:1;
															for ( $i = 1; $i <= $search_max_rooms; $i++ ) {
																$selected = '';
																if ( $i == $rooms ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-4">
												<label><?php _e( 'Adults','trav' ); ?></label>
												<div class="selector">
													<select name="adults" class="full-width">
														<?php
															$adults = ( isset( $_GET['adults'] ) && is_numeric( (int) $_GET['adults'] ) )?(int) $_GET['adults']:1;
															for ( $i = 1; $i <= $search_max_adults; $i++ ) {
																$selected = '';
																if ( $i == $adults ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-4">
												<label><?php _e( 'Kids','trav' ); ?></label>
												<div class="selector">
													<select name="kids" class="full-width">
														<?php
															$kids = ( isset( $_GET['kids'] ) && is_numeric( (int) $_GET['kids'] ) )?(int) $_GET['kids']:0;
															for ( $i = 0; $i <= $search_max_kids; $i++ ) {
																$selected = '';
																if ( $i == $kids ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="age-of-children no-display">
											<h5><?php _e( 'Age of Children','trav' ); ?></h5>
											<div class="row">
												<div class="col-xs-4 child-age-field">
													<label><?php echo __( 'Child ', 'trav' ) . '1' ?></label>
													<div class="selector validation-field">
														<select name="child_ages[]" class="full-width">
															<?php
																$max_kid_age = 17;
																for ( $i = 0; $i <= $max_kid_age; $i++ ) {
																	echo '<option value="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</option>';
																}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					<!--Tour State Tab-->	
						<?php if ( in_array('tour', $enabled_features) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'tour' ) echo ' active in' ?>" id="tours-tab">
							<?php endif; ?>
							<form role="search" method="get" id="searchform" class="tour-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="tour">
								<div class="row">
									<div class="form-group col-sm-4 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Destination ', 'trav' ) ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or tour name', 'trav') ?>" />
									</div>
									<div class="form-group col-sm-8 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-6">
												<label><?php _e( 'From', 'trav' ) ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'To', 'trav' ) ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-3 fixheight">
										<?php $trip_types = get_terms( 'tour_type' ); ?>
										<div class="row">
											<?php if ( ! empty( $trip_types ) ) : ?>
												<div class="col-xs-6">
													<label><?php _e( 'Trip Type', 'trav' ) ?></label>
													<div class="selector">
														<select name="tour_types" class="full-width">
															<option value=""><?php _e( 'Trip Type', 'trav' ) ?></option>
															<?php foreach ( $trip_types as $trip_type ) : ?>
																<option value="<?php echo $trip_type->term_id ?>"><?php _e( $trip_type->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											<?php endif; ?>
											<div class="col-xs-6">
												<label><?php _e( 'Budget', 'trav' ) ?></label>
												<input type="text" name="max_price" class="input-text full-width" placeholder="<?php echo sprintf( __( 'Max Budget (%s)', 'trav'), $def_currency ) ?>" />
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
						<!--Real State Tab-->	
						<?php if ( in_array('real', $enabled_features) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
          						  <div class="tab-pane fade<?php if ( $enabled_features[0] == 'real' ) echo ' active in' ?>" id="real-tab">
						<?php endif; ?>
						
						<form role="search" method="get" id="searchform" class="real-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="real">
								<div class="row">
										<div class="ere-title form-group col-sm-4 col-md-4">
											<label><?php _e( 'Title', 'trav' ) ?></label>
		                                                <input type="text"
		                                                    value=""
		                                                    data-default-value=""
		                                                    name="title"
		                                                    class="input-text full-width"
		                                                    placeholder="<?php _e( 'Title', 'trav') ?>" />
		                                <div id="ere_result_by_title" class="ere-result-by-title"></div>
										</div>
										<div class="ere-location form-group col-sm-4 col-md-4">
											<label><?php _e( 'Address', 'trav' ) ?></label>
												<input type="text"
													value=""
                                                    data-default-value=""
                                                    name="keyword"
                                                    class="input-text full-width"
                                                    placeholder="<?php _e( 'Address', 'trav') ?>" />
										</div>
										<div class="form-group col-sm-4 col-md-4">
											<label><?php _e( 'City', 'trav' ) ?></label>
										        <select name="city"
										                class="ere-property-city-ajax search-field"
										                title="Cities" data-selected="" data-default-value="">
										            	<option value="austin">Austin</option><option value="baytown">Baytown</option><option value="cedar-hill">Cedar Hill</option><option value="chester">Chester</option><option value="chicago">Chicago</option><option value="coleman">Coleman</option><option value="corpus-christi">Corpus Christi</option><option value="dallas">Dallas</option><option value="fayetteville">Fayetteville</option><option value="galveston">Galveston</option><option value="jersey-city">Jersey City</option><option value="los-angeles">Los Angeles</option><option value="midland">Midland</option><option value="new-york">New York</option><option value="odessa">Odessa</option><option value="san-angelo">San Angelo</option><option value="san-antonio">San Antonio</option>
										            	<option value="" selected> <?php _e( 'All Cities', 'trav' ) ?> </option>
										        </select>
    									</div>	
    									<div class="form-group col-sm-4 col-md-4">
    										<label><?php _e( 'Types', 'trav' ) ?></label>
	        									<select name="type" 
	        										title="Property Types"
	        										class="search-field" data-default-value="">
	                    							<option value="apartment">Apartment</option><option value="bar">Bar</option><option value="cafe">Cafe</option><option value="farm">Farm</option><option value="house">House</option><option value="restaurant">Restaurant</option><option value="spa">Spa</option><option value="store">Store</option><option value="villa">Villa</option>
	                   								<option value="" selected><?php _e( 'All Types', 'trav' ) ?></option>
	        									</select>
    									</div>
    									 <div class="form-group col-md-4 col-sm-6 col-xs-6 col-mb-12">
	    									 <label><?php _e( 'Bedrooms', 'trav' ) ?></label>
	            								<select name="bedrooms" title="Property Bedrooms"
	                									class="search-field" data-default-value="any">
	                    								<option value="any"><?php _e( 'Any Bedrooms', 'trav' ) ?></option><option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option><option value="6" >6</option><option value="7" >7</option><option value="8" >8</option><option value="9" >9</option><option value="10" >10</option></select>
	                    				</div>
                    					<div class="form-group col-md-4 col-sm-6 col-xs-6 col-mb-12">
	    									 <label><?php _e( 'Bathrooms', 'trav' ) ?></label>
	            								<select name="bathrooms" title="Property Bathrooms"
	                									class="search-field" data-default-value="any">
	                    								<option value="any"><?php _e( 'Any Bathrooms', 'trav' ) ?></option><option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option><option value="6" >6</option><option value="7" >7</option><option value="8" >8</option><option value="9" >9</option><option value="10" >10</option></select>
	                    				</div>
                    			</div> <!--row end-->
							</form>
        
           					<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>  <!--fade panel-->
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</section>
	<?php endwhile;
endif;
get_footer();