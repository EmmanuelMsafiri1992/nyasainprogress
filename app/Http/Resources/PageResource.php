<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray(Request $request): array
	{
		if (!isset($this->id)) return [];
		
		$entity = [
			'id' => $this->id,
		];
		
		$columns = $this->getFillable();
		foreach ($columns as $column) {
			$entity[$column] = $this->{$column};
		}
		
		$entity['picture_url'] = $this->picture_url ?? null;
		
		$embed = explode(',', request()->input('embed'));
		
		if (in_array('parent', $embed)) {
			$entity['parent'] = new static($this->whenLoaded('parent'));
		}
		
		return $entity;
	}
}
