<?php

namespace App\Http\Controllers;

use App\Jobs\RecordLinkClick;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LinkRedirectController extends Controller
{
    public function redirect(Request $request, string $username, int $linkId): RedirectResponse
    {
        $user = User::where('username', $username)->firstOrFail();

        $link = $user->links()
            ->where('id', $linkId)
            ->where('is_active', true)
            ->firstOrFail();

        RecordLinkClick::dispatch(
            $link,
            $request->ip(),
            $request->userAgent() ?? '',
        );

        return redirect()->away($link->url);
    }
}
