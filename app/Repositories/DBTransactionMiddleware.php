<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DBTransactionMiddleware {
    function __invoke(callable $next, ...$args): mixed {
        return DB::transaction(fn() => $next(...$args));
    }
}
