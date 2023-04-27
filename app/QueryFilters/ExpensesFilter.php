<?php

namespace App\QueryFilters;
use Closure;

class ExpensesFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('orderByAmount')) {
            return $next($request);
        }

        return $request->orderBy('amount', 'DESC');
    }
}
