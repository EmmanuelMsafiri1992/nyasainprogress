<?php


namespace App\Http\Resources;

use App\Enums\Continent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray(Request $request): array
	{
		$entity = [
			'code' => $this->code,
		];
		
		$columns = $this->getFillable();
		foreach ($columns as $column) {
			$entity[$column] = $this->{$column};
		}
		
		$entity['icode'] = $this->icode ?? null;
		$entity['flag_url'] = $this->flag_url ?? null;
		$entity['flag16_url'] = $this->flag16_url ?? null;
		$entity['flag24_url'] = $this->flag24_url ?? null;
		$entity['flag32_url'] = $this->flag32_url ?? null;
		$entity['flag48_url'] = $this->flag48_url ?? null;
		$entity['flag64_url'] = $this->flag64_url ?? null;
		$entity['background_image_url'] = $this->background_image_url ?? null;
		
		$embed = explode(',', request()->input('embed'));
		
		if (in_array('currency', $embed)) {
			$entity['currency'] = new CurrencyResource($this->whenLoaded('currency'));
		}
		if (in_array('continent', $embed)) {
			$entity['continent'] = Continent::find($this->continent_code);
		}
		
		return $entity;
	}
}
