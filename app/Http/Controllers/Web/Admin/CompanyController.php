<?php


namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Admin\Panel\PanelController;
use App\Http\Requests\Admin\CompanyRequest as StoreRequest;
use App\Http\Requests\Admin\CompanyRequest as UpdateRequest;
use App\Models\Company;

class CompanyController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel(Company::class);
		$this->xPanel->setRoute(admin_uri('companies'));
		$this->xPanel->setEntityNameStrings(trans('admin.company'), trans('admin.companies'));
		$this->xPanel->denyAccess(['create']);
		if (!request()->input('order')) {
			$this->xPanel->orderByDesc('id');
		}
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_deletion_button', 'bulkDeletionButton', 'end');
		
		// Filters
		// -----------------------
		$this->xPanel->disableSearchBar();
		// -----------------------
		$this->xPanel->addFilter(
			[
				'name'  => 'name',
				'type'  => 'text',
				'label' => mb_ucfirst(trans('admin.Name')),
			],
			false,
			function ($value) {
				$this->xPanel->addClause('where', 'name', 'LIKE', "%$value%");
				$this->xPanel->addClause('orWhere', 'description', 'LIKE', "%$value%");
			}
		);
		// -----------------------
		$this->xPanel->addFilter(
			[
				'name'  => 'country',
				'type'  => 'select2',
				'label' => mb_ucfirst(trans('admin.Country')),
			],
			getCountries(),
			fn ($value) => $this->xPanel->addClause('where', 'country_code', '=', $value)
		);
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'      => 'id',
			'label'     => '',
			'type'      => 'checkbox',
			'orderable' => false,
		]);
		$this->xPanel->addColumn([
			'name'          => 'logo', // Put unused field column
			'label'         => trans('admin.Logo'),
			'type'          => 'model_function',
			'function_name' => 'getLogoHtml',
		]);
		$this->xPanel->addColumn([
			'name'          => 'name',
			'label'         => trans('admin.Name'),
			'type'          => 'model_function',
			'function_name' => 'getNameHtml',
		]);
		$this->xPanel->addColumn([
			'name'  => 'description',
			'label' => trans('admin.Description'),
		]);
		$this->xPanel->addColumn([
			'name'          => 'country_code',
			'label'         => trans('admin.Country'),
			'type'          => 'model_function',
			'function_name' => 'getCountryHtml',
		]);
		
		// FIELDS
		$this->xPanel->addField([
			'name'       => 'name',
			'label'      => trans('admin.company_name'),
			'type'       => 'text',
			'attributes' => [
				'placeholder' => trans('admin.company_name'),
			],
		]);
		$this->xPanel->addField([
			'name'   => 'logo',
			'label'  => trans('admin.Logo') . ' (Supported file extensions: jpg, jpeg, png, gif)',
			'type'   => 'image',
			'upload' => true,
			'disk'   => 'public',
		]);
		$this->xPanel->addField([
			'name'       => 'description',
			'label'      => trans('admin.Company Description'),
			'type'       => 'textarea',
			'attributes' => [
				'placeholder' => trans('admin.Company Description'),
				'rows'        => 10,
			],
		]);
		$this->xPanel->addField([
			'name'       => "address",
			'label'      => trans('admin.Address'),
			'type'       => "text",
			'attributes' => [
				'placeholder' => trans('admin.Address'),
			],
		]);
		$this->xPanel->addField([
			'name'              => 'phone',
			'label'             => trans('admin.Phone'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.Phone'),
			],
			'wrapperAttributes' => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'fax',
			'label'             => trans('admin.Fax'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.Fax'),
			],
			'wrapperAttributes' => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'email',
			'label'             => trans('admin.User Email'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.User Email'),
			],
			'wrapperAttributes' => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'website',
			'label'             => trans('admin.Company Website'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.Company Website'),
			],
			'wrapperAttributes' => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => "facebook",
			'label'             => 'Facebook',
			'type'              => "text",
			'attributes'        => [
				'placeholder' => 'Facebook',
			],
			'wrapperAttributes' => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => "twitter",
			'label'             => 'Twitter',
			'type'              => "text",
			'attributes'        => [
				'placeholder' => 'Twitter',
			],
			'wrapperAttributes' => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => "linkedin",
			'label'             => 'Linkedin',
			'type'              => "text",
			'attributes'        => [
				'placeholder' => 'Linkedin',
			],
			'wrapperAttributes' => [
				'class' => 'col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => "pinterest",
			'label'             => 'Pinterest',
			'type'              => "text",
			'attributes'        => [
				'placeholder' => 'Pinterest',
			],
			'wrapperAttributes' => [
				'class' => 'col-md-6',
			],
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
