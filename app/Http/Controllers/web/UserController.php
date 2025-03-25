<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/employees/Index');
    }

    public function store(UserRequest $request){

        $data = $request->validated();
        
        $data['password'] = Hash::make($data['document']);

        $user = User::create($data);

        if($data['make_admin']){
            $user->assignRole('admin_room_911');
        }

        return redirect()->route('web.users.index');

    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($data['document'] !== $user->document) {
            $data['password'] = Hash::make($data['document']);
        }
    
        $user->update($data);
    
        if (isset($data['make_admin']) && $data['make_admin']) {
            if (!$user->hasRole('admin_room_911')) {
                $user->assignRole('admin_room_911');
            }
        } else {
            if ($user->hasRole('admin_room_911')) {
                $user->removeRole('admin_room_911');
            }
        }
    
        return redirect()->route('web.users.index');
    }

        /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('web.users.index');
    }
    
}
