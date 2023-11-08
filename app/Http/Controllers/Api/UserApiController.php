<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFormRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $users = User::with(['role'])->orderBy('last_name', 'asc')->get();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return UserResource
     */
    public function store(UserFormRequest $userFormRequest)
    {
        $user = User::create([
            'name' => $userFormRequest->name,
            'first_name' => $userFormRequest->first_name,
            'last_name' => $userFormRequest->last_name,
            'password' => $userFormRequest->password,
            'uin' => $userFormRequest->uin,
            'email' => $userFormRequest->email,
            'netid' => $userFormRequest->netid
        ]);
        $user->syncRoles($userFormRequest->roles);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return UserResource
     */
    public function update(UserFormRequest $userFormRequest, $id)
    {
        $user = User::updateOrCreate(
            ['id' => $id],
            $userFormRequest->only(['first_name', 'last_name', 'email', 'name', 'netid', 'uin'])
        );

        $user->syncRoles($userFormRequest->roles);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
