<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create User
     *@param Request $request
     *@return User
     */

    public function createUser(Request $request){

      


        try{
            // Validate / create User
            $validateUser = Validator::make($request->all(),
             [  
                'avatar' => 'required',
                'type' => 'required', // Since we have different type of logins
                'open_id' => 'required',
                'name' => 'required',
               
                'email'=> 'required',
                //'password' => 'required|min:6'
            ]  

        );
        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ] , 401);
        }
        //* This will have all the user filled values
        $validated = $validateUser->validate();

        $map = [];
        // type = [email , phone , google , facebook , apple]
        $map['type'] = $validated['type'];

        $map['open_id'] = $validated['open_id'];


        $user = User::where($map)->first();
        // return response()->json([
        //     'status' => true,
        //     'message' => 'user validated',
        //     'data' => $validated
        // ]);

        // Wheather user is already logedin or not
        //empty => user does not exist with any of the types
        // then save the user in the database
        if(empty($user->id)){
            // this certain user has never been in our database
            // we assign the user to the database

            // this token is user id
            $validated['token'] = md5(uniqid().rand(10000,99999));
            // user first time created
            $validated['created_at'] = Carbon::now();

            //encript password
           //! $validated['password'] = Hash::make($validated['password']);
            // returns the id of the row after saving
            $userID = User::insertGetId($validated);
            // we use this userID to show all the info
            // about the particular user 
            $userInfo = User::where('id' , '=' , $userID)->first();
            
            $accessToken = $userInfo->createToken(uniqid())->plainTextToken;
            
            $userInfo->access_token = $accessToken;

            User::where('id' , '=' , $userID)->update(['access_token' => $accessToken]);

            return response()->json([
                'code' => 200,
                'msg' => 'User Created Successfully',
                'data' => $userInfo
            ], 200);
        }

        $accessToken = $user->createToken(uniqid())->plainTextToken;
            
        $user->access_token = $accessToken;

        User::where('open_id' , '=' , $validated['open_id'])->update(['access_token' => $accessToken]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password)
        // ]);
        return response()->json([
            'code' => 200,
            'msg' => 'User LogedIn Successfully',
            'data' => $user
        ], 200);


        }catch(\Throwable $th){
            return response() -> json([
                'status' => false,
                'message' => $th->getMessage()
            ] , 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */

    // public function loginUser(Request $request){
    //     try{
    //         $validateUser = Validator::make($request->all(),
    //     [
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     if($validateUser->fails()){
    //         return response()->json([
    //             'status' => false,
    //             'message'=> 'validator error',
    //             'errors' => $validateUser->errors()
    //         ] , 401);
    //     }
    //     if(!Auth::attempt($request->only(['email' , 'password']))){
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Email & Password does not match with our record.',

    //         ],401);
    //     }
    //     $user = User::where('email' , $request->email)->first();
    //     return response()->json([
    //         'status' => true,
    //         'message' => "User Logged In Successfully",
    //         'token' => $user->createToken("API_TOKEN")->plainTextToken
    //     ],200);

    //     }catch (\Throwable $th){
    //         return response()->json([
    //             'status' => false,
    //             "message" => $th->getMessage()
    //         ],500);
    //     }
    // } 
}

