<?php

namespace App\Http\Controllers;

use App\Link;
use App\Transformers\LinkTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LinksController extends Controller
{
    public function index()
    {
        return $this->collection(Link::all(), new LinkTransformer());
    }

    public function show($id)
    {
        return $this->item(Link::findOrFail($id), new LinkTransformer());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'url' => 'required|max:255',
            'category_id' => 'required|exists:categories,id'
        ], [
            'title.required' => 'The :attribute field is required.',
            'title.max' => 'The :attribute may not be greater than :max characters.',
            
            'url.required' => 'The :attribute field is required.',
            'url.max' => 'The :attribute may not be greater than :max characters.',
        ]);

        $link = Link::create($request->all());

        return response()->json(
            $this->item($link, new LinkTransformer()),
            Response::HTTP_CREATED,
            ['Location' => route('links.show', ['id' => $link->id])]
        );
    }

    public function update(Request $request, $id)
    {
        $link = Link::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|max:255',
            'url' => 'required|max:255',
            'category_id' => 'exists:categories,id'
        ], [
            'title.required' => 'The :attribute field is required.',
            'title.max' => 'The :attribute may not be greater than :max characters.',
            
            'url.required' => 'The :attribute field is required.',
            'url.max' => 'The :attribute may not be greater than :max characters.',
        ]);

        $link->fill($request->all());
        $link->save();

        return $this->item($link, new LinkTransformer());
    }

    public function destroy($id)
    {
        $link = Link::findOrFail($id);

        $link->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
