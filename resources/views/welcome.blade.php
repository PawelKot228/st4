<x-app-layout>
    <div>
        <div class="flex flex-col justify-center py-4 px-28 max-w-2xl gap-8 mx-auto">
            <div>
                <p id="notRecording" class="text-green-400">Currently not recording</p>
                <p id="recording" class="hidden text-red-500">Recording</p>
            </div>

            <div>
                <x-input-label for="minutes" :value="__('Time in seconds')"/>

                <x-text-input
                    class="block mt-1 w-full"
                    type="number"
                    id="minutes"
                    name="minutes"
                    max="3599"
                />

            </div>


            <x-primary-button id="start">
                Start recording
            </x-primary-button>

        </div>


        <script>
            const startButton = document.getElementById('start');
            const notRecording = document.getElementById('notRecording');
            const recording = document.getElementById('recording');

            const recordingTimeInput = document.getElementById('minutes')

            startButton.addEventListener('click', startRecording);

            async function startRecording() {
                startButton.disabled = true;

                const url = new URL("{{ route('camera.start') }}");

                if(recordingTimeInput?.value) {
                    url.searchParams.set('time', recordingTimeInput?.value)
                }

                notRecording.classList.add('hidden')
                recording.classList.remove('hidden')

                fetch(url.toString())
                    .then(res => res.json())
                    .then(res => {
                        console.log(res);

                        notRecording.classList.remove('hidden')
                        recording.classList.add('hidden')
                        startButton.disabled = false;
                    })
            }

        </script>
    </div>

    <div class="py-4">
        <h2 class="text-xl font-semibold text-center">Videos</h2>

        <div class="my-4">
            @foreach($videos as $video)
                @if($video === 'video/.DS_Store')
                    @continue
                @endif

                <div class="flex flex-col justify-center items-center gap-2 my-4">
                    <p>{{ $video }}</p>
                    <video id="audio" controls src="{{ asset("storage/$video") }}"></video>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
