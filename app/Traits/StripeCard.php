<?php

namespace App\Traits;
use Illuminate\Http\Request;
use App\Models\UserCardDetail;
use Session;
use Stripe;
use DB;

trait StripeCard
{
	public function addCard($data){

		try {

	        $expiry = explode('/', $data['expiry']);
	        Stripe\Stripe::setApiKey('sk_test_51J68hMFM5kkzQuYJZOSh90ckOpRG5ZGOF9exy1qlnaqy8PKrjUKxxn8AkHmg9ABzbBJKniQbklsB4Pr6xo2lB6a100x0ubkadW');

	        $token = \Stripe\Token::create([
			 'card' => [
			   'number' 	=> $data['number'],
			   'exp_month' => $expiry[0],
			   'exp_year' => $expiry[1],
			   'cvc'	 => $data['cvc'],

			 ]
			]);

			  $customer = \Stripe\Customer::create(array(
			    'email'  =>  $data['email'],
			    'source' => $token
			  ));

		    $user_card_detail                 = new UserCardDetail;
		    $user_card_detail->user_id        = $data['user_id'];
		    $user_card_detail->name           = $data['name'];
		    $user_card_detail->stripe_card_id = $token->card['id'];
		    $user_card_detail->card_number 	  = $data['number'];
		    $user_card_detail->expiry 	  	  = $data['expiry'];
		    $user_card_detail->cvv 	  	      = $data['cvc'];
		    $user_card_detail->email 	  	  = $data['email'];
		    $user_card_detail->card_token     = $token->id;
		    $user_card_detail->brand          = $token->card['brand'];
		    $user_card_detail->country        = $token->card['country'];
		    $user_card_detail->last           = $token->card['last4'];
		    $user_card_detail->raw            = $token->card;

		    $user_card_detail->save();
			return true;

		}catch (\Exception $e)
		{
	        return 	$e->getMessage();
	    }
	}

	public function updateCard($data){

		try {

	        $expiry = explode('/', $data['expiry']);
	        Stripe\Stripe::setApiKey('sk_test_51J68hMFM5kkzQuYJZOSh90ckOpRG5ZGOF9exy1qlnaqy8PKrjUKxxn8AkHmg9ABzbBJKniQbklsB4Pr6xo2lB6a100x0ubkadW');

	        $token = \Stripe\Token::create([
			 'card' => [
			   'number' 	=> $data['number'],
			   'exp_month' => $expiry[0],
			   'exp_year' => $expiry[1],
			   'cvc'	 => $data['cvc'],

			 ]
			]);

			  $customer = \Stripe\Customer::create(array(
			    'email'  =>  $data['email'],
			    'source' => $token
			  ));

			  $user_card_detail = UserCardDetail::whereUserId($data['user_id'])->first();
			  if ($user_card_detail) {

				  	$user_card_detail->update([
			    	'name' => $data['name'],
			    	'stripe_card_id' => $token->card['id'],
			    	'card_number' => $data['number'],
			    	'expiry' => $data['expiry'],
			    	'cvv' => $data['cvc'],
			    	'email' => $data['email'],
			    	'card_token' => $token->id,
			    	'brand' => $token->card['brand'],
			    	'country' => $token->card['country'],
			    	'last' => $token->card['last4'],
			    	'raw' => $token->card,
			    ]);

			  } else {

				  	UserCardDetail::create([
				  	'user_id' => $data['user_id'],
			    	'name' => $data['name'],
			    	'stripe_card_id' => $token->card['id'],
			    	'card_number' => $data['number'],
			    	'expiry' => $data['expiry'],
			    	'cvv' => $data['cvc'],
			    	'email' => $data['email'],
			    	'card_token' => $token->id,
			    	'brand' => $token->card['brand'],
			    	'country' => $token->card['country'],
			    	'last' => $token->card['last4'],
			    	'raw' => $token->card,
			    ]);

			  }

		    // UserCardDetail::updateOrCreate([
		    // 	'user_id' => $data['user_id'],
		    // ],[
		    // 	'name' => $data['name'],
		    // 	'stripe_card_id' => $token->card['id'],
		    // 	'card_number' => $data['number'],
		    // 	'expiry' => $data['expiry'],
		    // 	'cvv' => $data['cvc'],
		    // 	'email' => $data['email'],
		    // 	'card_token' => $token->id,
		    // 	'brand' => $token->card['brand'],
		    // 	'country' => $token->card['country'],
		    // 	'last' => $token->card['last4'],
		    // 	'raw' => $token->card,
		    // ]);

		    // $user_card_detail->user_id        = $data['user_id'];
		    // $user_card_detail->name           = $data['name'];
		    // $user_card_detail->stripe_card_id = $token->card['id'];
		    // $user_card_detail->card_number 	  = $data['number'];
		    // $user_card_detail->expiry 	  	  = $data['expiry'];
		    // $user_card_detail->cvv 	  	      = $data['cvc'];
		    // $user_card_detail->email 	  	  = $data['email'];
		    // $user_card_detail->card_token     = $token->id;
		    // $user_card_detail->brand          = $token->card['brand'];
		    // $user_card_detail->country        = $token->card['country'];
		    // $user_card_detail->last           = $token->card['last4'];
		    // $user_card_detail->raw            = $token->card;
		    // $user_card_detail->save();
			return true;

		}catch (\Exception $e)
		{
	        return 	$e->getMessage();
	    }
	}


}
