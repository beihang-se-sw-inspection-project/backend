<?php
namespace App\Http\Controllers;

use App\Http\Resources\JSONAPIResource;
use App\Http\Requests\JSONAPIRequest;
use App\Models\PasswordResetCode;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\PasswordHasBeenReset;
use Illuminate\Support\Facades\Mail;



class APIForgotPasswordController extends Controller
{

    /**
     * @var JSONAPIService
     */
    private $service;

    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    public function show($passwordresetcode)
    {
        return $this->service->fetchResource(PasswordResetCode::class, $passwordresetcode, 'passwordresetcode');
    }

    public function store(JSONAPIRequest $request)
    {       
        $attributes = $request->input('data.attributes');

        // Delete all old code that user send before.
        PasswordResetCode::where('email', $attributes['email'])->delete();

        return $this->service->createResource(PasswordResetCode::class, $attributes);
    }

    public function update(JSONAPIRequest $request, PasswordResetCode $passwordresetcode)
    {
        // check if it has not expired: the time is 15 minutes
        if ($passwordresetcode->created_at < now()->subMinutes(15)) {

            return $this->service->errorResponse("Password Reset", "Reset Code Expired");
        }

        $user = User::where('email', $passwordresetcode->email)->first();
            
        $user->accessToken = $user->createToken('Password Access Client')->accessToken;

        // Send email to user
        Mail::to($user->email)->send(new PasswordHasBeenReset($user->name));

        $passwordresetcode->delete();
        
        return $this->service->fetchResource($user);
    }

    
}