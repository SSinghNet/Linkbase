@props([
    'buttonStyle' => 'solid',
    'defaultIcon' => 'fa-solid fa-link',
    'user',
    'link',
])

<a
    {{-- href="{{ $link->url }}" --}}
    href="{{ route('link.redirect', ['username' => $user->username, 'linkId' => $link->id]) }}"
    target="_blank"
    rel="noreferrer noopener"
    {{ $attributes->class('block transition-transform duration-200 hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-transparent') }}
>
    <x-yourbase.preview-link
        :button-style="$buttonStyle"
        :icon="$link->icon ?: $defaultIcon"
        :title="$link->title"
        class="w-full"
    />
</a>
