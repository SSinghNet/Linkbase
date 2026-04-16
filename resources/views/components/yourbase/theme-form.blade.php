@props([
    'buttonStyles' => [],
    'themePresets' => [],
])

<x-yourbase.panel
    :eyebrow="__('Theme Card')"
    :heading="__('Set your colors and button feel')"
>
    <x-slot:actions>
        <flux:button.group>
            @foreach ($themePresets as $preset => $palette)
                <flux:button type="button" variant="ghost" wire:click="applyThemePreset('{{ $preset }}')">
                    {{ ucfirst($preset) }}
                </flux:button>
            @endforeach
        </flux:button.group>
    </x-slot:actions>

    <form wire:submit="saveTheme" class="space-y-5">
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ([
                'accent' => __('Accent'),
                'background' => __('Background'),
                'surface' => __('Surface'),
                'text' => __('Text'),
            ] as $key => $label)
                <label class="rounded-2xl border border-zinc-200/80 bg-zinc-50 p-4 dark:border-white/10 dark:bg-white/5">
                    <span class="mb-3 block text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ $label }}</span>
                    <div class="flex items-center gap-3">
                        <input
                            type="color"
                            wire:model.live="theme.{{ $key }}"
                            class="h-12 w-14 cursor-pointer rounded-xl border-0 bg-transparent p-0"
                        />
                        <flux:input wire:model.live="theme.{{ $key }}" type="text" />
                    </div>
                </label>
            @endforeach
        </div>

        <flux:radio.group wire:model="theme.button_style" variant="segmented" :label="__('Button style')">
            @foreach ($buttonStyles as $buttonStyle)
                <flux:radio :value="$buttonStyle">{{ str($buttonStyle)->title() }}</flux:radio>
            @endforeach
        </flux:radio.group>

        <div class="flex items-center justify-between gap-4">
            <span></span>
            <flux:button variant="primary" type="submit">
                {{ __('Save theme') }}
            </flux:button>
        </div>
    </form>
</x-yourbase.panel>
