<x-layouts.app title="Apply as an Artist" metaDescription="Volunteer as a performing artist with Threefold Artists. Share your talent with communities that need it most.">

    {{-- Page Hero --}}
    <section class="pt-12 pb-12 bg-band relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4"><a href="{{ route('get-involved') }}" class="hover:text-white transition-colors">Get Involved</a> / Performing Artists</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Apply as an Artist</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Share your talent. Change lives. Return threefold.</p>
        </div>
    </section>

    {{-- Why Volunteer --}}
    <section class="py-16 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-20">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-ink-faint mb-4">Why Volunteer</p>
                <h2 class="font-display text-4xl sm:text-5xl font-light text-ink">Why Volunteer With Us?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-ink mb-4">Make a Difference</h3>
                    <p class="text-ink-muted leading-relaxed">Bring joy and connection to people who cannot easily access live performances.</p>
                </div>
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-ink mb-4">Grow as an Artist</h3>
                    <p class="text-ink-muted leading-relaxed">Performing in diverse settings challenges and develops your artistry in unique ways.</p>
                </div>
                <div>
                    <div class="w-12 h-px bg-stage-gold mb-8"></div>
                    <h3 class="font-display text-2xl font-normal text-ink mb-4">Join a Community</h3>
                    <p class="text-ink-muted leading-relaxed">Connect with fellow artists who share your passion for accessible performing arts.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Application Form --}}
    <section class="py-16 sm:py-20 border-t border-line">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-ink-faint mb-4">Apply</p>
                <h2 class="font-display text-4xl font-light text-ink mb-4">Artist Application</h2>
                <p class="text-ink-muted">Fill in the form below and we will be in touch about volunteer opportunities.</p>
            </div>

            <form action="{{ route('artist-application.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8"
                x-data="{
                    submitting: false,
                    limits: @js($uploadLimits),
                    photoError: '',
                    resumeError: '',
                    totalError: '',
                    mb(bytes) {
                        return `${(bytes / (1024 * 1024)).toFixed(bytes < 10 * 1024 * 1024 ? 1 : 0)} MB`;
                    },
                    checkSingleFile(input, maxBytes) {
                        const file = input.files[0];
                        if (! file || file.size <= maxBytes) return '';
                        input.value = '';
                        return `“${file.name}” is ${this.mb(file.size)}. The limit is ${this.mb(maxBytes)}. Please choose a smaller file.`;
                    },
                    totalBytes() {
                        return Array.from(this.$root.querySelectorAll('input[type=file]'))
                            .reduce((total, input) => total + Array.from(input.files).reduce((sum, file) => sum + file.size, 0), 0);
                    },
                    handleSubmit(event) {
                        if (this.submitting) { event.preventDefault(); return; }
                        const total = this.totalBytes();
                        if (this.limits.request && total > this.limits.request) {
                            event.preventDefault();
                            this.totalError = `Your files add up to ${this.mb(total)}, which is over the ${this.mb(this.limits.request)} limit for one application. Remove a file, or share large videos with the Demo Video Link instead.`;
                            this.$nextTick(() => this.$refs.totalError.scrollIntoView({ block: 'center' }));
                            return;
                        }
                        this.totalError = '';
                        this.submitting = true;
                    },
                }"
                @submit="handleSubmit($event)">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required aria-required="true"
                            @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                            class="form-field">
                        @error('name') <p id="name-error" class="text-red-600 text-sm mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required aria-required="true"
                            @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                            class="form-field">
                        @error('email') <p id="email-error" class="text-red-600 text-sm mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                            class="form-field">
                    </div>

                    <div>
                        <label for="discipline" class="form-label">Art Form *</label>
                        <select name="discipline" id="discipline" required aria-required="true"
                            @error('discipline') aria-invalid="true" aria-describedby="discipline-error" @enderror
                            class="form-field">
                            <option value="">Select...</option>
                            @foreach(\App\Support\DisciplineOptions::labels() as $value => $label)
                            <option value="{{ $value }}" {{ old('discipline') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('discipline') <p id="discipline-error" class="text-red-600 text-sm mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="experience" class="form-label">Experience</label>
                    <textarea name="experience" id="experience" rows="3"
                        placeholder="Tell us about your performing experience..."
                        class="form-field">{{ old('experience') }}</textarea>
                </div>

                <div>
                    <label for="bio" class="form-label">Short Bio</label>
                    <textarea name="bio" id="bio" rows="3"
                        placeholder="A brief description of yourself as an artist..."
                        class="form-field">{{ old('bio') }}</textarea>
                </div>

                <div>
                    <label for="availability" class="form-label">Availability</label>
                    <textarea name="availability" id="availability" rows="2"
                        placeholder="e.g., Weekday afternoons, weekends..."
                        class="form-field">{{ old('availability') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="photo" class="form-label">Artist Photo</label>
                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp"
                            @change="photoError = checkSingleFile($event.target, limits.photo); totalError = ''"
                            @error('photo') aria-invalid="true" aria-describedby="photo-error" @enderror
                            class="form-field">
                        <p class="mt-2 text-sm text-ink-muted">JPG, PNG, or WebP up to 5 MB.</p>
                        <p x-show="photoError" x-text="photoError" x-cloak class="text-red-600 text-sm mt-1 dark:text-red-400" role="alert"></p>
                        @error('photo') <p id="photo-error" class="text-red-600 text-sm mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="resume" class="form-label">Resume</label>
                        <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                            @change="resumeError = checkSingleFile($event.target, limits.resume); totalError = ''"
                            @error('resume') aria-invalid="true" aria-describedby="resume-error" @enderror
                            class="form-field">
                        <p class="mt-2 text-sm text-ink-muted">PDF, DOC, or DOCX up to 10 MB.</p>
                        <p x-show="resumeError" x-text="resumeError" x-cloak class="text-red-600 text-sm mt-1 dark:text-red-400" role="alert"></p>
                        @error('resume') <p id="resume-error" class="text-red-600 text-sm mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="demo_video_url" class="form-label">Demo Video Link</label>
                    <input type="url" name="demo_video_url" id="demo_video_url" value="{{ old('demo_video_url') }}" inputmode="url"
                        placeholder="https://youtube.com/watch?v=…"
                        aria-describedby="demo-video-url-help @error('demo_video_url') demo-video-url-error @enderror"
                        class="form-field">
                    <p id="demo-video-url-help" class="mt-2 text-sm text-ink-muted">Optional. <strong class="font-semibold text-ink-soft">The best way to share a performance video.</strong> Paste a link from YouTube, Vimeo, Google Drive, or Dropbox. Unlisted links are fine; just make sure anyone with the link can view it. If your video is too large to upload below, share it here instead.</p>
                    @error('demo_video_url') <p id="demo-video-url-error" class="text-red-600 text-sm mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div
                    x-data="{
                        files: [],
                        maxFiles: @js($uploadLimits['mediaMaxFiles']),
                        mediaError: '',
                        addFiles(selectedFiles) {
                            const selected = Array.from(selectedFiles);
                            const tooLarge = selected.filter((file) => file.size > this.limits.media);
                            const incoming = selected.filter((file) => file.size <= this.limits.media);
                            this.totalError = '';
                            this.mediaError = tooLarge.length
                                ? `${tooLarge.map((file) => `“${file.name}” (${this.mb(file.size)})`).join(', ')} ${tooLarge.length > 1 ? 'are' : 'is'} over the ${this.mb(this.limits.media)} limit and ${tooLarge.length > 1 ? 'were' : 'was'} not added. Share large videos with the Demo Video Link above instead.`
                                : '';
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
                    <label for="supporting_media" class="form-label">Supporting Media</label>
                    <input x-ref="supportingMedia" type="file" name="supporting_media[]" id="supporting_media" multiple accept="video/mp4,video/quicktime,video/webm,audio/mpeg,audio/wav,audio/x-m4a,image/jpeg,image/png,image/webp,application/pdf"
                        @change="addFiles($event.target.files)"
                        @error('supporting_media') aria-invalid="true" aria-describedby="supporting-media-error" @enderror
                        class="sr-only">
                    <div class="rounded-[10px] border border-dashed border-[#D9D1C3] bg-surface dark:border-line-strong px-5 py-6 sm:flex sm:items-center sm:justify-between sm:gap-6">
                        <div>
                            <p class="text-sm font-semibold text-ink">Add performance samples and supporting files</p>
                            <p class="mt-1 text-sm text-ink-muted">Choose files or drag them here. Add more whenever you are ready.</p>
                        </div>
                        <label for="supporting_media" class="mt-4 inline-flex shrink-0 whitespace-nowrap rounded-[7px] cursor-pointer items-center justify-center px-5 py-2.5 bg-cta text-on-cta text-xs font-semibold tracking-wide uppercase hover:bg-cta-hover transition-colors sm:mt-0">
                            Add Files
                        </label>
                    </div>
                    <template x-if="files.length">
                        <ul class="mt-4 divide-y divide-line border-y border-line">
                            <template x-for="(file, index) in files" :key="`${file.name}-${file.size}-${index}`">
                                <li class="flex items-center justify-between gap-4 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-ink" x-text="file.name"></p>
                                        <p class="text-xs text-ink-muted" x-text="formatFileSize(file.size)"></p>
                                    </div>
                                    <button type="button" @click="removeFile(index)" class="shrink-0 text-xs font-semibold uppercase tracking-wide text-ink underline hover:text-red-text">Remove</button>
                                </li>
                            </template>
                        </ul>
                    </template>
                    <p class="mt-3 text-sm text-ink-muted">Optional. Share up to 5 files that showcase your work, such as a performance reel, song, scene, monologue, dance, audio sample, portfolio, or other supporting materials. Videos and files are private and reviewed only by Threefold Artists. MP4, MOV, WebM, MP3, WAV, M4A, JPG, PNG, WebP, or PDF, up to 20 MB each. <strong class="font-semibold text-ink-soft">Video too large?</strong> Use the Demo Video Link above instead.</p>
                    <p x-show="mediaError" x-text="mediaError" x-cloak class="text-red-600 text-sm mt-2 dark:text-red-400" role="alert"></p>
                    <p x-show="files.length >= maxFiles" x-cloak class="mt-2 text-sm text-ink-muted">You have selected the maximum of 5 files. Remove one to add another.</p>
                    @error('supporting_media') <p id="supporting-media-error" class="text-red-600 text-sm mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    @error('supporting_media.*') <p id="supporting-media-error" class="text-red-600 text-sm mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="mt-12 border-t border-line pt-8">
                    <p x-ref="totalError" x-show="totalError" x-text="totalError" x-cloak class="mb-6 border-l-4 border-red-500 bg-red-50 p-4 text-sm text-red-700 dark:border-red-400 dark:bg-red-950/40 dark:text-red-300" role="alert"></p>
                    <x-turnstile />
                    <button type="submit" :disabled="submitting" :aria-busy="submitting"
                        class="inline-flex min-w-56 cursor-pointer items-center justify-center gap-3 px-8 py-3.5 bg-cta text-on-cta text-sm font-semibold tracking-wide uppercase transition-all hover:bg-cta-hover hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-theatre-black disabled:cursor-wait disabled:translate-y-0 disabled:bg-gray-500 disabled:shadow-none">
                        <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span x-text="submitting ? 'Submitting Application' : 'Submit Application'"></span>
                    </button>
                    <p x-show="submitting" x-cloak class="mt-3 text-sm text-ink-muted" role="status">Uploading your files and submitting your application. Please keep this page open.</p>
                </div>
            </form>
        </div>
    </section>

</x-layouts.app>
