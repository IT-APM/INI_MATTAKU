<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Http;

class LogActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        // $user = auth()->user();
        // $location = Http::get("https://ipwho.is/{$request->ip()}")->json();
        // // $location = Http::get("http://ip-api.com/json/{$request->ip()}");
        // // if (!$location->successful()) {
        // //     $location = [
        // //         'status' => 'fail',
        // //         'message' => 'Failed get IP address locations',
        // //     ];
        // // }else{
        // //     $location = $location->json();
        // // }
        // activity()
        //     ->causedBy($user)
        //     ->withProperties([
        //         'method' => $request->method(),
        //         'url' => $request->fullUrl(),
        //         'ip' => $request->ip(),
        //         'location' => $location,
        //         'user_agent' => $request->userAgent(),
        //         'request' => $request->except(['password', 'password_confirmation']),
        //         'response_status' => $response->getStatusCode(),
        //     ])
        //     ->log($this->getActionName($request));
        return $response;
    }

    protected function getActionName($request)
    {
        if ($request->route()) {
            return $request->route()->getActionName();
        }

        return 'unknown_action';
    }
}
