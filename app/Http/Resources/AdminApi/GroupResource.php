<?php

namespace App\Http\Resources\AdminApi;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo_url' => $this->logo_url,
            'is_open' => $this->isOpen(),
            'trial_ends_at' => $this->trial_ends_at,
            'users_count' => $this->users()->count(),
            'quizzes_count' => $this->quizzes()->count(),
            'questions_count' => $this->questions()->count(),
            'created_at' =>$this->created_at,
            'updated_at' =>$this->updated_at,
        ];
    }
}
