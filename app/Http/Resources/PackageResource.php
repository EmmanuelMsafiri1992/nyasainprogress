<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray(Request $request): array
	{
		$entity = [
			'id' => $this->id,
		];
		
		$columns = $this->getFillable();
		foreach ($columns as $column) {
			$entity[$column] = $this->{$column};
		}
		
		$entity['period_start'] = $this->period_start ?? null;
		$entity['period_end'] = $this->period_end ?? null;
		$entity['description_array'] = $this->description_array ?? [];
		$entity['description_string'] = $this->description_string ?? null;
		$entity['price_formatted'] = $this->price_formatted ?? null;
		
		$embed = explode(',', request()->input('embed'));
		
		if (in_array('currency', $embed)) {
			$entity['currency'] = new CurrencyResource($this->whenLoaded('currency'));
		}
		
		return $entity;
	}
}
