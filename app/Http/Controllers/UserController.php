<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Validator;
class UserController extends Controller
{
    public $successStatus =200;
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [ 

            'email' => 'required|email|unique:users', 
            'password' => 'required|min:8', 
            'password_confirmation' => 'required|same:password', 
        ]);
    
        if ($validator->fails()) { 
            
            return response()->json(['Status'=>false,'error'=>$validator->errors()->first()], 401);            
        }

         $user = new User([
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);      

          $user->save();        
          return response()->json(['Status'=>True , 'message' => 'Successfully created user!','response'=>$user], 201);
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);       

         $credentials = request(['email', 'password']);        

         if(!Auth::attempt($credentials))
          
          	 return response()->json(['Status'=>false , 'message' => 'Unauthorized'], 401);   

	            
	        $user = $request->user();       
	         $tokenResult = $user->createToken('Personal Access Token');
	        $token = $tokenResult->token;       
         
         if ($request->remember_me)
            
            $token->expires_at = Carbon::now()->addWeeks(1);       
             $token->save();        
             
             return response()->json(['response'=>['status'=>True,'access_token' => $tokenResult->accessToken, 'token_type' => 'Bearer',
     		
     		'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()]]);

    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();        
        return response()->json(['message' => 'Successfully logged out' ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
               $user = Auth::user();
                return response()->json(['success' => $user], $this->successStatus);
    }
}