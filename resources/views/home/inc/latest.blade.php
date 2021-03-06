<?php
if (!isset($cacheExpiration)) {
    $cacheExpiration = (int)config('settings.optimization.cache_expiration');
}
if (config('settings.listing.display_mode') == '.compact-view') {
	$colDescBox = 'col-sm-9 col-12';
	$colPriceBox = 'col-sm-3 col-12';
} else {
	$colDescBox = 'col-sm-7 col-12';
	$colPriceBox = 'col-sm-3 col-12';
}
$hideOnMobile = '';
if (isset($latestOptions, $latestOptions['hide_on_mobile']) and $latestOptions['hide_on_mobile'] == '1') {
	$hideOnMobile = ' hidden-sm';
}
?>
@if (isset($latest) && !empty($latest) && $latest->posts->count() > 0)
	@includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.spacer', 'home.inc.spacer'], ['hideOnMobile' => $hideOnMobile])
	<div class="container{{ $hideOnMobile }}">
		<div class="col-xl-12 layout-section">
			<div class="row row-featured row-featured-category">



				<div class="container text-center">

					<h1 class="intro-title animated fadeInDown">{!! $latest->title !!}</h1>
					<h2 class="sub animateme fittext3 animated fadeIn">
						Ready to declutter and make money?
					</h2>



				</div>



					@foreach($latest->posts as $key => $post)
						@continue(empty($post->city))
						<?php
						// Main Picture
						if ($post->pictures->count() > 0) {
							$postImg = imgUrl($post->pictures->get(0)->filename, 'large');
						} else {
							$postImg = imgUrl(config('larapen.core.picture.default'), 'large');
						}

						$userPhoto = imgUrl($post->user_photo_url, 'large');

						?>


						<div class="col-sm-12 col-md-12 col-lg-3">
							<a href="{{ \App\Helpers\UrlGen::post($post) }}">
								<div class="row p-0 mb-2">
									<div class="col-3 p-0 pl-4">
										<img src="{{$post->user_photo_url}}" class="userImage rounded-circle">
									</div>
									<div class="col-9 p-0 m-0 pt-1">
										{{$post->contact_name}}
									</div>
								</div>

								<div class="imageLatestThumb col-sm-12 col-md-12 col-lg-12" style="background-image: url('{{$postImg}}')">


									<div class="badge badge-primary" style="position:absolute; bottom: 10px; right: 10px; background-color: #f4932b">
									@if (is_numeric($post->price) && $post->price > 0)
										{!! \App\Helpers\Number::money($post->price) !!}
									@elseif(is_numeric($post->price) && $post->price == 0)
										{!! t('free_as_price') !!}
									@else
										{!! \App\Helpers\Number::money(' --') !!}
									@endif
									</div>
								</div>

								<div class="pl-2 pt-2">

									<div class="row">
										<div class="col-12"><h4 class="itemHeader">{{ \Illuminate\Support\Str::limit($post->title, 32) }}</h4>
										</div>
									<!--	<div class="col-2">




											@if (isset($post->savedByLoggedUser) && $post->savedByLoggedUser->count() > 0)
												<button class="btn btn-success btn-sm make-favorite" id="{{ $post->id }}">
													<i class="fa fa-heart"></i><span> </span>
												</button>
											@else
												<button class="btn btn-default btn-sm make-favorite" id="{{ $post->id }}">
													<i class="fa fa-heart"></i><span> </span>
												</button>
											@endif

										</div>-->
									</div>

									<h6 class="locationHeader">{{$post->city->name}}</h6>
									<p class="itemDescription">{{ \Illuminate\Support\Str::limit(strip_tags($post->description), 40) }}</p>

								</div>
							</a>
						</div>

					@endforeach




			<!--

		 		<div id="postsList" class="adds-wrapper noSideBar category-list">
					@foreach($latest->posts as $key => $post)
						@continue(empty($post->city))
						<?php
							// Main Picture
							if ($post->pictures->count() > 0) {
								$postImg = imgUrl($post->pictures->get(0)->filename, 'large');
							} else {
								$postImg = imgUrl(config('larapen.core.picture.default'), 'large');
							}
						?>
						<div class="item-list">
							@if ($post->featured == 1)
								@if (isset($post->latestPayment, $post->latestPayment->package) && !empty($post->latestPayment->package))
									@if ($post->latestPayment->package->ribbon != '')
										<div class="cornerRibbons {{ $post->latestPayment->package->ribbon }}">
											<a href="#"> {{ $post->latestPayment->package->short_name }}</a>
										</div>
									@endif
								@endif
							@endif
							
							<div class="row">
								<div class="col-sm-2 col-12 no-padding photobox">
									<div class="add-image">
										<span class="photo-count"><i class="fa fa-camera"></i> {{ $post->pictures->count() }} </span>
										<a href="{{ \App\Helpers\UrlGen::post($post) }}">
											<img class="lazyload img-thumbnail no-margin" src="{{ $postImg }}" alt="{{ $post->title }}">
										</a>
									</div>
								</div>
								
								<div class="{{ $colDescBox }} add-desc-box">
									<div class="items-details">
										<h5 class="add-title">
											<a href="{{ \App\Helpers\UrlGen::post($post) }}">{{ \Illuminate\Support\Str::limit($post->title, 70) }}</a>
										</h5>
										
										<span class="info-row">
											@if (config('settings.single.show_post_types'))
												@if (isset($post->postType) && !empty($post->postType))
													<span class="add-type business-ads tooltipHere"
														  data-toggle="tooltip"
														  data-placement="bottom"
														  title="{{ $post->postType->name }}"
													>
														{{ strtoupper(mb_substr($post->postType->name, 0, 1)) }}
													</span>&nbsp;
												@endif
											@endif
											@if (!config('settings.listing.hide_dates'))
												<span class="date">
													<i class="icon-clock"></i> {!! $post->created_at_formatted !!}
												</span>
											@endif
											<span class="category"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
												<i class="icon-folder-circled"></i>&nbsp;
												@if (isset($post->category->parent) && !empty($post->category->parent))
													<a href="{!! \App\Helpers\UrlGen::category($post->category->parent) !!}" class="info-link">
														{{ $post->category->parent->name }}
													</a>&nbsp;&raquo;&nbsp;
												@endif
												<a href="{!! \App\Helpers\UrlGen::category($post->category) !!}" class="info-link">
													{{ $post->category->name }}
												</a>
											</span>
											<span class="item-location"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
												<i class="icon-location-2"></i>&nbsp;
												<a href="{!! \App\Helpers\UrlGen::city($post->city) !!}" class="info-link">
													{{ $post->city->name }}
												</a>
												{{ (isset($post->distance)) ? '- ' . round($post->distance, 2) . getDistanceUnit() : '' }}
											</span>
										</span>
									</div>
									
									@if (config('plugins.reviews.installed'))
										@if (view()->exists('reviews::ratings-list'))
											@include('reviews::ratings-list')
										@endif
									@endif
								
								</div>
								
								<div class="{{ $colPriceBox }} text-right price-box" style="white-space: nowrap;">
									<h4 class="item-price">
										@if (isset($post->category, $post->category->type))
											@if (!in_array($post->category->type, ['not-salable']))
												@if (is_numeric($post->price) && $post->price > 0)
													{!! \App\Helpers\Number::money($post->price) !!}
												@elseif(is_numeric($post->price) && $post->price == 0)
													{!! t('free_as_price') !!}
												@else
													{!! \App\Helpers\Number::money(' --') !!}
												@endif
											@endif
										@else
											{{ '--' }}
										@endif
									</h4>&nbsp;
									@if (isset($post->latestPayment, $post->latestPayment->package) && !empty($post->latestPayment->package))
										@if ($post->latestPayment->package->has_badge == 1)
											<a class="btn btn-danger btn-sm make-favorite">
												<i class="fa fa-certificate"></i>
												<span> {{ $post->latestPayment->package->short_name }} </span>
											</a>&nbsp;
										@endif
									@endif
									@if (isset($post->savedByLoggedUser) && $post->savedByLoggedUser->count() > 0)
										<a class="btn btn-success btn-sm make-favorite" id="{{ $post->id }}">
											<i class="fa fa-heart"></i><span> {{ t('Saved') }} </span>
										</a>
									@else
										<a class="btn btn-default btn-sm make-favorite" id="{{ $post->id }}">
											<i class="fa fa-heart"></i><span> {{ t('Save') }} </span>
										</a>
									@endif
								</div>
							</div>
						</div>
					@endforeach
			
					<div style="clear: both"></div>

						@if (isset($latestOptions) && isset($latestOptions['show_view_more_btn']) && $latestOptions['show_view_more_btn'] == '1')


							<div class="container text-right">
								<a href="{{ \App\Helpers\UrlGen::search() }}" class="search_allc">
									{{ t('View more') }} &rangle;
								</a>
							</div>
						@endif
				</div>
-->

			</div>
		</div>
	</div>
@endif

@section('after_scripts')
    @parent
    <script>
		/* Default view (See in /js/script.js) */
		@if (isset($posts) && count($posts) > 0)
			@if (config('settings.listing.display_mode') == '.grid-view')
				gridView('.grid-view');
			@elseif (config('settings.listing.display_mode') == '.list-view')
				listView('.list-view');
			@elseif (config('settings.listing.display_mode') == '.compact-view')
				compactView('.compact-view');
			@else
				gridView('.grid-view');
			@endif
		@else
			listView('.list-view');
		@endif
		/* Save the Search page display mode */
		var listingDisplayMode = readCookie('listing_display_mode');
		if (!listingDisplayMode) {
			createCookie('listing_display_mode', '{{ config('settings.listing.display_mode', '.grid-view') }}', 7);
		}
		
		/* Favorites Translation */
		var lang = {
			labelSavePostSave: "{!! t('Save ad') !!}",
			labelSavePostRemove: "{!! t('Remove favorite') !!}",
			loginToSavePost: "{!! t('Please log in to save the Ads') !!}",
			loginToSaveSearch: "{!! t('Please log in to save your search') !!}",
			confirmationSavePost: "{!! t('Post saved in favorites successfully') !!}",
			confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully') !!}",
			confirmationSaveSearch: "{!! t('Search saved successfully') !!}",
			confirmationRemoveSaveSearch: "{!! t('Search deleted successfully') !!}"
		};
    </script>
@endsection