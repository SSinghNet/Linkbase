<?php

use App\Concerns\YourbaseValidationRules;
use App\Models\Link;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component {
    use YourbaseValidationRules;

    public string $name = '';

    public string $username = '';

    public string $bio = '';

    /**
     * @var array{accent: string, background: string, surface: string, text: string, button_style: string}
     */
    public array $theme = [];

    /**
     * @var array<string, array{id: int|null, local_key: string, title: string, url: string, icon: string, is_active: bool}>
     */
    public array $linkForms = [];

    /**
     * @var list<string>
     */
    public array $linkOrder = [];

    public function mount(): void
    {
        $user = $this->user();

        $this->name = $user->name;
        $this->username = $user->username;
        $this->bio = $user->bio ?? '';
        $this->theme = $this->resolveTheme($user->theme);

        $this->loadLinks();
    }

    public function updateProfile(): void
    {
        $user = $this->user();

        $validated = $this->validate($this->yourbaseProfileRules($user->id));

        $user->fill($validated);
        $user->save();

        Flux::toast(variant: 'success', text: __('Profile card updated.'));
    }

    public function applyThemePreset(string $preset): void
    {
        $theme = $this->themePresets()[$preset] ?? null;

        if ($theme === null) {
            return;
        }

        $this->theme = $theme;
    }

    public function saveTheme(): void
    {
        $validated = $this->validate($this->yourbaseThemeRules($this->themeButtonStyles()));

        $user = $this->user();
        $user->theme = $validated['theme'];
        $user->save();

        Flux::toast(variant: 'success', text: __('Theme saved.'));
    }

    public function addLink(): void
    {
        $link = $this->blankLink();

        $this->linkForms[$link['local_key']] = $link;
        $this->linkOrder[] = $link['local_key'];
    }

    public function removeLink(string $localKey): void
    {
        if (! array_key_exists($localKey, $this->linkForms)) {
            return;
        }

        unset($this->linkForms[$localKey]);

        $this->linkOrder = array_values(array_filter(
            $this->linkOrder,
            fn (string $key): bool => $key !== $localKey,
        ));
    }

    public function sortLinks(string $item, int $position): void
    {
        $currentIndex = array_search($item, $this->linkOrder, true);

        if ($currentIndex === false) {
            return;
        }

        $movedLink = $this->linkOrder[$currentIndex];

        array_splice($this->linkOrder, $currentIndex, 1);
        array_splice($this->linkOrder, $position, 0, [$movedLink]);

        $this->linkOrder = array_values($this->linkOrder);
    }

    public function saveLinks(): void
    {
        $validated = $this->validate($this->yourbaseLinkRules());

        $user = $this->user();
        $keptIds = [];

        foreach (array_values($validated['linkOrder']) as $position => $localKey) {
            if (! array_key_exists($localKey, $validated['linkForms'])) {
                continue;
            }

            $linkData = $validated['linkForms'][$localKey];

            $payload = [
                'title' => $linkData['title'],
                'url' => $linkData['url'],
                'icon' => $linkData['icon'] ?: null,
                'order' => $position + 1,
                'is_active' => (bool) $linkData['is_active'],
            ];

            if ($linkData['id']) {
                $link = $user->links()->findOrFail($linkData['id']);
                $link->update($payload);
            } else {
                $link = $user->links()->create($payload);
            }

            $keptIds[] = $link->id;
        }

        $query = $user->links();

        if ($keptIds === []) {
            $query->delete();
        } else {
            $query->whereNotIn('id', $keptIds)->delete();
        }

        $this->loadLinks();

        Flux::toast(variant: 'success', text: __('Links saved.'));
    }

    /**
     * @return array{accent: string, background: string, surface: string, text: string, button_style: string}
     */
    public function defaultTheme(): array
    {
        /** @var array{accent: string, background: string, surface: string, text: string, button_style: string} $theme */
        $theme = config('yourbase.theme.default');

        return $theme;
    }

    /**
     * @return array<string, array{accent: string, background: string, surface: string, text: string, button_style: string}>
     */
    public function themePresets(): array
    {
        /** @var array<string, array{accent: string, background: string, surface: string, text: string, button_style: string}> $presets */
        $presets = config('yourbase.theme.presets', []);

        return $presets;
    }

    /**
     * @return list<string>
     */
    public function themeButtonStyles(): array
    {
        /** @var list<string> $styles */
        $styles = config('yourbase.theme.button_styles', ['solid', 'soft', 'outline']);

        return $styles;
    }

    public function themePreviewStyles(): string
    {
        return implode('; ', [
            '--yb-accent: '.$this->theme['accent'],
            '--yb-background: '.$this->theme['background'],
            '--yb-surface: '.$this->theme['surface'],
            '--yb-text: '.$this->theme['text'],
        ]);
    }

    /**
     * @return list<array{id: int|null, local_key: string, title: string, url: string, icon: string, is_active: bool}>
     */
    public function orderedLinks(): array
    {
        return collect($this->linkOrder)
            ->map(fn (string $localKey): ?array => $this->linkForms[$localKey] ?? null)
            ->filter()
            ->values()
            ->all();
    }

    protected function loadLinks(): void
    {
        $links = $this->user()
            ->links()
            ->orderBy('order')
            ->get()
            ->map(fn (Link $link): array => [
                'id' => $link->id,
                'local_key' => 'saved-'.$link->id,
                'title' => $link->title,
                'url' => $link->url,
                'icon' => $link->icon ?? config('yourbase.links.default_icon', 'link'),
                'is_active' => $link->is_active,
            ])
            ->values();

        $this->linkForms = $links
            ->mapWithKeys(fn (array $link): array => [$link['local_key'] => $link])
            ->all();

        $this->linkOrder = $links
            ->pluck('local_key')
            ->all();
    }

    /**
     * @param  array<string, mixed>|null  $theme
     * @return array{accent: string, background: string, surface: string, text: string, button_style: string}
     */
    protected function resolveTheme(?array $theme): array
    {
        return array_merge($this->defaultTheme(), is_array($theme) ? $theme : []);
    }

    /**
     * @return array{id: int|null, local_key: string, title: string, url: string, icon: string, is_active: bool}
     */
    protected function blankLink(): array
    {
        return [
            'id' => null,
            'local_key' => 'new-'.Str::uuid(),
            'title' => '',
            'url' => '',
            'icon' => config('yourbase.links.default_icon', 'link'),
            'is_active' => true,
        ];
    }

    protected function user(): User
    {
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }
};
?>

@php
    $orderedLinks = $this->orderedLinks();
    $activeLinkCount = collect($orderedLinks)->where('is_active', true)->count();
    $previewLinks = collect($orderedLinks)->where('is_active', true)->take(3)->values()->all();
@endphp

<div class="flex w-full flex-1 flex-col gap-6">
    <x-yourbase.hero
        :active-link-count="$activeLinkCount"
        :bio="$bio"
        :button-style="$theme['button_style']"
        :name="$name"
        :preview-links="$previewLinks"
        :preview-styles="$this->themePreviewStyles()"
        :username="$username"
    />

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <x-yourbase.profile-form />
        <x-yourbase.theme-form :button-styles="$this->themeButtonStyles()" :theme-presets="$this->themePresets()" />
    </div>

    <x-yourbase.links-editor :links="$orderedLinks" />
</div>
