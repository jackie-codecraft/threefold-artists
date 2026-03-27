<x-layouts.app title="Events" metaDescription="Upcoming live performances by Threefold Artists. Theatre, music, and dance events in your community.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 bg-theatre-black relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="w-full h-full object-cover object-center opacity-60">
    </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-16 h-px bg-stage-gold mb-6"></div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Calendar</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-white">Upcoming Events</h1>
            <p class="text-lg text-gray-300 mt-4 max-w-2xl">Find a performance near you.</p>
        </div>
    </section>
    <div class="h-12 bg-gradient-to-b from-theatre-black to-white"></div>

    <section class="py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ view: 'list' }">

            {{-- View Toggle --}}
            <div class="flex gap-3 mb-16">
                <button @click="view = 'list'"
                    :class="view === 'list' ? 'bg-theatre-black text-white' : 'border border-gray-300 text-gray-500 hover:border-theatre-black hover:text-theatre-black'"
                    class="px-5 py-2 text-xs font-semibold tracking-[0.15em] uppercase transition-colors">
                    List
                </button>
                <button @click="view = 'calendar'"
                    :class="view === 'calendar' ? 'bg-theatre-black text-white' : 'border border-gray-300 text-gray-500 hover:border-theatre-black hover:text-theatre-black'"
                    class="px-5 py-2 text-xs font-semibold tracking-[0.15em] uppercase transition-colors">
                    Calendar
                </button>
            </div>

            {{-- List View --}}
            <div x-show="view === 'list'">
                @if($events->isEmpty())
                    <div class="max-w-lg mx-auto text-center">
                        <div class="w-12 h-px bg-stage-gold mx-auto mb-8"></div>
                        <h2 class="font-display text-3xl font-light text-theatre-black mb-4">No Upcoming Events</h2>
                        <p class="text-gray-500 leading-relaxed mb-10">Our next performances are being planned. Request a performance for your community, or subscribe to stay updated.</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('request-performance') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">
                                Request a Performance
                            </a>
                            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-theatre-black text-theatre-black text-sm font-semibold tracking-wide uppercase hover:bg-theatre-black hover:text-white transition-colors">
                                Contact Us
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16">
                        @foreach($events as $event)
                        <script type="application/ld+json">
                        {
                            "@@context": "https://schema.org",
                            "@@type": "Event",
                            "name": "{{ $event->title }}",
                            "startDate": "{{ $event->date->toIso8601String() }}",
                            "location": {
                                "@@type": "Place",
                                "name": "{{ $event->venue_name }}",
                                "address": "{{ $event->venue_address }}"
                            },
                            "organizer": {
                                "@@type": "Organization",
                                "name": "Threefold Artists"
                            },
                            "description": "{{ Str::limit(strip_tags($event->description), 200) }}"
                        }
                        </script>
                        <div>
                            <div class="mb-4">
                                <span class="font-display text-4xl font-light text-theatre-black">{{ $event->date->format('d') }}</span>
                                <span class="text-sm font-semibold tracking-[0.15em] uppercase text-gray-400 ml-2">{{ $event->date->format('M Y') }}</span>
                            </div>
                            <div class="w-12 h-px bg-stage-gold mb-4"></div>
                            <h3 class="font-display text-xl font-normal text-theatre-black mb-2">{{ $event->title }}</h3>
                            @if($event->art_form)
                                <span class="text-xs font-semibold tracking-[0.15em] uppercase text-stage-gold-dark">{{ ucfirst($event->art_form) }}</span>
                            @endif
                            <p class="text-gray-500 text-sm mt-3 mb-3">{{ Str::limit($event->description, 120) }}</p>
                            <div class="text-sm text-gray-400 space-y-1">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $event->venue_name }}
                                </div>
                                @if($event->time)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-16">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>

            {{-- Calendar View --}}
            <div x-data="calendarView({{ $allEvents->toJson() }})" x-show="view === 'calendar'" x-cloak @keydown.escape.window="closeModal()">
                {{-- Month Nav --}}
                <div class="flex items-center justify-between mb-8">
                    <button @click="prevMonth()" class="text-gray-400 hover:text-theatre-black transition-colors p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <h3 class="font-display text-2xl font-light text-theatre-black" x-text="monthLabel"></h3>
                    <button @click="nextMonth()" class="text-gray-400 hover:text-theatre-black transition-colors p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                {{-- Day Headers --}}
                <div class="grid grid-cols-7 gap-px mb-2">
                    <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']">
                        <div class="text-center text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 py-2" x-text="day"></div>
                    </template>
                </div>

                {{-- Calendar Grid --}}
                <div class="grid grid-cols-7 gap-px">
                    <template x-for="cell in calendarCells" :key="cell.key">
                        <div @click="cell.events.length ? openModal(cell) : null"
                             :class="{
                                 'bg-linen': cell.isToday,
                                 'cursor-pointer hover:bg-gray-50': cell.events.length > 0,
                             }"
                             class="min-h-[70px] sm:min-h-[80px] p-1 sm:p-2 border border-gray-100">
                            <span class="text-xs sm:text-sm" :class="cell.isCurrentMonth ? 'text-theatre-black' : 'text-gray-300'" x-text="cell.day"></span>
                            <template x-if="cell.events.length > 0">
                                <div class="mt-0.5 sm:mt-1">
                                    {{-- On mobile: show dot indicator. On desktop: show title --}}
                                    <div class="flex flex-wrap gap-0.5 sm:hidden">
                                        <template x-for="ev in cell.events">
                                            <div class="w-1.5 h-1.5 bg-stage-gold rounded-full"></div>
                                        </template>
                                    </div>
                                    <div class="hidden sm:block">
                                        <template x-for="ev in cell.events.slice(0, 2)">
                                            <div class="text-xs text-stage-gold-dark truncate leading-tight" x-text="ev.title"></div>
                                        </template>
                                        <template x-if="cell.events.length > 2">
                                            <div class="text-xs text-gray-400" x-text="'+' + (cell.events.length - 2) + ' more'"></div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Event Modal --}}
                <div x-show="modalOpen" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-6"
                     @click.self="closeModal()">

                    {{-- Backdrop --}}
                    <div class="absolute inset-0 bg-theatre-black/70"></div>

                    {{-- Modal panel — bottom sheet on mobile, centered on desktop --}}
                    <div class="relative w-full sm:max-w-lg bg-white z-10 sm:mx-auto"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="translate-y-full sm:translate-y-4 sm:opacity-0"
                         x-transition:enter-end="translate-y-0 sm:opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="translate-y-0 sm:opacity-100"
                         x-transition:leave-end="translate-y-full sm:translate-y-4 sm:opacity-0">

                        {{-- Modal header --}}
                        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100">
                            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400" x-text="selectedDayLabel"></p>
                            <button @click="closeModal()" class="text-gray-400 hover:text-theatre-black transition-colors" aria-label="Close">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- Event list --}}
                        <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                            <template x-for="(ev, index) in selectedDayEvents" :key="index">
                                <div :class="index > 0 ? 'mt-6 pt-6 border-t border-gray-100' : ''">
                                    <h4 class="font-display text-xl font-normal text-theatre-black mb-3" x-text="ev.title"></h4>
                                    <div class="space-y-1.5 text-sm text-gray-500">
                                        <div x-show="ev.time" class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span x-text="formatTime(ev.time)"></span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span>
                                                <span class="block font-medium text-theatre-black" x-text="ev.venue_name"></span>
                                                <span x-show="ev.venue_address" class="text-gray-400 text-xs" x-text="ev.venue_address"></span>
                                            </span>
                                        </div>
                                        <div x-show="ev.art_form" class="flex items-center gap-2 pt-1">
                                            <span class="text-xs font-semibold tracking-[0.15em] uppercase text-stage-gold" x-text="ev.art_form ? ev.art_form.replace('_', ' ') : ''"></span>
                                        </div>
                                    </div>
                                    <p x-show="ev.description" class="text-gray-500 text-sm leading-relaxed mt-3" x-text="ev.description"></p>
                                </div>
                            </template>
                        </div>

                        {{-- Mobile drag handle hint --}}
                        <div class="sm:hidden h-5 flex items-center justify-center">
                            <div class="w-10 h-1 bg-gray-200 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@push('scripts')
<script>
function calendarView(events) {
    const eventsList = Array.isArray(events) ? events : (events.data || []);
    return {
        currentDate: new Date(),
        modalOpen: false,
        selectedDay: null,
        selectedDayEvents: [],
        selectedDayLabel: '',
        events: eventsList.map(e => ({
            ...e,
            dateObj: new Date(String(e.date).substring(0, 10) + 'T00:00:00')
        })),
        get monthLabel() {
            return this.currentDate.toLocaleString('en-US', { month: 'long', year: 'numeric' });
        },
        get calendarCells() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();
            const today = new Date();
            const cells = [];

            // Previous month days
            for (let i = firstDay - 1; i >= 0; i--) {
                const day = daysInPrevMonth - i;
                cells.push({ key: 'prev-' + day, day, isCurrentMonth: false, isToday: false, events: [] });
            }

            // Current month days
            for (let d = 1; d <= daysInMonth; d++) {
                const isToday = d === today.getDate() && month === today.getMonth() && year === today.getFullYear();
                const dayEvents = this.events.filter(e => {
                    return e.dateObj.getDate() === d && e.dateObj.getMonth() === month && e.dateObj.getFullYear() === year;
                });
                cells.push({ key: 'cur-' + d, day: d, isCurrentMonth: true, isToday, events: dayEvents });
            }

            // Next month days to fill grid
            const remaining = 42 - cells.length;
            for (let i = 1; i <= remaining; i++) {
                cells.push({ key: 'next-' + i, day: i, isCurrentMonth: false, isToday: false, events: [] });
            }

            return cells;
        },
        prevMonth() {
            this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
            this.selectedDay = null;
        },
        nextMonth() {
            this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
            this.selectedDay = null;
        },
        openModal(cell) {
            this.selectedDay = cell;
            this.selectedDayEvents = cell.events;
            const d = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), cell.day);
            this.selectedDayLabel = d.toLocaleString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
            this.modalOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.modalOpen = false;
            document.body.style.overflow = '';
        },
        formatTime(time) {
            if (!time) return '';
            const [h, m] = time.split(':');
            const hour = parseInt(h);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${m} ${ampm}`;
        }
    };
}
</script>
@endpush

</x-layouts.app>
