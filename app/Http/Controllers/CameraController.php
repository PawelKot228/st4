<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Sleep;

class CameraController extends Controller
{
    public function index()
    {
        $videos = \Storage::disk('public')->allFiles('video');

        return view('welcome', compact('videos'));
    }

    public function startRecording(): JsonResponse
    {
        $os = config('app.os');
        $time = (int)request('time', 2);
        $timeSeconds = $time % 60;
        $timeMinutes = (int)($time / 60);

        if ($timeSeconds < 9) {
            $timeSeconds = "0$timeSeconds";
        }

        if ($timeMinutes < 9) {
            $timeMinutes = "0$timeMinutes";
        }

        $path = storage_path('app/public/video');
        $fileName = now()->getTimestamp();


        \Storage::disk('public')->makeDirectory('video');

        $command = "ffmpeg -y -f vfwcap -r 25 -i 0 -t 00:$timeMinutes:$timeSeconds $path/$fileName.mp4";
        if ($os === 'macos') {
            $command = "ffmpeg -f avfoundation -framerate 30 -i \"0\" -target pal-vcd -t 00:$timeMinutes:$timeSeconds $path/$fileName.mpg";
        } elseif ($os === 'linux') {
            $command = "ffmpeg -f v4l2 -framerate 60 -video_size 640x480 -i /dev/video0 -t 00:$timeMinutes:$timeSeconds $path/$fileName.mkv";
        }

        $output = shell_exec($command . " 2>&1");

        Sleep::sleep($time);

        return response()->json($output);
    }

    public function stopRecording()
    {
        $processId = cache('process');
        dd($processId);
        $process = \Process::run("kill -9 $processId");

        if ($process->failed()) {
            return response()->json($process->output(), 400);
        }

        return response()->json($processId);
    }
}
