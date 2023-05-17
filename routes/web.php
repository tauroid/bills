<?php

use App\Models\LinkedUser;
use App\Models\LinkingUri;
use App\Models\Settlement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\CommonData;
use App\Http\Controllers\HomeController;
use App\Models\User;
use App\Repositories\Settlements as SettlementRepository;
use App\Repositories\Settlements\UserCantViewSettlementException;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth','verified']], function () {
    Route::redirect('/', '/home');

    Route::get('/home', [HomeController::class,'show']);

    Route::get('/settlements', function () {
        if (User::find(Auth::id())->settlements->isNotEmpty()) {
            return Inertia::render('SettlementsOwner', CommonData::get());
        } else {
            return Inertia::render('Settlements', CommonData::get());
        }
    });

    Route::get('/settlement/{id}', function (int $id) {
        $settlement = Settlement::find($id);
        if (is_null($settlement)) {
            abort(404);
        }
        try {
            return SettlementRepository::viewSettlement(
                $settlement
            );
        } catch (UserCantViewSettlementException) {
            abort(404);
        }
    });

    Route::get('/link/{uri}', function (string $uri) {
        DB::transaction(function () use ($uri) {
            $linkingUri = LinkingUri::where('uri', $uri)->get()->first();
            $user = User::find(Auth::id());
            if ($linkingUri
                && $user->id !== $linkingUri->target_user
                && !$user->allLinkedUsers()->some(
                    fn ($user) =>
                    $user->id === $linkingUri->target_user
                )
            ) {
                $linkedUser = new LinkedUser;
                $linkedUser->user_a = Auth::id();
                $linkedUser->user_b = $linkingUri->target_user;
                $linkedUser->save();
            }
        });
        return redirect('/home');
    })->where('uri', '.*');
});

Route::redirect('/verification-failed', '/login')
    ->name('verification.notice');

Route::get('/scratch', function () {
    //return TransactionRepository::addTransaction(Settlement::find(1), []);
    return Inertia::render('Scratch');
});
