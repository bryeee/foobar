<?php

namespace App\Http\Controllers;

use App\Http\Resources\GitHubUserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetGitHubUser extends Controller
{
    protected $github;

    /**
     * User data cache expiration.
     */
    const CACHE_TTL = 120;

    public function __construct () 
    {
        $this->github = Http::github();
    }
    
    /**
     * TODO: extract validation to form request and business logic to service class if needed.
     * 
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // validate request
        $validated = $this->validate($request, [
            'usernames' => 'bail|required|array|min:1|max:10'
        ]);

        // iterate through the usernames collection
        $data = collect($validated['usernames'])
            ->filter()
            ->map(function ($username) {
                // if data is not cached retrieve it from github api then add it to the cache
                return Cache::remember($username, self::CACHE_TTL, function () use ($username) {
                    return $this->getBasicInfo($username) ?? '';
                });
            })
            ->filter()
            ->sortBy('name')
            ->values();

        // response json
        return GitHubUserResource::collection($data)->response();
    }

    /**
     * TODO: extract business logic to service class if needed.
     * 
     * Get basic github user information.
     *
     * @param String $username
     * @return Array|Null
     */
    protected function getBasicInfo(String $username) 
    {
        $getData =  $this->github->get("/users/{$username}");
        $getFollowers =  $this->github->get("/users/{$username}/followers");
        $getRepositories =  $this->github->get("/users/{$username}/repos");

        if ($getData->failed()) {
            Log::info("Failed to get github user info.", [
                'username' => $username,
                'response' => $getData->json()
            ]);

            return null;
        }

        $basicInfo = Arr::only($getData->json(), ['name', 'login', 'company']);
        $followers = collect($getFollowers->json())->count();
        $repositories = collect($getRepositories->json())->count();

        data_set($basicInfo, 'followers', $followers);
        data_set($basicInfo, 'repositories', $repositories);

        return $basicInfo;
    }
}
