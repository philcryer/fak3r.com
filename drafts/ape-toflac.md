
Home
Services
Our work
Articles
Contact
Our Articles about the Web and Drupal
Converting audio file formats from .ape with .cue to .flac on linux
by Andrew Burcin / 25 November, 2010
Linux
I recently needed to convert some audio files form a client into .flac format. Flac is digital audio file, like .mp3, except its an opensource format and its "lossless". Its very simple using command line tools in Linux to accomplish this.

You'll need flac, bchunk and ffmpeg. If you run Debian or Ubuntu, just type

$ sudo apt-get install flac bchunk ffmpeg
the first step is to convert the whole .ape into .wav.

$ ffmpeg -i INPUT.ape output.wav
Then you split the .wav into individual tracks.

$ bchunk -w output.wav INPUT.cue BASE_FILENAME
And finally convert to flac.

$ flac --best BASE_FILENAME*
You can then remove the .ape, .cue and interim .wav files.

Share this

