<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
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
			'id' => $this->id,
		];
		
		$columns = $this->getFillable();
		foreach ($columns as $column) {
			$entity[$column] = $this->{$column};
		}
		
		return $entity;
	}
}
