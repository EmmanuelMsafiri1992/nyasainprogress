<?php
/*
 * JobClass - Job Board Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com/jobclass
 * Author: BeDigit | https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - https://codecanyon.net/licenses/standard
 */

namespace App\Http\Controllers\Web\Admin;

use App\Models\Country;
use App\Http\Controllers\Web\Admin\Panel\PanelController;
use App\Http\Requests\Admin\PaymentMethodRequest as StoreRequest;
use App\Http\Requests\Admin\PaymentMethodRequest as UpdateRequest;
use App\Models\PaymentMethod;

class PaymentMethodController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel(PaymentMethod::class);
		$this->xPanel->setRoute(admin_uri('payment_methods'));
		$this->xPanel->setEntityNameStrings(trans('admin.payment method'), trans('admin.payment methods'));
		$this->xPanel->enableReorder('display_name', 1);
		$this->xPanel->allowAccess(['reorder']);
		$this->xPanel->denyAccess(['create', 'delete']);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft');
		}
		
		// Get Countries codes
		$countries = Country::query()->get(['code']);
		$countryCodes = [];
		if ($countries->count() > 0) {
			$countryCodes = $countries->keyBy('code')->keys()->toArray();
		}
		
		// Get the current Entry
		$entry = null;
		if (request()->segment(4) == 'edit') {
			$entry = $this->xPanel->model->find(request()->segment(3));
		}
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'          => 'display_name',
			'label'         => trans('admin.Name'),
			'type'          => 'model_function',
			'function_name' => 'getDisplayNameHtml',
		]);
		$this->xPanel->addColumn([
			'name'  => 'description',
			'label' => trans('admin.Description'),
		]);
		$this->xPanel->addColumn([
			'name'          => 'countries',
			'label'         => mb_ucfirst(trans('admin.countries')),
			'type'          => 'model_function',
			'function_name' => 'getCountriesHtml',
		]);
		$this->xPanel->addColumn([
			'name'          => 'active',
			'label'         => trans('admin.Active'),
			'type'          => 'model_function',
			'function_name' => 'getActiveHtml',
		]);
		
		// FIELDS
		$this->xPanel->addField([
			'name'       => 'display_name',
			'label'      => trans('admin.Name'),
			'type'       => 'text',
			'attributes' => [
				'placeholder' => trans('admin.Name'),
			],
		]);
		$this->xPanel->addField([
			'name'       => 'description',
			'label'      => trans('admin.Description'),
			'type'       => 'textarea',
			'attributes' => [
				'placeholder' => trans('admin.Description'),
			],
			'hint'       => trans('admin.HTML tags are supported'),
		]);
		
		$countriesFieldParams = [
			'name'       => 'countries',
			'label'      => trans('admin.Countries Codes'),
			'type'       => 'text',
			'attributes' => [
				'placeholder' => trans('admin.Countries Codes') . ' (' . trans('admin.separated by commas') . ')',
			],
			'hint'       => '<strong>' . trans('admin.countries_codes_list_hint') . ':</strong><br>' . implode(', ', $countryCodes),
		];
		if (!empty($entry)) {
			$countriesFieldParams['value'] = $entry->countries;
		}
		$this->xPanel->addField($countriesFieldParams);
		
		$this->xPanel->addField([
			'name'  => 'active',
			'label' => trans('admin.Active'),
			'type'  => 'checkbox_switch',
		]);
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
