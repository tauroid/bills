<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HomeController;
use App\Models\DummyEntity;
use App\Models\Entity;
use App\Models\EntityName;
use App\Models\Name;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DummyEntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return array_map(function ($dummyEntity) {
            return [
                'name' => $dummyEntity->entity->names[0]->name,
                'id' => $dummyEntity->id,
            ];
        }, User::find(Auth::id())->dummy_entities->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null(Auth::id())) return;

        DB::transaction(function () use ($request) {
            $name = new Name;
            $name->name = $request->name ?? '';
            $name->save();

            $entity = new Entity;
            $entity->save();

            $entityName = new EntityName;
            $entityName->entity = $entity->id;
            $entityName->name = $name->id;
            $entityName->save();

            $dummyEntity = new DummyEntity;
            $dummyEntity->entity_id = $entity->id;
            $dummyEntity->owner_user_id = Auth::id();
            $dummyEntity->save();
        });

        return redirect('/home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (is_null(Auth::id())) return;

        DB::transaction(function () use ($id) {
            $dummyEntity = DummyEntity::find($id);

            if (is_null($dummyEntity)) return;

            if (Auth::id() !== $dummyEntity->owner_user_id) return;

            $dummyEntity->delete();
        });

        return redirect('/home');
    }
}
