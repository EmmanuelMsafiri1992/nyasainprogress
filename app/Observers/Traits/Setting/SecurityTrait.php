<?php


namespace App\Observers\Traits\Setting;

use App\Helpers\DBTool;
use Illuminate\Support\Facades\Schema;

trait SecurityTrait
{
	/**
	 * Updating
	 *
	 * @param $setting
	 * @param $original
	 * @return false|void
	 */
	public function securityUpdating($setting, $original)
	{
		// Honeypot
		$allFormsFields = $this->getAllFormsFields();
		
		// honeypot_name_field_name
		$nameFieldName = $setting->value['honeypot_name_field_name'] ?? null;
		if (!empty($nameFieldName)) {
			if (in_array($nameFieldName, $allFormsFields)) {
				$message = trans('admin.honeypot_reserved_field_name_error_message', [
					'attribute'      => trans('admin.honeypot_name_field_name_label'),
					'attributeValue' => $nameFieldName,
				]);
				notification($message, 'error');
				
				return false;
			}
		} else {
			$message = trans('admin.honeypot_field_name_required_message', [
				'attribute' => trans('admin.honeypot_name_field_name_label'),
			]);
			notification($message, 'error');
			
			return false;
		}
		
		// honeypot_valid_from_field_name
		$validFromFieldName = $setting->value['honeypot_valid_from_field_name'] ?? null;
		if (!empty($validFromFieldName)) {
			if (in_array($validFromFieldName, $allFormsFields)) {
				$message = trans('admin.honeypot_reserved_field_name_error_message', [
					'attribute'      => trans('admin.honeypot_valid_from_field_name_label'),
					'attributeValue' => $validFromFieldName,
				]);
				notification($message, 'error');
				
				return false;
			}
		} else {
			$message = trans('admin.honeypot_field_name_required_message', [
				'attribute' => trans('admin.honeypot_valid_from_field_name_label'),
			]);
			notification($message, 'error');
			
			return false;
		}
		
		// password length
		$passwordMinLength = $setting->value['password_min_length'] ?? 6;
		$passwordMaxLength = $setting->value['password_max_length'] ?? 60;
		if ($passwordMinLength > $passwordMaxLength) {
			$message = trans('admin.min_max_error_message', ['attribute' => trans('admin.password_length')]);
			notification($message, 'error');
			
			return false;
		}
		
		// Check if the PHP intl extension is installed
		// to use DNS or Spoof in the email validator.
		$emailValidatorDns = $setting->value['email_validator_dns'] ?? false;
		$emailValidatorSpoof = $setting->value['email_validator_spoof'] ?? false;
		if (($emailValidatorDns || $emailValidatorSpoof) && !extension_loaded('intl')) {
			$message = trans('admin.intl_extension_missing_error_message_for_email_validation');
			notification($message, 'error');
			
			return false;
		}
	}
	
	private function getAllFormsFields(): array
	{
		$fields = [];
		
		try {
			$dbColumns = $this->getAllDbColumns();
			$contactFields = ['first_name', 'last_name', 'company_name', 'email', 'message'];
			$reportFields = ['report_type_id', 'email', 'message', 'post_id', 'abuseForm'];
			$sendByEmailFields = ['recipient_email', 'post_id', 'sendByEmailForm'];
			$otherFields = ['_method', '_token', 'captcha', 'g-recaptcha-response'];
			
			$fields = array_merge($fields, $dbColumns);
			$fields = array_merge($fields, $contactFields);
			$fields = array_merge($fields, $reportFields);
			$fields = array_merge($fields, $sendByEmailFields);
			$fields = array_merge($fields, $otherFields);
			
			$fields = collect($fields)->unique()->toArray();
		} catch (\Throwable $e) {
		}
		
		return $fields;
	}
	
	private function getAllDbColumns(): array
	{
		$columns = [];
		
		$tables = DBTool::getDatabaseTables(withPrefix: false);
		foreach ($tables as $table) {
			$tableColumns = Schema::getColumnListing($table);
			if (is_array($tableColumns)) {
				$columns = array_merge($columns, $tableColumns);
			}
		}
		
		return collect($columns)->unique()->toArray();
	}
}
