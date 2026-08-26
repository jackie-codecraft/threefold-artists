<x-layouts.app title="Get Involved" metaDescription="Volunteer as a performing artist with Threefold Artists. Share your talent with communities that need it most.">

    {{-- Page Hero --}}
    <section class="pt-12 pb-12 bg-theatre-black relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Join Us</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Get Involved</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Share your talent. Change lives. Return threefold.</p>
        </div>
    </section>

    {{-- Why Volunteer --}}
    <section class="py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Why Volunteer</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">Why Volunteer With Us?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Make a Difference</h3>
                    <p class="text-gray-500 leading-relaxed">Bring joy and connection to people who cannot easily access live performances.</p>
                </div>
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Grow as an Artist</h3>
                    <p class="text-gray-500 leading-relaxed">Performing in diverse settings challenges and develops your artistry in unique ways.</p>
                </div>
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-theatre-black mb-4">Join a Community</h3>
                    <p class="text-gray-500 leading-relaxed">Connect with fellow artists who share your passion for accessible performing arts.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Application Form --}}
    <section class="py-16 sm:py-20 border-t border-gray-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Apply</p>
                <h2 class="font-display text-4xl font-light text-theatre-black mb-4">Artist Application</h2>
                <p class="text-gray-500">Fill in the form below and we will be in touch about volunteer opportunities.</p>
            </div>

            <form action="{{ route('get-involved.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8"
                x-data="{ submitting: false }"
                @submit="if (submitting) { $event.preventDefault(); return; } submitting = true">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="name" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Full Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required aria-required="true"
                            @error('name') aria-describedby="name-error" @enderror
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        @error('name') <p id="name-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required aria-required="true"
                            @error('email') aria-describedby="email-error" @enderror
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                        @error('email') <p id="email-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">
                    </div>

                    <div>
                        <label for="discipline" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Art Form *</label>
                        <select name="discipline" id="discipline" required aria-required="true"
                            @error('discipline') aria-describedby="discipline-error" @enderror
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black focus:border-theatre-black focus:ring-0">
                            <option value="">Select...</option>
                            @foreach(\App\Support\DisciplineOptions::labels() as $value => $label)
                            <option value="{{ $value }}" {{ old('discipline') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('discipline') <p id="discipline-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="experience" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Experience</label>
                    <textarea name="experience" id="experience" rows="3"
                        placeholder="Tell us about your performing experience..."
                        class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">{{ old('experience') }}</textarea>
                </div>

                <div>
                    <label for="bio" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Short Bio</label>
                    <textarea name="bio" id="bio" rows="3"
                        placeholder="A brief description of yourself as an artist..."
                        class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">{{ old('bio') }}</textarea>
                </div>

                <div>
                    <label for="availability" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Availability</label>
                    <textarea name="availability" id="availability" rows="2"
                        placeholder="e.g., Weekday afternoons, weekends..."
                        class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black placeholder-gray-400 focus:border-theatre-black focus:ring-0">{{ old('availability') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="photo" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Artist Photo</label>
                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp"
                            @error('photo') aria-describedby="photo-error" @enderror
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black file:mr-4 file:border-0 file:bg-theatre-black file:px-4 file:py-2 file:text-sm file:font-semibold file:uppercase file:tracking-wide file:text-white hover:file:bg-gray-800 focus:border-theatre-black focus:ring-0">
                        <p class="mt-2 text-sm text-gray-500">JPG, PNG, or WebP up to 5 MB.</p>
                        @error('photo') <p id="photo-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="resume" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Resume</label>
                        <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                            @error('resume') aria-describedby="resume-error" @enderror
                            class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black file:mr-4 file:border-0 file:bg-theatre-black file:px-4 file:py-2 file:text-sm file:font-semibold file:uppercase file:tracking-wide file:text-white hover:file:bg-gray-800 focus:border-theatre-black focus:ring-0">
                        <p class="mt-2 text-sm text-gray-500">PDF, DOC, or DOCX up to 10 MB.</p>
                        @error('resume') <p id="resume-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div
                    x-data="{
                        files: [],
                        maxFiles: 5,
                        addFiles(selectedFiles) {
                            const incoming = Array.from(selectedFiles);
                            const remaining = this.maxFiles - this.files.length;
                            this.files = this.files.concat(incoming.slice(0, Math.max(remaining, 0)));
                            this.syncFiles();
                        },
                        removeFile(index) {
                            this.files.splice(index, 1);
                            this.syncFiles();
                        },
                        syncFiles() {
                            const dataTransfer = new DataTransfer();
                            this.files.forEach((file) => dataTransfer.items.add(file));
                            this.$refs.supportingMedia.files = dataTransfer.files;
                        },
                        formatFileSize(bytes) {
                            if (bytes < 1024 * 1024) return `${Math.ceil(bytes / 1024)} KB`;
                            return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
                        },
                    }"
                    @dragover.prevent
                    @drop.prevent="addFiles($event.dataTransfer.files)"
                >
                    <label for="supporting_media" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Supporting Media</label>
                    <input x-ref="supportingMedia" type="file" name="supporting_media[]" id="supporting_media" multiple accept="video/mp4,video/quicktime,video/webm,audio/mpeg,audio/wav,audio/x-m4a,image/jpeg,image/png,image/webp,application/pdf"
                        @change="addFiles($event.target.files)"
                        @error('supporting_media') aria-describedby="supporting-media-error" @enderror
                        class="sr-only">
                    <div class="border border-dashed border-gray-300 bg-gray-50 px-5 py-6 sm:flex sm:items-center sm:justify-between sm:gap-6">
                        <div>
                            <p class="text-sm font-semibold text-theatre-black">Add performance samples and supporting files</p>
                            <p class="mt-1 text-sm text-gray-500">Choose files or drag them here. Add more whenever you are ready.</p>
                        </div>
                        <label for="supporting_media" class="mt-4 inline-flex cursor-pointer items-center justify-center px-5 py-2.5 bg-theatre-black text-white text-xs font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors sm:mt-0">
                            Add Files
                        </label>
                    </div>
                    <template x-if="files.length">
                        <ul class="mt-4 divide-y divide-gray-200 border-y border-gray-200">
                            <template x-for="(file, index) in files" :key="`${file.name}-${file.size}-${index}`">
                                <li class="flex items-center justify-between gap-4 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-theatre-black" x-text="file.name"></p>
                                        <p class="text-xs text-gray-500" x-text="formatFileSize(file.size)"></p>
                                    </div>
                                    <button type="button" @click="removeFile(index)" class="shrink-0 text-xs font-semibold uppercase tracking-wide text-theatre-black underline hover:text-curtain-red">Remove</button>
                                </li>
                            </template>
                        </ul>
                    </template>
                    <p class="mt-3 text-sm text-gray-500">Optional. Share up to 5 files that showcase your work, such as a performance reel, song, scene, monologue, dance, audio sample, portfolio, or other supporting materials. Videos and files are private and reviewed only by Threefold Artists. MP4, MOV, WebM, MP3, WAV, M4A, JPG, PNG, WebP, or PDF, up to 20 MB each.</p>
                    <p x-show="files.length >= maxFiles" x-cloak class="mt-2 text-sm text-gray-500">You have selected the maximum of 5 files. Remove one to add another.</p>
                    @error('supporting_media') <p id="supporting-media-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    @error('supporting_media.*') <p id="supporting-media-error" class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mt-12 border-t border-gray-200 pt-8">
                    <x-turnstile />
                    <button type="submit" :disabled="submitting" :aria-busy="submitting"
                        class="inline-flex min-w-56 cursor-pointer items-center justify-center gap-3 px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase transition-all hover:bg-gray-800 hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-theatre-black disabled:cursor-wait disabled:translate-y-0 disabled:bg-gray-500 disabled:shadow-none">
                        <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span x-text="submitting ? 'Submitting Application' : 'Submit Application'"></span>
                    </button>
                    <p x-show="submitting" x-cloak class="mt-3 text-sm text-gray-500" role="status">Uploading your files and submitting your application. Please keep this page open.</p>
                </div>
            </form>
        </div>
    </section>

</x-layouts.app>
