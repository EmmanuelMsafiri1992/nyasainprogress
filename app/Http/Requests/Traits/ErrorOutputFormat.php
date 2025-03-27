<?php


namespace App\Http\Requests\Traits;

trait ErrorOutputFormat
{
	private string $defaultMessage = 'An error occurred while validating the data.';
	private string $messageKey = 'validation_error_occurred';
	
	/**
	 * @param $errors
	 * @param bool $getDefaultMessage
	 * @return string
	 */
	protected function webFormatError($errors, bool $getDefaultMessage = false): string
	{
		$message = t($this->messageKey);
		
		if ($getDefaultMessage) {
			return is_string($message) ? $message : $this->defaultMessage;
		}
		
		// Get errors (as string)
		if (is_array($errors) && count($errors) > 0) {
			$errorsTxt = '<h5><strong>' . t('oops_an_error_has_occurred') . '</strong></h5>';
			$errorsTxt .= '<ul class="list list-check">';
			foreach ($errors as $value) {
				if (is_array($value)) {
					foreach ($value as $v) {
						$errorsTxt .= '<li>' . $v . '</li>';
					}
				} else {
					$errorsTxt .= '<li>' . $value . '</li>';
				}
			}
			$errorsTxt .= '</ul>';
		} else {
			$errorsTxt = $message;
		}
		
		return is_string($errorsTxt) ? $errorsTxt : $this->defaultMessage;
	}
	
	/**
	 * @param $errors
	 * @param bool $getDefaultMessage
	 * @return string
	 */
	protected function apiFormatError($errors, bool $getDefaultMessage = false): string
	{
		$message = t($this->messageKey);
		
		if ($getDefaultMessage) {
			return is_string($message) ? $message : $this->defaultMessage;
		}
		
		$bullet = !doesRequestIsFromWebApp() ? '➤' : '';
		
		// Get errors (as string)
		if (is_array($errors) && count($errors) > 0) {
			$errorsTxt = '';
			foreach ($errors as $value) {
				if (is_array($value)) {
					foreach ($value as $v) {
						$errorsTxt .= empty($errorsTxt) ? $bullet . ' ' . $v : "\n" . $bullet . ' ' . $v;
					}
				} else {
					$errorsTxt .= empty($errorsTxt) ? $bullet . ' ' . $value : "\n" . $bullet . ' ' . $value;
				}
			}
		} else {
			$errorsTxt = $message;
		}
		
		return is_string($errorsTxt) ? $errorsTxt : $this->defaultMessage;
	}
	
	/**
	 * @param $errors
	 * @param bool $getDefaultMessage
	 * @return string
	 */
	protected function fileinputFormatError($errors, bool $getDefaultMessage = false): string
	{
		$message = t($this->messageKey);
		
		if ($getDefaultMessage) {
			return is_string($message) ? $message : $this->defaultMessage;
		}
		
		// Get errors (as string)
		if (is_array($errors) && count($errors) > 0) {
			$errorsTxt = '';
			foreach ($errors as $value) {
				if (is_array($value)) {
					foreach ($value as $v) {
						$errorsTxt .= empty($errorsTxt) ? '- ' . $v : '<br>- ' . $v;
					}
				} else {
					$errorsTxt .= empty($errorsTxt) ? '- ' . $value : '<br>- ' . $value;
				}
			}
		} else {
			$errorsTxt = $message;
		}
		
		return is_string($errorsTxt) ? $errorsTxt : $this->defaultMessage;
	}
	
	/**
	 * @param $errors
	 * @return string
	 */
	protected function simpleFormatError($errors): string
	{
		return $this->apiFormatError($errors, true);
	}
}
