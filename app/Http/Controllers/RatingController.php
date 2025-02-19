<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Movie;
use App\Models\Rating;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Exception\RatingException;


class RatingController
{
    public function index(Request $request, Movie $movie)
    {
        $perPage = $request->get('per_page', 5);
        $ratings = $movie->ratings()->paginate($perPage);

        return RatingResource::collection($ratings);
    }

    public function create(Movie $movie, StoreRatingRequest $request)
    {
        $exists = Rating::where('user_id', $request->user()->id)
            ->where('movie_id', $movie->id)
            ->exists();

        if ($exists) {
            throw new RatingException(['PUT'], 'Rating already exists', null, Response::HTTP_CONFLICT);
        }

        $rating = new Rating();
        $rating->movie_id = $movie->id;
        $rating->user_id = $request->user()->id;
        $rating->fill($request->all());
        $rating->save();

        return response()->json([
            'message' => 'Rating created'
        ], Response::HTTP_CREATED);
    }
}
