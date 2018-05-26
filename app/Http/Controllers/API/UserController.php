<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\Wallet; 
use App\Transaction; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller 
{
	/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $response['token'] =  $user->createToken('MyApp')->accessToken; 
            return response()->json(['success' => true, 'response' => $response], 200); 
        } 
        else{ 
            return response()->json(['success' => false, 'response' => 'Unauthorized or Invalid Credentials.'], 401); 
        } 
    }
	/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required|min:6', 
            'c_password' => 'required|same:password', 
        ]);
		if ($validator->fails()) { 
		    return response()->json(['success' => false, 'response'=>$validator->errors()], 422);            
		}
		$input = $request->all(); 
		$input['password'] = bcrypt($input['password']); 
		$user = User::create($input); 
		$response['token'] =  $user->createToken('MyApp')->accessToken; 
		$response['name'] =  $user->name;
		return response()->json(['success' => true, 'response' => $response], 200); 
	}
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function user() 
    { 
        // $user = Auth::user(); user this if in auth:api middleware after api authenticate
        $user_email = 'john@wallet.io';
        $wallet_transcations = Wallet::where('email',$user_email)->with(['transactions' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(3);
        }])->get();
        return response()->json(['success' => true, 'response' => $wallet_transcations], 200);
    } 

    /** 
     * user transcation api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function user_transcation(Request $request) 
    { 
        // $user = Auth::user(); user this if in auth:api middleware after api authenticate
        $validator = Validator::make($request->all(), [ 
            'type' => 'required|in:send,receive', 
            'currency' => 'required|in:sgd,usd', 
            'amount' => 'required|integer', 
            'fee' => 'integer', 
            'description' => '', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['success' => false, 'response'=> $validator->errors()], 422);            
        }

        $user_email = 'john@wallet.io';
        $wallet = Wallet::where('email',$user_email);

        if($wallet->count()){
            $wallet = $wallet->first();
            $transcation = new Transaction;
            $transcation->user_id = 2; // By System user
            $transcation->wallet_id   = $wallet->id;
            $transcation->trans_id    = str_random(15);
            $transcation->type        = $request->get('type');
            $transcation->amount      = $request->get('amount');
            $transcation->currency    = $request->get('currency');
            $transcation->fee         = $request->get('fee');

            if($request->get('type') == 'send'){
                $transcation->sender      = 'John';
                $transcation->receiver    = 'Zay';
                if($wallet->balance - $request->get('amount') < 0){
                    return response()->json(['success' => false, 'response' => 'You don\'t have sufficient fund in your wallet!'], 404);
                }
                $wallet->balance = $wallet->balance - $request->get('amount');
            }
            else{
                $transcation->sender      = 'Zay';
                $transcation->receiver    = 'John';
                $wallet->balance = $wallet->balance + $request->get('amount');
                $wallet->save();
            }

            $transcation->status      = 'completed';
            $transcation->description = $request->get('description');
            $transcation->save();
            $wallet->save();
            return response()->json(['success' => true, 'response' => 'Successfully make a transcation.'], 201);
        }

        return response()->json(['success' => true, 'response' => 'Couldn\'t find your wallet in our system!'], 404);
    } 

    /** 
     * user_create_wallet api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function user_create_wallet(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email|unique:wallets', 
            'amount' => 'required|integer', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['success' => false, 'response'=> $validator->errors()], 422);            
        }
        // $user = Auth::user();
        $user_id = 2;
        $wallet = new Wallet;
        $wallet->user_id = $user_id;
        $wallet->email   = $request->get('email');
        $wallet->balance = $request->get('amount');
        $wallet->save();
        return response()->json(['success' => true, 'response' => $wallet], 201); 
    } 

    /** 
     * user_delete_wallet api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function user_delete_wallet(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['success' => false, 'response'=> $validator->errors()], 422);            
        }
        
        Wallet::where('email',$request->get('email'))->with('transactions')->delete();

        return response()->json(['success' => true, 'response' => 'Succesfully deleted a wallet.'], 200); 
    }

    /** 
     * user_wallet api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function user_wallet_details(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['success' => false, 'response'=> $validator->errors()], 422);            
        }
        $wallet_transcations = Wallet::where('email',$request->get('email'))->with('transactions')->get();
        return response()->json(['success' => true, 'response' => $wallet_transcations], 200); 
    } 


}