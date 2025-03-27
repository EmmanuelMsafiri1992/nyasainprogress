<?php


namespace App\Http\Resources;

use App\Models\ThreadMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThreadResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function toArray(Request $request): array
	{
		$user = null;
		if (auth('sanctum')->check()) {
			$user = auth('sanctum')->user();
		}
		
		$entity = [
			'id' => $this->id,
		];
		
		$columns = $this->getFillable();
		foreach ($columns as $column) {
			$entity[$column] = $this->{$column};
		}
		
		$entity['updated_at'] = $this->updated_at ?? null;
		$entity['latest_message'] = $this->latest_message ?? null;
		$entity['p_is_unread'] = $this->p_is_unread ?? null;
		$entity['p_creator'] = $this->p_creator ?? [];
		$entity['p_is_important'] = $this->p_is_important ?? null;
		
		$embed = explode(',', request()->input('embed'));
		
		if (in_array('user', $embed)) {
			if (!empty($user)) {
				$entity['user'] = new UserResource($user);
			}
		}
		
		if (in_array('post', $embed)) {
			$entity['post'] = new PostResource($this->whenLoaded('post'));
		}
		
		if (in_array('messages', $embed) && str_contains(currentRouteAction(), 'Api\ThreadController@show')) {
			// Get the Thread's Messages
			$messages = collect();
			if (!empty($user) && isset($user->id)) {
				$messages = ThreadMessage::query()
					->notDeletedByUser($user->id)
					->where('thread_id', $this->id)
					->with('user')
					->orderByDesc('id');
			}
			$messages = $messages->paginate(request()->input('perPage', 10));
			
			$messagesCollection = new EntityCollection('ThreadMessageController', $messages);
			$message = ($messages->count() <= 0) ? t('no_messages_found') : null;
			$entity['messages'] = apiResponse()->withCollection($messagesCollection, $message)->getData(true);
		}
		
		if (in_array('participants', $embed)) {
			$entity['participants'] = UserResource::collection($this->whenLoaded('users'));
		}
		
		return $entity;
	}
}
