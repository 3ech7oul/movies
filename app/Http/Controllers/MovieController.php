<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class MovieController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Movie::query()
            ->withAvg('ratings', 'rating')
            ->withCount('ratings');

        $perPage = $request->get('per_page', 5);
        $movies = $query->paginate($perPage);

        return MovieResource::collection($movies);
    }

    public function get(Movie $movie): MovieResource
    {
        return new MovieResource($movie);
    }
}
