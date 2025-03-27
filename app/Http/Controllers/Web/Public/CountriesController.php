<?php


namespace App\Http\Controllers\Web\Public;

use Larapen\LaravelMetaTags\Facades\MetaTag;

class CountriesController extends FrontController
{
	/**
	 * @return \Illuminate\Contracts\View\View
	 */
    public function index()
    {
        $data = [];
        
        // Meta Tags
		[$title, $description, $keywords] = getMetaTag('countries');
		MetaTag::set('title', $title);
		MetaTag::set('description', strip_tags($description));
		MetaTag::set('keywords', $keywords);

        return appView('countries', $data);
    }
}
