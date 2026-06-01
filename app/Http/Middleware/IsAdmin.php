<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
   public function handle(Request $request, Closure $next)
   {
    if($request->user()->role !== 'admin'){
        return response()->json([
            'message' => 'Akses Ditolak!!'
        ], 403);
    }

    return $next($request);
   }
}
