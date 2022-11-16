<?php

namespace App\Http\Controllers;

use App\Http\Resources\JSONAPIResource;
use App\Http\Requests\JSONAPIRequest;
use App\Models\User;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class APIAuthenticationController extends Controller
{

    /**
     * @var JSONAPIService
     */
    private $service;

    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    public function show(Request $request)
    {
        return new JSONAPIResource($request->user());
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JSONAPIRequest $request)
    {
        $credentials = [
            'email' => $request->input('data.attributes.email'),
            'password' => $request->input('data.attributes.password'),
        ];

        if ($request->has('data.attributes.name')) {
            $credentials = [
                'name' => $request->input('data.attributes.name'),
                'password' => $request->input('data.attributes.password'),
            ];
        }       

        if (Auth::attempt($credentials)) {

            $user = Auth::user();         
            
            $user->accessToken = $user->createToken('Password Access Client')->accessToken;

            return $this->service->fetchResource($user);
        }

        return $this->service->errorResponse("authentication","Invalid credentials");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        Auth::user()->token()->revoke();
        return response(null, 200);
    }

}
