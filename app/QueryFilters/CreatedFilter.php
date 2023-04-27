<?php

namespace App\QueryFilters;

class CreatedFilter
{
    public function handle($request, $next)
    {
        if (!request()->has('orderByCreated')) {
            return $next($request);
        }

        return $request->orderBy('created_at', 'DESC');
    }
}
