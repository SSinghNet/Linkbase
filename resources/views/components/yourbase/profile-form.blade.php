<x-yourbase.panel :eyebrow="__('Profile Card')" :heading="__('Make the intro feel personal')">
    <x-slot:actions>
        <div class="rounded-2xl bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700 dark:bg-sky-500/15 dark:text-sky-300">
            {{ __('Visible first') }}
        </div>
    </x-slot:actions>

    <form wire:submit="updateProfile" class="space-y-5">
        <div class="grid gap-4 md:grid-cols-2">
            <flux:input wire:model="name" :label="__('Display name')" type="text" required autocomplete="name" />
            <flux:input wire:model="username" :label="__('Handle')" type="text" required autocomplete="username" />
        </div>

        <flux:textarea wire:model="bio" :label="__('Bio')" rows="4" />

        <div class="flex items-center justify-between gap-4">
            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Use this space for a quick hook, role, or call to action.') }}
            </flux:text>

            <flux:button variant="primary" type="submit">
                {{ __('Save profile') }}
            </flux:button>
        </div>
    </form>
</x-yourbase.panel>
