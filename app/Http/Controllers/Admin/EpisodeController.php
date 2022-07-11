<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EpisodeRequest;
use App\Models\Course;
use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $episodes = Episode::latest()->paginate(20);
        return view('Admin.episodes.all', compact('episodes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.episodes.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EpisodeRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(EpisodeRequest $request)
    {
        $episode = Episode::create($request->all());
//        $this->setCourseTime($episode);

        return redirect(route('episodes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Episode $episode
     * @return \Illuminate\Http\Response
     */
    public function show(Episode $episode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Episode $episode
     * @return \Illuminate\Http\Response
     */
    public function edit(Episode $episode)
    {
        return $episode;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Episode $episode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Episode $episode)
    {
        $episode->update($request->all());
//        $this->setCourseTime($episode);

        return redirect(route('episodes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Episode $episode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Episode $episode)
    {
        $episode->delete();
        return redirect(route('episodes.index'));
    }
}
