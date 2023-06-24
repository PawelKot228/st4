<x-app-layout>
    <div>
        <div class="flex justify-center py-4 gap-2">
            <x-primary-button id="start">
                Start recording
            </x-primary-button>
            <x-primary-button id="stop" disabled>
                Stop recording
            </x-primary-button>
            <x-secondary-button id="submit" disabled>
                submit
            </x-secondary-button>
        </div>
        <div class="flex flex-col justify-center items-center gap-2 py-4">
            <audio id="audio" controls></audio>
            <a id="download" href="#" download="recording.wav">Download recording</a>
        </div>

        <script>
            let mediaRecorder;
            let audioChunks = [];

            let isSendingAudio = false;
            let audioBlob;

            const startButton = document.getElementById('start');
            const stopButton = document.getElementById('stop');
            const submitButton = document.getElementById('submit');
            const audioElement = document.getElementById('audio');
            const downloadLink = document.getElementById('download');

            startButton.addEventListener('click', startRecording);
            stopButton.addEventListener('click', stopRecording);
            submitButton.addEventListener('click', sendRecording);

            async function startRecording() {
                startButton.disabled = true;
                stopButton.disabled = false;

                audioChunks = [];
                const stream = await navigator.mediaDevices.getUserMedia({audio: true});

                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.addEventListener('dataavailable', event => {
                    audioChunks.push(event.data);
                });

                mediaRecorder.addEventListener('stop', () => {
                    audioBlob = new Blob(audioChunks, {'type': 'audio/wav'});
                    const audioUrl = URL.createObjectURL(audioBlob);
                    audioElement.src = audioUrl;
                    downloadLink.href = audioUrl;
                });

                mediaRecorder.start();
            }

            function stopRecording() {
                startButton.disabled = false;
                submitButton.disabled = false;
                stopButton.disabled = true;

                if (mediaRecorder) {
                    mediaRecorder.stop();
                }
            }

            function sendRecording() {
                if(isSendingAudio || !audioBlob) {
                    return
                }

                isSendingAudio = true;

                const formData = new FormData();
                formData.append('audio', audioBlob, 'recording.wav');

                fetch('{{ route('audio.save') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(data => {
                    console.log(data)
                    isSendingAudio = false;
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
            }
        </script>
    </div>

    <div class="py-4">
        <h2 class="text-xl font-semibold text-center">Audios</h2>

        <div class="my-4">
            @foreach($audios as $audio)
                <div class="flex flex-col justify-center items-center gap-2 my-4">
                    <p>{{ $audio }}</p>
                    <audio id="audio" controls src="{{ asset("storage/$audio") }}"></audio>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
