<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GitHubUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this['name'],
            'login' => $this['login'],
            'company' => $this['company'],
            'followers' => $this['followers'] ?? null,
            'repositories' => $this['repositories'] ?? null,
            'average_followers' => $this->averageFollowers() 
        ];
    }

    /**
     * Get average followers per public repository
     */
    public function averageFollowers()
    {
        try {
            return ($this['followers'] / $this['repositories']);
        } catch (\Throwable $th) {
            return null;
        }
    }
}
