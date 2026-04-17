@php
    /** @var \App\Models\User $user */
    /** @var \Illuminate\Support\Collection<int, \App\Models\Link>|\Illuminate\Database\Eloquent\Collection<int, \App\Models\Link> $links */

    $theme = array_merge(config('yourbase.theme.default', []), is_array($user->theme) ? $user->theme : []);
    $buttonStyle = $theme['button_style'] ?? 'solid';
    $defaultIcon = config('yourbase.links.default_icon', 'fa-solid fa-link');
    $title = $user->name.' (@'.$user->username.')';
    $profileStyles = implode('; ', [
        '--yb-accent: '.($theme['accent'] ?? '#3B82F6'),
        '--yb-background: '.($theme['background'] ?? '#0F172A'),
        '--yb-surface: '.($theme['surface'] ?? '#111827'),
        '--yb-text: '.($theme['text'] ?? '#F8FAFC'),
    ]);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => $title])
    </head>
    <body
        class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.16),_transparent_32%),linear-gradient(180deg,_#f8fafc_0%,_#e2e8f0_100%)] px-4 py-8 text-zinc-950 antialiased dark:bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.18),_transparent_28%),linear-gradient(180deg,_#020617_0%,_#0f172a_100%)] dark:text-white sm:px-6 lg:px-8"
        style="{{ $profileStyles }}; background:
            radial-gradient(circle at top, color-mix(in srgb, var(--yb-accent) 22%, transparent), transparent 32%),
            linear-gradient(180deg, color-mix(in srgb, var(--yb-background) 82%, black), color-mix(in srgb, var(--yb-surface) 88%, black)); color: var(--yb-text);"
    >
        <main class="mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-5xl items-center justify-center">
            <section class="w-full">
                <div class="mx-auto flex w-full max-w-2xl flex-col gap-8">
                    <div class="flex flex-col items-center gap-5 text-center">
                        <div
                            class="flex size-20 shrink-0 items-center justify-center rounded-full text-2xl font-semibold shadow-sm"
                            style="background: color-mix(in srgb, var(--yb-text) 10%, transparent); border: 1px solid color-mix(in srgb, var(--yb-text) 20%, transparent); color: var(--yb-text);"
                        >
                            {{ $user->initials() }}
                        </div>

                        <div class="space-y-3">
                            <div class="space-y-2">
                                <h1 class="text-4xl font-semibold tracking-tight sm:text-5xl">{{ $user->name }}</h1>
                                <p class="text-base opacity-75 sm:text-lg">{{ '@'.$user->username }}</p>
                            </div>

                            <p class="mx-auto max-w-xl text-sm leading-7 opacity-90 sm:text-base">
                                {{ $user->bio ?: __('Everything worth clicking, all in one place.') }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="overflow-hidden rounded-[2rem] border border-white/40 p-5 shadow-[0_32px_80px_rgba(15,23,42,0.2)] sm:p-6 lg:p-8"
                        style="{{ $profileStyles }}; background:
                            linear-gradient(160deg, color-mix(in srgb, var(--yb-background) 92%, black), color-mix(in srgb, var(--yb-surface) 96%, black));
                            color: var(--yb-text);"
                    >
                        <div class="space-y-3">
                            @forelse ($links as $link)
                                <x-yourbase.profile-link :user="$user" :link="$link" :button-style="$buttonStyle" :default-icon="$defaultIcon" />
                            @empty
                                <div class="rounded-2xl border border-dashed px-4 py-5 text-center text-sm opacity-80">
                                    {{ __('No links are live on this profile yet.') }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
