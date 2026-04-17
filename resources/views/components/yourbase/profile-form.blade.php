<x-yourbase.panel :eyebrow="__('Profile Card')" :heading="__('Make the intro feel personal')">


    <form wire:submit="updateProfile" class="space-y-5">
        <div class="grid gap-4 md:grid-cols-2">
            <flux:input wire:model="name" :label="__('Display name')" type="text" required autocomplete="name" />
            <flux:input wire:model="username" :label="__('Handle')" type="text" required autocomplete="username" />
        </div>

        <flux:textarea wire:model="bio" :label="__('Bio')" rows="4" />

        <div class="flex items-center justify-between gap-4">
            <span></span>
            <flux:button variant="primary" type="submit">
                {{ __('Save profile') }}
            </flux:button>
        </div>
    </form>
</x-yourbase.panel>
