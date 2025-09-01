<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Trait\JsonApiResponseTrait;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    use JsonApiResponseTrait;

/**
 * @OA\Post(
 *     path="/api/v1/login",
 *     summary="Login user",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"email","password"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 example="user@example.com",
 *                 description="User email"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 example="secret123",
 *                 description="User password"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful login",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="token", type="string", example="jwt-token-here")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Invalid credentials")
 * )
 */
    public function login(LoginRequest $request){

try{
        $credentials = $request->only('email', 'password');
         
       if(Auth::attempt($credentials)){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['name'] =  $user->name;
   
            return $this->successResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->errorResponse('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }catch(Exception $e){
        if ($e instanceof QueryException) {
                return $this->errorResponse('Something went wrong.');
            }
            return $this->errorResponse($e->getMessage());
    }
    }
    public function test(){
        if(Auth::attempt(['email' => 'superadmin@example.com', 'password' => 'password'])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            // $token = $user->createToken('My Token')->accessToken;
 
// Creating a token with scopes...
// $token = $user->createToken('My Token', ['user:read', 'orders:create'])->accessToken;
 
// Creating a token with all scopes...
// $token = $user->createToken('My Token', ['*'])->accessToken;
// $token = $user->createToken('My Token', ['*'])->accessToken;

            $success['name'] =  $user->name;
   
            return $this->successResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->errorResponse('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}
