<?php

use App\ExceptionHandling\FrontendExceptionHandler;
use App\Http\Controllers\DummyEntityController;
use App\Models\LinkedUser;
use App\Models\DummyEntity;
use App\Models\LinkingUri;
use App\Models\Settlement;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Settlements as SettlementsRepository;
use App\Repositories\Transactions as TransactionsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    [
        'middleware' => ['auth:sanctum','verified']
    ],
    function ()
    {
        Route::post('/batch', function () {
            $requests = request()->json();

            $output = array_map(function ($requestData) {
                $url = parse_url($requestData['url']);

                $query = [];
                if (isset($url['query'])) {
                    parse_str($url['query'], $query);
                }

                $request = Request::create(
                    $requestData['url'],
                    $requestData['type'],
                    $query,
                    content: $requestData['body']
                );
                $request->headers = request()->headers;
                $request->cookies = request()->cookies;


                return json_decode(
                    app()->handle($request)->getContent());
            }, $requests->all());

            return $output;
        });

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::resource('dummy-entities', DummyEntityController::class);

        Route::post(
            '/entity-attached-to-dummy/{dummyEntityId}/{realEntityId}',
            function ($dummyEntityId, $realEntityId) {
                $dummyEntity = DummyEntity::find($dummyEntityId);
                if ($dummyEntity->owner_user_id !== Auth::id()) return;
                if (!User::find(Auth::id())->allLinkedUsers()->some(
                    function ($user) use ($realEntityId) {
                        return $user->entities[0]->id === intval($realEntityId);
                    })) return;
                $dummyEntity->real_entity_id = $realEntityId;
                $dummyEntity->save();
            });

        Route::delete(
            '/entity-attached-to-dummy/{dummyEntityId}',
            function ($dummyEntityId) {
                $dummyEntity = DummyEntity::find($dummyEntityId);
                if ($dummyEntity->owner_user_id !== Auth::id()) return;
                $dummyEntity->real_entity_id = null;
                $dummyEntity->save();
            });

        Route::post('/linking-uri', function () {
            LinkingUri::createLinkingUriForAuthedUser();
            return back();
        });

        Route::delete('/linked-users/{id}', function (string $id) {
            LinkedUser
                ::where('user_a', Auth::id())
                ->where('user_b', $id)
                ->delete();
            LinkedUser
                ::where('user_a', $id)
                ->where('user_b', Auth::id())
                ->delete();
        });

        Route::get(
            '/settlement/{id}/ownership-candidates',
            function (int $settlement_id) {
                $settlement = Settlement::find($settlement_id);
                return response()->json(
                    SettlementsRepository::potentialOwnershipAssignees(
                        $settlement
                    )->map(fn($user) => [
                        'id' => $user->id,
                        'name' => $user->name,
                    ])->filter(
                        fn ($user) =>
                        str_starts_with(
                            strtolower($user['name']),
                            strtolower(request()->input('searchTerm')))
                    )->values()
                );
        });

        Route::get(
            '/settlement/{id}/admin-candidates',
            function (int $settlement_id) {
                $settlement = Settlement::find($settlement_id);
                return response()->json(
                    SettlementsRepository::potentialAdminGrantees(
                        $settlement
                )->map(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name
                ])->filter(
                    fn ($user) =>
                    str_starts_with(
                        strtolower($user['name']),
                        strtolower(request()->input('searchTerm')))
                )->values());
            }
        );

        Route::get(
            '/settlement/{id}/admins',
            function (int $settlement_id) {
                return response()->json(
                    SettlementsRepository::admins(
                        Settlement::find($settlement_id)
                    )->map(
                        fn ($admin) => [
                            'id' => $admin->user->id,
                            'name' => $admin->user->name,
                        ])
                );
            }
        );

        Route::post(
            '/settlement/{id}/assign-ownership',
            function (int $settlement_id) {
                $body = request()->json();
                SettlementsRepository::changeOwner(
                    Settlement::find($settlement_id),
                    User::find($body->getInt('user_id'))
                );
            });

        Route::post(
            '/settlement/{id}/grant-admin-privileges',
            function (int $settlement_id) {
                $body = request()->json();
                $settlement = Settlement::find($settlement_id);
                foreach ($body->get('new_admin_ids') as $id) {
                    SettlementsRepository::addAdmin(
                        $settlement, User::find($id));
                }
            });

        Route::post(
            '/settlement/{id}/revoke-admin-privileges',
            function (int $settlement_id) {
                $body = request()->json();
                $settlement = Settlement::find($settlement_id);
                foreach ($body->get('admin_ids') as $id) {
                    SettlementsRepository::removeAdmin(
                        $settlement, User::find($id));
                }
            }
        );

        Route::post(
            '/settlement/{id}/edit',
            function (int $settlement_id) {
                $attributes = [];

                $body = request()->json();

                $name = $body->get('name');

                if (!is_null($name)) {
                    $attributes['name'] = $name;
                }

                if (!empty($attributes)) {
                    SettlementsRepository::editSettlement(
                        Settlement::find($settlement_id),
                        $attributes);
                }
            }
        );

        Route::post(
            '/settlements',
            function () {
                $attributes = [];

                $body = request()->json();

                $name = $body->get('name');

                if (!is_null($name)) {
                    $attributes['name'] = $name;
                }

                SettlementsRepository::addSettlement($attributes);
            }
        );

        Route::delete(
            '/settlement/{id}',
            function (int $settlement_id) {
                SettlementsRepository::deleteSettlement(
                    Settlement::find($settlement_id)
                );
            }
        );

        Route::post(
            '/settlement/{id}/transactions',
            function (int $settlement_id) {
                return FrontendExceptionHandler::handle(
                    function () use ($settlement_id) {
                        $settlement =
                            Settlement::find($settlement_id);
                        TransactionsRepository::addTransaction(
                            $settlement, request()->json()->all()
                        );
                    }
                );
            }
        );

        Route::post(
            '/settlement/{settlement_id}/transaction/{transaction_id}/edit',
            function (int $settlement_id, int $transaction_id) {
                return FrontendExceptionHandler::handle(fn () =>
                    TransactionsRepository::editTransaction(
                        Settlement::find($settlement_id),
                        Transaction::find($transaction_id),
                        request()->json()->all()
                    )
                );
            }
        );

        Route::delete(
            '/settlement/{settlement_id}/transaction/{transaction_id}',
            function (int $settlement_id, int $transaction_id) {
                return FrontendExceptionHandler::handle(fn () =>
                    TransactionsRepository::deleteTransaction(
                        Settlement::find($settlement_id),
                        Transaction::find($transaction_id),
                    )
                );
            }
        );
    }
);
