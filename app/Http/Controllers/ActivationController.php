<?php

namespace App\Http\Controllers;

use App\ActivationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    public function activation($token)
    {
        $activationCode = \App\Models\ActivationCode::whereCode($token)->first();
//        dd($activationCode);

        if(! $activationCode) {
            dd('not exist');

            return redirect('/');
        }

        if($activationCode->expire < Carbon::now()) {
            dd('expire');
            return redirect('/');
        }

        if($activationCode->used == true) {
            dd('used');
            return redirect('/');
        }

        $activationCode->user()->update([
            'active' => 1
        ]);

        $activationCode->update([
            'used' => true
        ]);

        auth()->loginUsingId($activationCode->user->id);
        return redirect('/');
    }

}
