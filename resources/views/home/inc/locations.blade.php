<?php
// Default Map's values
$loc = [
	'show'       		=> false,
	'itemsCols'  		=> 3,
	'showButton' 		=> false,
	'countCitiesPosts' 	=> false,
];
$map = ['show' => false];

// Get Admin Map's values
if (isset($citiesOptions)) {
	if (isset($citiesOptions['show_cities']) and $citiesOptions['show_cities'] == '1') {
		$loc['show'] = true;
	}
	if (isset($citiesOptions['items_cols']) and !empty($citiesOptions['items_cols'])) {
		$loc['itemsCols'] = (int)$citiesOptions['items_cols'];
	}
	if (isset($citiesOptions['show_post_btn']) and $citiesOptions['show_post_btn'] == '1') {
		$loc['showButton'] = true;
	}
	
	if (file_exists(config('larapen.core.maps.path') . config('country.icode') . '.svg')) {
		if (isset($citiesOptions['show_map']) and $citiesOptions['show_map'] == '1') {
			$map['show'] = true;
		}
	}
	
	if (config('settings.listing.count_cities_posts')) {
		$loc['countCitiesPosts'] = true;
	}
}
$hideOnMobile = '';
if (isset($citiesOptions, $citiesOptions['hide_on_mobile']) and $citiesOptions['hide_on_mobile'] == '1') {
	$hideOnMobile = ' hidden-sm';
}
?>





@if ($loc['show'] || $map['show'])
<div class="greycont">
<div class="container{{ $hideOnMobile }}">
	<div class="col-xl-12 page-content p-0">



		<div>
			<div class="container text-center">

				<h1 class="intro-title animated fadeInDown"> Trade within your neighbourhood</h1>
				<h2 class="sub animateme fittext3 animated fadeIn">
					Discover the Bayobab Way of Life
				</h2>



			</div>
			<div class="row">
				@if (!$map['show'])
					<div class="row">
						<div class="col-xl-12 col-sm-12">
							<h2 class="title-3 pt-1 pr-3 pb-3 pl-3" style="white-space: nowrap;">
								<i class="icon-location-2"></i>&nbsp;{{ t('Choose a city') }}
							</h2>
						</div>
					</div>
				@endif
				<?php
				$leftClassCol = '';
				$rightClassCol = '';
				$ulCol = 'col-md-3 col-sm-12'; // Cities Columns
				
				if ($loc['show'] && $map['show']) {
					// Display the Cities & the Map
					$leftClassCol = 'col-lg-5 col-md-12';
					$rightClassCol = 'col-lg-4 col-md-12 mt-3 mt-xl-0 mt-lg-0';
					$ulCol = 'col-md-4 col-sm-6 col-xs-12';
					
					if ($loc['itemsCols'] == 2) {
						$leftClassCol = 'col-md-6 col-sm-12';
						$rightClassCol = 'col-md-5 col-sm-12';
						$ulCol = 'col-md-6 col-sm-12';
					}
					if ($loc['itemsCols'] == 1) {
						$leftClassCol = 'col-md-3 col-sm-12';
						$rightClassCol = 'col-md-8 col-sm-12';
						$ulCol = 'col-xl-12';
					}
				} else {
					if ($loc['show'] && !$map['show']) {
						// Display the Cities & Hide the Map
						$leftClassCol = 'col-xl-12';
					}
					if (!$loc['show'] && $map['show']) {
						// Display the Map & Hide the Cities
						$rightClassCol = 'col-xl-12';
					}
				}
				?>



				@if ($loc['show'])
				<div class="{{ $leftClassCol }} page-content m-0 p-0">



					<div class="row">
						<div class="col-xl-12">
							<div style="clear:both"></div>
							<div class="col-lg-6 col-sm-12 col-md-12 p-lg-0 p-md-4 p-sm-4">
								<form id="modalAdminForm" name="modalAdminForm" method="POST">
									<input type="hidden"
										   id="currSearch"
										   name="curr_search"
										   value="{!! base64_encode(serialize(request()->except(['l', 'location', '_token']))) !!}"
									>
									<select class="form-control chooseregionselect" id="modalAdminField" name="admin_code">
										<option selected value="">{{ t('Choose your region…') }}</option>
										@if (isset($modalAdmins) and $modalAdmins->count() > 0)
											@foreach($modalAdmins as $admin1)
												<option value="{{ $admin1->code }}">{{ $admin1->name }}</option>
											@endforeach
										@endif
									</select>
									{!! csrf_field() !!}
								</form>
							</div>
							<div style="clear:both"></div>
							<hr class="hr-thin">
							<p id="selectedAdmin"><i class="icon-location-2"></i> {{ t('Popular cities in') }} <strong>{{ config('country.name') }}</strong></p>

						</div>

						<div class="p-1">
						<div class="col-xl-12" id="adminCities">

							<div class="row">

							@foreach ($cities as $key => $items)


									<div class="col-4">
										<ul class="list-link list-unstyled">
									@foreach ($items as $k => $city)
										<li>
											@if ($city->id == 0)

											@else
												<a href="{{ \App\Helpers\UrlGen::city($city) }}">
													{{ $city->name }}
												</a>
												@if ($loc['countCitiesPosts'])
													&nbsp;({{ $city->posts_count ?? 0 }})
												@endif
											@endif
										</li>
									@endforeach
								</ul>
									</div>



							@endforeach
							</div>

						</div>
						</div>

					</div>

					@if (isset($cities))
						<div class="relative location-content">

							<div class="col-xl-12 tab-inner">
								<div class="row">

								</div>
							</div>

						</div>
					@endif
				</div>
				@endif

					<div class="col-lg-2 col-sm-0">

					</div>


				@includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.locations.svgmap', 'home.inc.locations.svgmap'])
			</div>


		</div>
	</div>
</div>
</div>
@endif

@section('modal_location')
	@parent
	@if ($loc['show'] || $map['show'])
		@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.modal.location', 'layouts.inc.modal.location'])
	@endif
@endsection
