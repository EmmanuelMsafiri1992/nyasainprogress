<?php


namespace App\Http\Controllers\Web\Public\Account;

use Larapen\LaravelMetaTags\Facades\MetaTag;

class TransactionsController extends AccountBaseController
{
	/**
	 * Promotions Transactions List
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function index()
	{
		$type = request()->segment(3);
		$isPromoting = ($type == 'promotion');
		$isSubscripting = ($type == 'subscription');
		
		// Call API endpoint
		$endpoint = '/payments/' . $type;
		$otherEmbed = $isSubscripting ? ',posts' : '';
		$queryParams = [
			'embed' => 'payable,paymentMethod,package,currency' . $otherEmbed,
			'sort'  => 'created_at',
		];
		$queryParams = array_merge(request()->all(), $queryParams);
		$data = makeApiRequest('get', $endpoint, $queryParams);
		
		$apiMessage = $this->handleHttpError($data);
		$apiResult = data_get($data, 'result');
		
		$appName = config('settings.app.name', 'Site Name');
		$title = ($isSubscripting) ? t('my_subs_transactions') : t('my_promo_transactions');
		$title = $title . ' - ' . $appName;
		$description = t('my_transactions_on', ['appName' => config('settings.app.name')]);
		
		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', $description);
		
		return appView(
			'account.transactions',
			compact('type', 'isPromoting', 'isSubscripting', 'apiResult', 'apiMessage')
		);
	}
}
