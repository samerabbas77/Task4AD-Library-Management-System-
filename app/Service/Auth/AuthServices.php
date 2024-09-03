<?php
namespace App\Service\Auth;

use App\Models\User;

class AuthServices
{
   /**
    * JWT Authentection
    * Register for a user 
    * @param mixed $validator
    * @return mixed|User|\Illuminate\Http\JsonResponse
    */
   public function registerService($validator)
   {
    if(!$validator){
        return response()->json($validator->errors()->toJson(), 400);
    }

    $user = new User;
    $user->name = $validator['name'];
    $user->email = $validator['email'];
    $user->password = bcrypt($validator['password']);
    $user->role = 'user';
    $user->save();

    return $user;
   } 
//.............................................................................................
//.............................................................................................
  /**
   * JWT Authentection
   *  login fpr user
   * @param mixed $credentials
   * @return bool|mixed|\Illuminate\Http\JsonResponse
   */
  public function loginService($credentials)
  {
    if (! $token = auth('api')->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    return $token;
  }
//.............................................................................................
//.............................................................................................
 public function logoutService()
 {
    auth('api')->logout();
 }
  

}