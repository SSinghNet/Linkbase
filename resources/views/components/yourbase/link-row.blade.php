@props([
    'link' => [],
])

<div
    wire:key="link-row-{{ $link['local_key'] }}"
    wire:sort:item="{{ $link['local_key'] }}"
    class="rounded-[1.75rem] border border-zinc-200/80 bg-white p-4 shadow-sm transition hover:border-zinc-300 dark:border-white/10 dark:bg-zinc-950/40 dark:hover:border-white/20"
>
    <div class="flex flex-col items-center xl:items-end justify-center gap-4 xl:flex-row">
        <button
            type="button"
            wire:sort:handle
            class="flex h-11 w-11 m-0 shrink-0 items-center justify-center rounded-2xl border border-zinc-200 bg-zinc-50 text-zinc-400 transition hover:border-zinc-300 hover:text-zinc-700 dark:border-white/10 dark:bg-white/5 dark:text-zinc-500 dark:hover:text-zinc-200"
            aria-label="{{ __('Drag to reorder link') }}"
        >
            <flux:icon icon="bars-3" class="size-5" />
        </button>

        <div class="grid min-w-0 flex-1 gap-4 lg:grid-cols-[minmax(210px,0.9fr)_minmax(0,1fr)_minmax(0,1.3fr)_auto]">
            <div class="items-center">
                <div class="flex gap-0 items-end">
                    <flux:input
                        wire:key="icon-{{ $link['local_key'] }}"
                        wire:model.live.debounce.250ms="linkForms.{{ $link['local_key'] }}.icon"
                        :label="__('Font Awesome icon')"
                        type="text"
                        variant="outline"
                    />
                    <div class="flex h-11 min-w-11 items-center justify-center bg-transparent text-zinc-700 dark:text-zinc-100">
                        <i class="{{ $link['icon'] }}"></i>
                    </div>
                </div>
            </div>
            <div>
                <flux:input
                    wire:key="title-{{ $link['local_key'] }}"
                    wire:model.blur="linkForms.{{ $link['local_key'] }}.title"
                    :label="__('Title')"
                 type="text"
                />
            </div>

            <div>
                <flux:input
                    wire:key="url-{{ $link['local_key'] }}"
                    wire:model.blur="linkForms.{{ $link['local_key'] }}.url"
                    :label="__('URL')"
                    type="url"
                />

            </div>
            
            <div class="flex items-center md:items-end justify-center gap-3">

                <label class="flex items-center gap-2 rounded-xl border border-zinc-200 bg-zinc-50 px-3 py-2 text-sm dark:border-white/10 dark:bg-white/5">
                    <flux:checkbox wire:model.live="linkForms.{{ $link['local_key'] }}.is_active" />
                    <span>{{ __('Active') }}</span>
                </label>

                <flux:button type="button" variant="ghost" wire:click="removeLink('{{ $link['local_key'] }}')">
                    {{ __('X') }}
                </flux:button>

            
            </div>
        </div>
    </div>
</div>
