<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProtectAgainstSqlInjection
{
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        array_walk_recursive($input, function (&$value) {
            $value = Str::replaceArray('?', [''], $value);
            $value = DB::connection()->getPdo()->quote($value);
            $value = Str::replaceFirst("'", '', $value);
            $value = Str::replaceLast("'", '', $value);
        });
        
        //   foreach ($input as $key => $value) {
        //     if (is_string($value)) {
        //         // Use Laravel's DB facade to escape special characters and prevent SQL injection
        //         $input[$key] = DB::connection()->getPdo()->quote($value);
        //         // Remove any executable SQL statements from the input
        //         $input[$key] = Str::replaceArray('\\', ['\\\\'], $input[$key]);
        //         $input[$key] = Str::replaceArray('%', ['\\%'], $input[$key]);
        //       // $input[$key] = Str::replaceArray('', ['\\'], $input[$key]);
        //     }
        // }
        $request->replace($input);

        return $next($request);
    }
}
