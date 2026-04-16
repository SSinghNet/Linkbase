@props([
    'links' => [],
])

<x-yourbase.panel
    class="overflow-hidden border-zinc-900/5 bg-gradient-to-br from-zinc-50 via-white to-sky-50/70 dark:border-white/10 dark:from-zinc-900 dark:via-zinc-900 dark:to-sky-950/20"
    :eyebrow="__('Links Card')"
    :heading="__('Curate the link stack')"
    :subheading="__('Drag rows into order and keep each destination distinct.')"
>
    <x-slot:actions>
        <flux:button variant="primary" type="button" wire:click="addLink">
            {{ __('Add link') }}
        </flux:button>
    </x-slot:actions>

    <form wire:submit="saveLinks" class="space-y-4">
        <div wire:sort="sortLinks" class="space-y-4">
            @forelse ($links as $link)
                <x-yourbase.link-row :link="$link" />
            @empty
                <div class="rounded-[1.5rem] border border-dashed border-zinc-300 px-6 py-10 text-center dark:border-white/10">
                    <flux:heading size="md">{{ __('No links yet') }}</flux:heading>
                    <flux:text class="mt-2 text-zinc-600 dark:text-zinc-300">
                        {{ __('Add your first destination to start building the page preview.') }}
                    </flux:text>
                </div>
            @endforelse
        </div>

        <div class="flex items-center justify-between gap-4 pt-2">
            <span></span>
            <flux:button variant="primary" type="submit">
                {{ __('Save links') }}
            </flux:button>
        </div>
    </form>
</x-yourbase.panel>
