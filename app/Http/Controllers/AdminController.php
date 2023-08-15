<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    /**
     * @OA\Info(
     * title="Swager test",
     * version="1.0.0",
     * description="Description of your API",
     * @OA\Contact(
     * email="your@email.com"
     *  ),
     * )
     * 
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a user",
     *     tags={"Register"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string", format="password"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *   @OA\Response(
     *         response=500,
     *         description="Not found",
     *     ),
     * )
     */


    
public function register(Request $request)
{
    try {
        $validateData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $admin = new Admin;
        $admin->name = $validateData['name'];
        $admin->email = $validateData['email'];
        $admin->password = Hash::make($validateData['password']);
        $admin->save();

        return response()->json(['message' => 'User registered successfully'], 201);
    } catch (ValidationException $e) {
        return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        // Handle other exceptions
        return response()->json(['message' => 'An error occurred'], 500);
    }
}

/**
     * @OA\Post(
     ** path="/api/user-register",
     *   tags={"Login"},
     *   summary="Login here",
     *   operationId="login",
     *
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *       name="mobile_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
public function login(Request $request)
{
    $validator = $request->validate([
        'email' => 'email|required',
        'password' => 'required'
    ]);
    if (!auth()->attempt($validator)) {
        return response()->json(['error' => 'Unauthorised'], 401);
    } else {
        $success['token'] = auth()->user()->createToken('authToken')->accessToken;
        $success['user'] = auth()->user();
        return response()->json(['success' => $success])->setStatusCode(200);
    }
}



}