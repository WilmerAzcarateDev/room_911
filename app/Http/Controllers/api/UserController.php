<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\UserRequest;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $start = request()->query('start');
        $end = request()->query('end');
        $document = request()->query('document');
        $production_departament_id = request()->query('production_departament_id');
        $users = User::with('login_attempts','production_departament')
            ->byProductionDepartament($production_departament_id)
            ->countAccess($start,$end);

        if($document){
            $users = $users->where('document', 'like', '%' . $document . '%');        
        }
        $users = $users->paginate();
        return response()->json($users);
    }

    public function latest_login_attempts(User $user)
    {
        $login_attempts = $user->login_attempts()->take(10)->latest()->get();

        return response()->json($login_attempts);
    }

    public function download_login_attempts(User $user)
    {   
        $login_attempts = $user->login_attempts()->get();

        $date = Carbon::now()->format('Y-m-d');

        return Pdf::view('pdf.login-history' , compact('login_attempts'))
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot
                    ->setNodeBinary(env('NODE_BINARY'))
                    ->setNpmBinary(env('NPM_BINARY'));
            })
            ->headerView('pdf.login-history-head' ,['user'=>$user,'date'=>$date])
            ->format(Format::A4)
            ->margins(1.5,1,1,1,Unit::Inch) 
            ->name('login-history'.$date.'pdf');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
