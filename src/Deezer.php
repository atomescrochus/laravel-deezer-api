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
    private $advanced_search;
    private $search_type;
    private $order;
    private $possible_order;
    private $multipleTrackPossible;

    public function __construct()
    {
        $this->request_url = "https://api.deezer.com";
        $this->cache_time = 60; // in minutes
        $this->search_query = null;
        $this->endpoint = null;
        $this->search_type = null;
        $this->order = 'ranking';
        $this->strict_mode = "off";
        $this->advanced_search = collect([]);
        $this->multipleTrackPossible = true;

        $this->possible_search_types = ['album', 'artist', 'history', 'playlist', 'radio', 'track', 'user'];
        $this->possible_order = ['ranking', 'track_asc', 'track_desc', 'artist_asc', 'artist_desc', 'album_asc', 'album_desc', 'rating_asc', 'rating_desc', 'duration_asc', 'duration_desc'];
    }

    public function artist(string $terms)
    {
        $this->advanced_search['artist'] = $terms;

        return $this;
    }

    public function album(string $terms)
    {
        $this->advanced_search['album'] = $terms;

        return $this;
    }

    public function track(string $terms)
    {
        $this->advanced_search['track'] = $terms;

        return $this;
    }

    public function label(string $terms)
    {
        $this->advanced_search['label'] = $terms;

        return $this;
    }

    public function minimumDuration($terms)
    {
        $this->advanced_search['dur_min'] = $terms;

        return $this;
    }

    public function maximumDuration(int $terms)
    {
        $this->advanced_search['dur_max'] = $terms;

        return $this;
    }

    public function minimumBPM(int $terms)
    {
        $this->advanced_search['dur_max'] = $terms;

        return $this;
    }

    public function maximumBPM(int $terms)
    {
        $this->advanced_search['dur_max'] = $terms;

        return $this;
    }

    public function basicSearch($searchTerms)
    {
        $this->endpoint = "/search/";
        $this->search_query = $searchTerms;

        return $this->search(false);
    }

    public function getTrackById(int $id)
    {
        $this->endpoint = "/track/".$id;
        $this->strict_mode = "off";
        $this->order = null;
        $this->multipleTrackPossible = false;

        return $this->search(false);
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

    public function type($type)
    {
        $this->checkSearchType($type);

        $this->search_type = $type;

        return $this;
    }

    public function order($order)
    {
        $this->checkOrder($order);

        $this->order = $order;

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

    private function checkOrder($order)
    {
        if (!in_array($order, $this->possible_order)) {
            throw UsageErrors::order();
        }
    }

    public function search($advanced = true)
    {
        if ($advanced) {
            $this->prepareAdvancedSearch();
        }
        
        $response = \Httpful\Request::get($this->getRequestUrl())->send();

        $results =  Cache::remember("{$this->getRequestUrl()}", $this->cache_time, function () use ($response) {
            return $this->formatApiResults($response);
        });

        return $results;
    }

    private function getRequestUrl()
    {
        $query = !is_null($this->search_query) ? "?q=".$this->search_query : null;
        $strict = $this->strict_mode == "on" ? "&strict={$this->strict_mode}" : null;
        $order = !is_null($this->order) ? "&order={$this->order}" : null;

        return  $this->request_url.
                $this->endpoint.
                $this->search_type.
                $query.
                $strict.
                $order;
    }

    private function prepareAdvancedSearch()
    {
        if ($this->advanced_search->count() == 0) {
            throw UsageErrors::advancedSearchMissingTerms();
        } else {
            // http://api.deezer.com/search?q=artist:"aloe blacc" track:"i need a dollar"
            // https://api.deezer.com?q=artist:"Les goules" track:"crabe"&strict=off
            $this->endpoint = "/search/";

            $query = $this->advanced_search->map(function ($query, $key) {
                return $key.':"'.$query.'"';
            })->implode(" ");

            $this->search_query = rawurlencode($query);
            // dd($this->search_query);
        }
    }

    private function formatApiResults($result)
    {
        
        if (isset($result->body->error)) {
            return $result->body;
        }

        $raw = $result->raw_body;
        $response = $result->body ? $result->body : null;

        $results = $this->multipleTrackPossible ? $response->data : $response;
        $count = $this->multipleTrackPossible ? $result->body->total : 1;

        return (object) [
            'results' => collect($results),
            'count' => $count,
            'raw' => json_decode($raw),
            'query' => urldecode($this->getRequestUrl()),
        ];
    }
}
