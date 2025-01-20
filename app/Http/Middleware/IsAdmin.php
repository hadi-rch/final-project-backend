<?php

namespace App\Http\Middleware;

use App\Models\Roles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        // dd($user);

        $roleAdmin = Roles::where('name','admin')->first();

        // dd($roleAdmin);

        if($user->role_id != $roleAdmin->id){
            return response([
                "message" => "halaman admin tidak dapat di akses"
            ],404);
        }
        return $next($request);
    }
}
