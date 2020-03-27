<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\HakAkses;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    private static $unauhorizedMessage = "You are not authorized to access this resource / execute the action!";
    private static $unauthenticatedMessage = "You are not logged in!";
    
    public function handle($request, Closure $next, ...$actions)
    {
      $user = Auth::user();
      
      if (empty($user)){
        return self::terminateRequest($request, self::$unauthenticatedMessage, 403);
      }

      if (!empty($actions)){
        if (!HakAkses::can($actions)){
          return self::terminateRequest($request, self::$unauhorizedMessage, 401);
        }
      }
      
      return $next($request);
    }
    
    private static function terminateRequest($request, $message, $code)
    {
      if ($request->ajax()){
        return response()->json([$message], $code);
      }

      $request->session()->flash('globalErrorMessages', [$message]);
      return redirect('/');
    }
}
