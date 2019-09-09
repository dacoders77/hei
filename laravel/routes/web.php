<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 *
 * Generate sitemap.xml
 *
 */
Route::get('sitemap.xml',function(){
	$xml = new \SimpleXMLElement("<?xml version=\"1.0\"?><urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"></urlset>");

	// Add home path by default
	$xml->addChild('url')->addChild('loc', URL::to('/'));

	// Loop config/routes.php and map Routes
	if(config('routes')) foreach (config('routes') as $route) {

		// If contains sitemap = true
		if(isset($route['sitemap'])) {
			$xml->addChild('url')->addChild('loc', URL::to($route['url']));
		}

	}

	return response($xml->asXML(), 200)->header('Content-Type', 'text/xml');
});


/**
 *
 * Setup Campaign Routes
 * @see App\Http\Controllers\Campaigns
 *
 */
Route::group(['namespace' => 'Campaigns'], function() {

	if( !Schema::hasTable('campaigns') ) return;

	// Loop Campaigns Config
	if(config('campaigns')) foreach (config('campaigns') as $campaign) {

		if(!\Campaign::find($campaign['id'])) continue;

		// Setup Object
		$campaign = (object) $campaign;

		$domain = parse_url(\Campaign::find($campaign->id)->url, PHP_URL_HOST);

		Route::group(['domain' => $domain, 'middleware' => 'published'], function() use ($campaign) {


            /**
             * @TODO DELETE!
             */
            Route::get('/pay', function () {
                return view('campaigns.pages.stripe');
            })->name('pay');

            Route::post ( '/paygate', function (Request $request) {
                \Stripe\Stripe::setApiKey ( 'test_SecretKey' );
                try {
                    \Stripe\Charge::create ( array (
                        "amount" => 300 * 100,
                        "currency" => "usd",
                        "source" => $request->input ( 'stripeToken' ), // obtained with Stripe.js
                        "description" => "Test payment."
                    ) );
                    Session::flash ( 'success-message', 'Payment done successfully !' );
                    return Redirect::back ();
                } catch ( \Exception $e ) {
                    Session::flash ( 'fail-message', "Error! Please Try again." );
                    return Redirect::back ();
                }
            } );







			foreach ($campaign->routes as $route) {
				// Setup Defaults
				$route = (object) array_merge([
					'path' => null,
					'method' => 'get',
					'name' => null,
					'controller' => null,
					'middleware' => null,
					'args' => []
				],$route);

				$r = Route::{ $route->method }( $route->path, $route->controller );

				$r->defaults( 'campaign_id', $campaign->id );

				foreach ($route->args as $key => $value) {
					$r->defaults($key,$value);
				}

				$r->name( $route->name );
				$r->middleware( $route->middleware );
			}
		});

	}

});


