<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class AudioController extends Controller
{
    public function index()
    {

        $audios = \Storage::disk('public')->allFiles('audio');

        return view('welcome', compact('audios'));
    }


    public function save(): JsonResponse
    {
        $post = request()->validate([
            'audio' => ['required', 'file'],
        ]);

        /** @var UploadedFile $audio */
        $audio = $post['audio'];


        $audio->storeAs('public/audio', \Str::random() . '.' . $audio->extension());

        return response()->json(status: 204);
    }
}
