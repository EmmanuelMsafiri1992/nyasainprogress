<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
		
		$defaultLogo = config('larapen.media.picture');
		$defaultLogoUrl = imgUrl($defaultLogo);
		$entity['logo_url'] = [
			'full'   => $this->logo_url ?? $defaultLogoUrl,
			'small'  => $this->logo_url_small ?? $defaultLogoUrl,
			'medium' => $this->logo_url_medium ?? $defaultLogoUrl,
			'large'  => $this->logo_url_large ?? $defaultLogoUrl,
		];
		$entity['posts_count'] = $this->posts_count ?? 0;
		$entity['country_flag_url'] = $this->country_flag_url ?? null;
		
		$embed = explode(',', request()->input('embed'));
		
		if (in_array('user', $embed)) {
			$entity['user'] = new UserResource($this->whenLoaded('user'));
		}
		if (in_array('city', $embed)) {
			$entity['city'] = new CityResource($this->whenLoaded('city'));
		}
		
		return $entity;
	}
}
