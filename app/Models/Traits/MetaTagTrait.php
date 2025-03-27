<?php


namespace App\Models\Traits;

trait MetaTagTrait
{
	// ===| ADMIN PANEL METHODS |===
	
	public function getPageHtml()
	{
		$entries = self::getDefaultPages();
		
		// Get Page Name
		$out = $this->page;
		if (isset($entries[$this->page])) {
			$url = admin_url('meta_tags/' . $this->id . '/edit');
			$out = '<a href="' . $url . '">' . $entries[$this->page] . '</a>';
		}
		
		return $out;
	}
	
	// ===| OTHER METHODS |===
	
	public static function getDefaultPages(): array
	{
		return [
			'home'           => 'Homepage',
			'search'         => 'Search (Default)',
			'searchCategory' => 'Search (Category)',
			'searchLocation' => 'Search (Location)',
			'searchProfile'  => 'Search (Profile)',
			'searchTag'      => 'Search (Tag)',
			'listingDetails' => 'Ad Details',
			'register'       => 'Register',
			'login'          => 'Login',
			'create'         => 'Ads Creation',
			'countries'      => 'Countries',
			'contact'        => 'Contact',
			'sitemap'        => 'Sitemap',
			'password'       => 'Password',
			'pricing'        => 'Pricing',
			'staticPage'     => 'Page (Static)',
		];
	}
}
