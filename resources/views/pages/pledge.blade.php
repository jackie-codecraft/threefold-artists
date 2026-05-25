<x-layouts.app title="Become a Founding Supporter" metaDescription="Pledge your future support for Threefold Artists and help keep theatre, music, dance, and the arts accessible.">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-theatre-black py-24 sm:py-32">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="" role="presentation" class="h-full w-full object-cover object-center opacity-45">
            <div class="absolute inset-0 bg-gradient-to-b from-theatre-black/70 via-curtain-red/70 to-theatre-black/85"></div>
        </div>
        <div class="relative z-10 mx-auto max-w-5xl px-4 text-center sm:px-6 lg:px-8">
            <p class="mb-5 text-xs font-semibold uppercase tracking-[0.35em] text-stage-gold">Keeping Theatre Alive</p>
            <h1 class="font-display text-5xl font-light leading-tight text-white sm:text-7xl">Become a<br class="hidden sm:block"> Founding Supporter</h1>
            <p class="mx-auto mt-8 max-w-3xl text-lg leading-8 text-gray-100 sm:text-xl">
                Threefold Artists brings live theatre, music, dance, and the arts directly to senior living communities, hospitals, rehabilitation centers, schools, shelters, and underserved audiences throughout Greater Los Angeles and beyond.
            </p>
        </div>
    </section>

    {{-- Story + impact --}}
    <section class="bg-linen py-16 sm:py-24">
        <div class="mx-auto grid max-w-6xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-[1fr_0.9fr] lg:px-8">
            <div>
                <div class="mb-6 h-px w-16 bg-stage-gold"></div>
                <h2 class="font-display text-4xl font-light text-curtain-red sm:text-5xl">The Arts Belong to Everyone</h2>
                <div class="mt-8 space-y-6 text-lg leading-8 text-gray-700">
                    <p>At Threefold Artists, we believe everyone deserves access to the arts regardless of physical, financial, or accessibility barriers.</p>
                    <p>Through uplifting performances and meaningful artistic experiences, we aim to create connection, comfort, joy, and inspiration for audiences who may not otherwise have the opportunity to attend traditional venues.</p>
                    <p class="font-display text-2xl italic text-curtain-red">“When people cannot come to the arts, we will come to them.”</p>
                </div>
            </div>
            <div class="rounded-3xl border border-curtain-red/15 bg-white p-8 shadow-2xl shadow-curtain-red/10 sm:p-10">
                <p class="mb-5 text-xs font-semibold uppercase tracking-[0.2em] text-stage-gold">What You Make Possible</p>
                <h3 class="font-display text-3xl font-light text-curtain-red">Your support creates real access.</h3>
                <p class="mt-5 text-gray-600 leading-7">Your support allows us to create and deliver meaningful, high-quality performances directly to communities who would otherwise go without.</p>
                <ul class="mt-8 space-y-4 text-gray-700">
                    @foreach([
                        'Bring live music and theatre into senior living communities',
                        'Create moments of joy, memory, and connection',
                        'Employ and support working artists',
                        'Reach individuals who are often isolated or underserved',
                    ] as $item)
                        <li class="flex gap-3">
                            <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-stage-gold"></span>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- Pledge form --}}
    <section class="relative overflow-hidden bg-theatre-black py-16 sm:py-24" x-data="{ selectedAmount: '{{ old('amount', '100') }}', customAmount: '', useCustom: false }">
        <div class="absolute inset-0 bg-gradient-to-br from-theatre-black via-curtain-red-dark/90 to-theatre-black"></div>
        <div class="absolute inset-x-0 top-0 h-px bg-stage-gold/40"></div>
        <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-linen p-6 shadow-2xl shadow-black/35 ring-1 ring-stage-gold/20 sm:p-10 lg:p-14">
                <div class="text-center">
                    <p class="mb-4 text-xs font-semibold uppercase tracking-[0.25em] text-stage-gold">Pledge your future support</p>
                    <h2 class="font-display text-4xl font-light text-curtain-red sm:text-5xl">Founding Supporter Pledge Form</h2>
                    <div class="mx-auto mt-5 max-w-2xl space-y-4 text-gray-600 leading-7">
                        <p>
                            We are currently preparing our official donation platform and nonprofit banking infrastructure.
                        </p>
                        <p>
                            In the meantime, we invite you to become part of our growing community by submitting a Founding Supporter pledge. Once our donation system is fully active, we will personally follow up with instructions for completing your contribution.
                        </p>
                        <p class="font-semibold text-curtain-red">
                            Thank you for believing in the mission of keeping theatre and the arts alive for all.
                        </p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        Please review the highlighted fields and try again.
                    </div>
                @endif

                <form action="{{ route('pledge.store') }}" method="POST" class="mt-10 space-y-9">
                    @csrf
                    <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                    <input type="hidden" name="form_started_at" value="{{ now()->timestamp }}">

                    <section>
                        <h3 class="mb-5 text-xl font-semibold text-theatre-black">Contact Information</h3>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="sr-only">Full name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Full Name" class="w-full rounded-xl border border-linen-dark bg-white px-4 py-3 text-theatre-black placeholder-gray-400 focus:border-stage-gold focus:ring-stage-gold">
                                @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="sr-only">Email address</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="Email Address" class="w-full rounded-xl border border-linen-dark bg-white px-4 py-3 text-theatre-black placeholder-gray-400 focus:border-stage-gold focus:ring-stage-gold">
                                @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="phone" class="sr-only">Phone number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Phone Number (Optional)" class="w-full rounded-xl border border-linen-dark bg-white px-4 py-3 text-theatre-black placeholder-gray-400 focus:border-stage-gold focus:ring-stage-gold">
                                @error('phone')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    <section>
                        <h3 class="mb-5 text-xl font-semibold text-theatre-black">Pledge Amount</h3>
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                            @foreach([25, 50, 100, 250, 500] as $amount)
                                <label class="cursor-pointer rounded-xl border border-linen-dark bg-white px-4 py-4 text-center transition hover:border-stage-gold hover:bg-stage-gold/10" :class="selectedAmount == '{{ $amount }}' && ! useCustom ? 'border-stage-gold bg-stage-gold/10 ring-2 ring-stage-gold/30' : ''">
                                    <input type="radio" name="amount_choice" value="{{ $amount }}" class="sr-only" x-on:change="selectedAmount = '{{ $amount }}'; useCustom = false; customAmount = ''" @checked((string) old('amount', '100') === (string) $amount)>
                                    <span class="text-theatre-black">${{ $amount }}</span>
                                </label>
                            @endforeach
                            <label class="cursor-pointer rounded-xl border border-linen-dark bg-white px-4 py-4 text-center transition hover:border-stage-gold hover:bg-stage-gold/10" :class="useCustom ? 'border-stage-gold bg-stage-gold/10 ring-2 ring-stage-gold/30' : ''">
                                <input type="radio" name="amount_choice" value="other" class="sr-only" x-on:change="useCustom = true; selectedAmount = customAmount">
                                <span class="text-theatre-black">Other</span>
                            </label>
                        </div>
                        <div class="mt-4" x-show="useCustom" x-cloak>
                            <label for="custom_amount" class="sr-only">Custom pledge amount</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-4 flex items-center text-gray-500">$</span>
                                <input type="number" id="custom_amount" min="1" step="1" x-model="customAmount" x-on:input="selectedAmount = customAmount" placeholder="Custom amount" class="w-full rounded-xl border border-linen-dark bg-white py-3 pl-8 pr-4 text-theatre-black placeholder-gray-400 focus:border-stage-gold focus:ring-stage-gold">
                            </div>
                        </div>
                        <input type="hidden" name="amount" :value="useCustom ? customAmount : selectedAmount">
                        @error('amount')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>

                    <section>
                        <h3 class="mb-5 text-xl font-semibold text-theatre-black">Additional Message</h3>
                        <label for="message" class="sr-only">Additional message</label>
                        <textarea name="message" id="message" rows="6" placeholder="Share why the arts matter to you or how you heard about Threefold Artists." class="w-full rounded-xl border border-linen-dark bg-white px-4 py-3 text-theatre-black placeholder-gray-400 focus:border-stage-gold focus:ring-stage-gold">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>

                    <div class="rounded-2xl border border-stage-gold/30 bg-white/70 p-5">
                        <label class="flex items-start gap-3 text-sm leading-6 text-gray-700">
                            <input type="checkbox" name="pledge_acknowledgment" value="1" required class="mt-1 h-4 w-4 rounded border-stage-gold/60 text-curtain-red focus:ring-stage-gold" @checked(old('pledge_acknowledgment'))>
                            <span>I understand this is currently a pledge and supporter interest form. No financial contribution is being processed through this form at this time.</span>
                        </label>
                        @error('pledge_acknowledgment')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="inline-flex items-center justify-center rounded-full border border-stage-gold/70 bg-curtain-red px-10 py-4 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-curtain-red/25 transition hover:bg-theatre-black hover:shadow-stage-gold/20">
                            Become a Founding Supporter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layouts.app>
