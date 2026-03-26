<x-layouts.app title="Events" metaDescription="Upcoming live performances by Threefold Artists. Theatre, music, and dance events in your community.">

    {{-- Page Hero --}}
    <section class="pt-16 pb-20 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Calendar</p>
            <h1 class="font-display text-5xl sm:text-6xl font-light text-theatre-black">Upcoming Events</h1>
            <p class="text-lg text-gray-500 mt-4 max-w-2xl">Find a performance near you.</p>
        </div>
    </section>

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
            <div x-data="calendarView({{ $allEvents->toJson() }})" x-show="view === 'calendar'" x-cloak>
                {{-- Month Nav --}}
                <div class="flex items-center justify-between mb-8">
                    <button @click="prevMonth()" class="text-gray-400 hover:text-theatre-black transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <h3 class="font-display text-2xl font-light text-theatre-black" x-text="monthLabel"></h3>
                    <button @click="nextMonth()" class="text-gray-400 hover:text-theatre-black transition-colors">
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
                        <div @click="cell.events.length ? selectDay(cell) : null"
                             :class="{
                                 'bg-linen': cell.isToday,
                                 'cursor-pointer hover:bg-gray-50': cell.events.length > 0,
                             }"
                             class="min-h-[80px] p-2 border border-gray-100">
                            <span class="text-sm" :class="cell.isCurrentMonth ? 'text-theatre-black' : 'text-gray-300'" x-text="cell.day"></span>
                            <template x-if="cell.events.length > 0">
                                <div class="mt-1">
                                    <template x-for="ev in cell.events.slice(0, 2)">
                                        <div class="text-xs text-stage-gold-dark truncate" x-text="ev.title"></div>
                                    </template>
                                    <template x-if="cell.events.length > 2">
                                        <div class="text-xs text-gray-400" x-text="'+' + (cell.events.length - 2) + ' more'"></div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Selected Day Detail --}}
                <div x-show="selectedDay" x-cloak class="mt-8 pt-8 border-t border-gray-200">
                    <h4 class="font-display text-xl font-light text-theatre-black mb-6" x-text="selectedDayLabel"></h4>
                    <template x-for="ev in selectedDayEvents">
                        <div class="mb-6 pb-6 border-b border-gray-100 last:border-0">
                            <h5 class="font-display text-lg font-normal text-theatre-black" x-text="ev.title"></h5>
                            <div class="text-sm text-gray-400 mt-1" x-text="ev.venue_name"></div>
                            <div x-show="ev.time" class="text-sm text-gray-400" x-text="ev.time"></div>
                        </div>
                    </template>
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
        selectedDay: null,
        selectedDayEvents: [],
        selectedDayLabel: '',
        events: eventsList.map(e => ({
            ...e,
            dateObj: new Date(e.date + 'T00:00:00')
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
        selectDay(cell) {
            this.selectedDay = cell;
            this.selectedDayEvents = cell.events;
            const d = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), cell.day);
            this.selectedDayLabel = d.toLocaleString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
        }
    };
}
</script>
@endpush

</x-layouts.app>
