<?php

namespace Atomescrochus\Deezer;

use Atomescrochus\Deezer\Exceptions\Unsupported;
use Atomescrochus\Deezer\Exceptions\UsageErrors;
use Illuminate\Support\Facades\Cache;

class Deezer
{
    private $cache_time;
    private $endpoint;
    private $possible_search_types;
    private $search_query;
    private $strict_mode;
    private $request_url;

    public function __construct()
    {
        $this->request_url = "https://api.deezer.com";
        $this->cache_time = 60; // in minutes
        $this->search_query = null;
        $this->endpoint = null;
        $this->strict_mode = "off";

        $this->possible_search_types = ['album', 'artist', 'history', 'playlist', 'radio', 'track', 'user'];
    }

    public function basicSearch($searchTerms, $type = 'artist')
    {
        $this->checkSearchType($type);

        $this->endpoint = "/search/{$type}";
        $this->search_query = $searchTerms;

        $results = Cache::remember("basic-search-{$type}-strict-{$this->strict_mode}-{$searchTerms}", $this->cache_time, function () {
            return $this->executeSearch();
        });

        return $results;
    }

    public function strictMode()
    {
        $this->strict_mode = "on";

        return $this;
    }

    public function cache(int $minutes)
    {
        $this->cache_time = $minutes;

        return $this;
    }

    private function checkSearchType($type)
    {
        if (!in_array($type, $this->possible_search_types)) {
            throw UsageErrors::searchType();
        }

        if (in_array($type, ['history'])) {
            throw Unsupported::needsOAuth();
        }
    }

    private function executeSearch()
    {
        $response = \Httpful\Request::get(
            $this->request_url.
            $this->endpoint.
            "?q=".
            $this->search_query.
            "&strict={$this->strict_mode}"
        )->send();

        return $this->formatApiResults($response);
    }

    private function formatApiResults($result)
    {
        if (isset($result->body->error)) {
            return $result->body;
        }

        $raw = $result->raw_body;
        $response = $result->body ? $result->body : null;

        return (object) [
            'results' => collect($response->data),
            'raw' => json_decode($raw),
        ];
    }
}
