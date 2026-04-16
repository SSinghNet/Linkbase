<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait YourbaseValidationRules
{
    /**
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function yourbaseProfileRules(?int $userId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                $userId === null
                    ? Rule::unique(User::class, 'username')
                    : Rule::unique(User::class, 'username')->ignore($userId),
            ],
            'bio' => ['nullable', 'string', 'max:280'],
        ];
    }

    /**
     * @param  list<string>  $buttonStyles
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function yourbaseThemeRules(array $buttonStyles): array
    {
        return [
            'theme.accent' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme.background' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme.surface' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme.text' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme.button_style' => ['required', Rule::in($buttonStyles)],
        ];
    }

    /**
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function yourbaseLinkRules(): array
    {
        return [
            'linkForms' => ['array'],
            'linkForms.*.id' => ['nullable', 'integer'],
            'linkForms.*.local_key' => ['required', 'string'],
            'linkForms.*.title' => ['required', 'string', 'max:80'],
            'linkForms.*.url' => ['required', 'url', 'max:255'],
            'linkForms.*.icon' => ['required', 'string', 'max:255'],
            'linkForms.*.is_active' => ['boolean'],
            'linkOrder' => ['array'],
            'linkOrder.*' => ['string'],
        ];
    }
}
