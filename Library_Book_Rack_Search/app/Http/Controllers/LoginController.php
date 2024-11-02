<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $is_using_email = false;

        if(strpos($request->usernameOrEmail, '@') !== false){
            $is_using_email = true;
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'usernameOrEmail' => 'required', // username or email
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = [
            'password' => $request->password,
        ];
    
        if($is_using_email){
            $credentials['email'] = $request->usernameOrEmail;
        }else{
            $credentials['name'] = $request->usernameOrEmail;
        }

        if(Auth::attempt($credentials)){
            // Authentication passed...
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid login information'], 401);
    }

    public function logout(Request $request)
    {
        // Log out the user
        Auth::logout();
    
        // Invalidate the session
        $request->session()->invalidate();
    
        // Regenerate the CSRF token
        $request->session()->regenerateToken();
    
        return response()->json(['success' => true]);
    }
}
