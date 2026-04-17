<?php

namespace App\Http\Controllers;

use App\Jobs\RecordProfileView;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __invoke(Request $request, string $username): View
    {
        $user = User::where('username', $username)
            ->firstOrFail();

        $links = $user->activeLinks;

        RecordProfileView::dispatch(
            $user,
            $request->ip(),
            $request->userAgent() ?? '',
        );

        return view('profile', [
            'user' => $user,
            'links' => $links,
        ]);
    }
}
