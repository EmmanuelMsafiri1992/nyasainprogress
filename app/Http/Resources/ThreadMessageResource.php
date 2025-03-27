<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThreadMessageResource extends JsonResource
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
		
		$entity['created_at_formatted'] = $this->created_at_formatted ?? null;
		$entity['p_recipient'] = $this->p_recipient ?? null;
		
		$embed = explode(',', request()->input('embed'));
		
		if (in_array('thread', $embed)) {
			$entity['thread'] = new ThreadResource($this->whenLoaded('thread'));
		}
		if (in_array('user', $embed)) {
			$entity['user'] = new UserResource($this->whenLoaded('user'));
		}
		
		return $entity;
	}
}
