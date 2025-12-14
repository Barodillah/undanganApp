<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrController extends Controller
{
    public function generate(Request $request)
    {
        $code = $request->query('code', 'TIDAKVALID');
        return view('qr.generate', compact('code'));
    }
}
