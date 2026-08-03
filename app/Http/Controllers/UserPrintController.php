<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserPrintController extends Controller
{
    public function __invoke(User $user): View
    {
        //$user->load(['doctors', 'patients']);

        return view('users.print', [
            'user' => $user,
        ]);
    }
}