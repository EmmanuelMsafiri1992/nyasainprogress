<?php


namespace App\Http\Requests\Admin;

class PluginRequest extends Request
{
	protected bool $isValidPurchaseCode = false;
	protected ?string $invalidPurchaseCodeMessage = null;
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [];
		
		$name = $this->segment(3);
		$plugin = load_plugin($name);
		if (empty($plugin)) {
			return $rules;
		}
		
		if ($this->has('purchase_code')) {
			$purchaseCodeData = plugin_purchase_code_data($plugin, $this->input('purchase_code'));
			$this->isValidPurchaseCode = (
				is_bool(data_get($purchaseCodeData, 'valid'))
				&& data_get($purchaseCodeData, 'valid')
			);
			$defaultMessage = 'Impossible to retrieve error message.';
			$this->invalidPurchaseCodeMessage = data_get($purchaseCodeData, 'message', $defaultMessage);
			
			if (!$this->isValidPurchaseCode) {
				$rules['purchase_code_valid'] = 'required'; // With customized message bellow
			}
		}
		
		return $rules;
	}
	
	/**
	 * Get custom messages for validator errors.
	 *
	 * @return array
	 */
	public function messages()
	{
		$messages = [];
		
		$name = $this->segment(3);
		$plugin = load_plugin($name);
		if (empty($plugin)) {
			return $messages;
		}
		
		if ($this->has('purchase_code')) {
			if (!$this->isValidPurchaseCode) {
				$apiMsg = ' ERROR: <strong>' . $this->invalidPurchaseCodeMessage . '</strong>';
				$msg = trans('admin.plugin_invalid_code', ['plugin_name' => $plugin->display_name]);
				$messages = ['purchase_code_valid.required' => $msg . $apiMsg];
			}
		}
		
		return $messages;
	}
}
