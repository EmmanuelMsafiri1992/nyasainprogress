<?php


namespace App\Http\Requests\Front;

use App\Http\Requests\Request;

class ResumeRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Validation Rules
        $rules = [];
        
        // Check 'resume' is required
        if ($this->hasFile('resume.filename')) {
            $rules['resume.filename'] = [
            	'required',
				'mimes:' . getUploadFileTypes('file'),
				'min:' . (int)config('settings.upload.min_file_size', 0),
				'max:' . (int)config('settings.upload.max_file_size', 1000),
			];
        }
        
        return $rules;
    }
}
