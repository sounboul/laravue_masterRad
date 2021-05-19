<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Cache;

class VerifyController extends Controller
{
    public function verify(Request $request)
    {
        $link = user::where('remember_token', $request->link)->first();

        if ($link !== null) {
            $link->email_verified_at = now();
            $link->active = "active";
            $link->update();
            Cache::put('message', 'ok');
            return redirect()->route('laravue');
        }
        else
        {
            Cache::put('message', 'not ok');
            return redirect()->route('laravue');
        }
    }
}
