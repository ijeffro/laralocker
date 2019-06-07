<?php

namespace Ijeffro\Laralocker\Http\Controllers;

use LearningLocker;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return LearningLocker
     */
    public function index()
    {
        return LearningLocker::clients()->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return LearningLocker
     */
    public function show($id)
    {
        return LearningLocker::client($id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return LearningLocker
     */
    public function save(Request $request)
    {
        $data = request()->all();
        return LearningLocker::client()->create($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return LearningLocker
     */
    public function update(Request $request, $id)
    {
        $data = request()->all();
        return LearningLocker::client($id)->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return LearningLocker
     */
    public function destroy($id)
    {
        return LearningLocker::client($id)->delete();
    }

}
