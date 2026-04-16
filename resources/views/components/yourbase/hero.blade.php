@props([
    'activeLinkCount' => 0,
    'bio' => '',
    'buttonStyle' => 'solid',
    'name' => '',
    'previewLinks' => [],
    'previewStyles' => '',
    'username' => '',
])

<section
    class="overflow-hidden rounded-[2rem] border border-zinc-200/70 bg-gradient-to-br from-white via-sky-50 to-zinc-100 shadow-sm dark:border-white/10 dark:from-zinc-900 dark:via-sky-950/30 dark:to-zinc-950"
>
    <div class="grid gap-8 px-6 py-8 lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)] lg:px-8">
        <div class="space-y-5">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-600 dark:text-sky-300">
                    {{ __('Yourbase') }}
                </p>
                <flux:heading size="xl" class="max-w-2xl">
                    {{ __('Shape the page people land on when they click your links.') }}
                </flux:heading>
                <flux:text class="max-w-xl text-base text-zinc-600 dark:text-zinc-300">
                    {{ __('Edit your profile, tune the visual theme, and keep every destination link current.') }}
                </flux:text>
            </div>

            <div class="flex flex-wrap gap-3 text-sm text-zinc-600 dark:text-zinc-300">
                <div class="rounded-full border border-white/70 bg-white/80 px-4 py-2 shadow-sm dark:border-white/10 dark:bg-white/5">
                    {{ trans_choice('{0} No links yet|{1} :count active link|[2,*] :count active links', $activeLinkCount, ['count' => $activeLinkCount]) }}
                </div>
                <div class="rounded-full border border-white/70 bg-white/80 px-4 py-2 shadow-sm dark:border-white/10 dark:bg-white/5">
                    {{ '@'.$username }}
                </div>
            </div>
        </div>

        <div
            class="rounded-[1.75rem] border border-white/60 p-5 shadow-lg shadow-sky-950/10 dark:border-white/10"
            style="{{ $previewStyles }}; background: linear-gradient(160deg, var(--yb-background), var(--yb-surface)); color: var(--yb-text);"
        >
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <flux:avatar circle="xl" size="xl" :name="$name" :initials="auth()->user()->initials()" />

                    <div class="min-w-0">
                        <p class="truncate text-lg font-semibold">{{ $name }}</p>
                        <p class="truncate text-sm opacity-75">{{ '@'.$username }}</p>
                    </div>
                </div>

                <p class="text-sm leading-6 opacity-90">
                    {{ $bio ?: __('Add a short bio so visitors know what to expect from your links.') }}
                </p>

                <div class="space-y-3">
                    @forelse ($previewLinks as $previewLink)
                        <x-yourbase.preview-link
                            :button-style="$buttonStyle"
                            :icon="$previewLink['icon'] ?: config('yourbase.links.default_icon', 'fa-solid fa-link')"
                            :title="$previewLink['title']"
                        />
                    @empty
                        <div class="rounded-2xl border border-dashed px-4 py-5 text-sm opacity-80">
                            {{ __('Your link preview will update as soon as you add a destination below.') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
