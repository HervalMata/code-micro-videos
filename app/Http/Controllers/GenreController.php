<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class GenreController extends Controller
{
    private $rules = [
        "name" => 'required|max:255',
        "is_active" => 'boolean'
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Genre[]|Collection
     */
    public function index(Request $request)
    {
        if ($request->has('only_trashed')) {
            return Genre::onlyTrashed()->get();
        }
        return Genre::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        return Genre::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param Genre $genre
     * @return Genre
     */
    public function show(Genre $genre)
    {
        return $genre;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Genre $genre
     * @return Genre
     * @throws ValidationException
     */
    public function update(Request $request, Genre $genre)
    {
        $this->validate($request, $this->rules);
        $genre->update($request->all());
        return $genre;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Genre $genre
     * @return Response
     * @throws Exception
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return \response()->noContent();
    }
}
