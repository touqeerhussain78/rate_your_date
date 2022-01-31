<?php

namespace App\Traits;
use Illuminate\Http\booking;
use Stripe;
use DB;

trait StripePayment
{
    public function stripe($card_number, $expiry_date, $cvv, $amount)
    {
    	try {
			$expiry = explode('/', $expiry_date);
       		\Stripe\Stripe::setApiKey('sk_test_51J68hMFM5kkzQuYJZOSh90ckOpRG5ZGOF9exy1qlnaqy8PKrjUKxxn8AkHmg9ABzbBJKniQbklsB4Pr6xo2lB6a100x0ubkadW');
            $email =  auth()->user()->email;

            try {
            $response = \Stripe\Token::create([
                'card' => [
                'number' => $card_number,
                'exp_month' => $expiry[0],
                'exp_year' => $expiry[1],
                'cvc' => $cvv
            ]
            ]);
            $err['status']  = true;
            } catch(\Stripe\Error\Card $e) {
                // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err['error']  = $body['error']['message'];
            $err['status']  = false;
            	throw new \Exception($e->getMessage(), 1);
            } catch (\Stripe\Error\RateLimit $e) {
            $body = $e->getJsonBody();
            $err['error']  = $body['error']['message'];
            $err['status']  = false;
            throw new \Exception($e->getMessage(), 1);
                // Too many requests made to the API too quickly
            } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err['error']  = $body['error']['message'];
            $err['status']  = false;
            throw new \Exception($e->getMessage(), 1);
                // Invalid parameters were supplied to Stripe's API
            } catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err['error']  = $body['error']['message'];
            $err['status']  = false;
            throw new \Exception($e->getMessage(), 1);
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
            } catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err['error']  = $body['error']['message'];
            $err['status']  = false;
			throw new \Exception($e->getMessage(), 1);
                // Network communication with Stripe failed
            } catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err['error']  = $body['error']['message'];
            $err['status']  = false;
                // Display a very generic error to the user, and maybe send
                // yourself an email
            } catch (Exception $e){
            $body = $e->getJsonBody();
            $err['error']  = $body['error']['message'];
            $err['status']  = false;
                // Something else happened, completely unrelated to Stripe
            }
            if (!$err['status']) {
                return response()->json(['message' => $body['error']['message'] ], 400);
            }

            $customer = \Stripe\Customer::create(array(
                  'email' => $email,
                  'card'  => $response->id,
              ));

			$charge = Stripe\Charge::create ([
		        'customer' => $customer->id,
				'amount'   => round($amount * 100),
				'currency' => 'usd',
			]);

		    return response()->json([
		        'status'=>'success',
		        'customer' => $charge->id,
		        'data'	=>	$charge
		    ], 200);

		} catch(\Stripe_CardError $e) {

			 $error = TRUE;
			 $insertData = [
			   'error'      => $e,
			   'type' 		=>'stripe'
			 ];
		//	 DB::table('payment_errors')->insert($insertData);
				throw new \Exception($e->getMessage(), 1);

			 /*return response()->json([
	            'status'=>'error',
	            'error'	=>	$e->getMessage()
	        ], 422);*/
			} catch (\Stripe_InvalidbookingError $e) {
			 $error = TRUE;
			 $insertData = [

			   'error'      => $e,
			   'type' 		=>'stripe'
			 ];
			// DB::table('payment_errors')->insert($insertData);
			 return response()->json([
	            'status'=>'error',
	            'error'	=>	$e->getMessage()
	        ], 422);
			 // Invalid parameters were supplied to Stripe's API
			} catch (\Stripe_AuthenticationError $e) {
			 $error = TRUE;
			 $insertData = [

			   'error'      => $e,
			   'type' 		=>'stripe'
			 ];
		//	DB::table('payment_errors')->insert($insertData);
				throw new \Exception($e->getMessage(), 1);
			/*return response()->json([
	            'status'=>'error',
	            'error'	=>	$e->getMessage()
	        ], 422);*/
			 // Authentication with Stripe's API failed
			 // (maybe you changed API keys recently)
			} catch (\Stripe_ApiConnectionError $e) {
			 $error = TRUE;
			 $insertData = [
			   'error'      => $e,
			   'type' 		=>'stripe'
			 ];
		//	 DB::table('payment_errors')->insert($insertData);
				throw new \Exception($e->getMessage(), 1);
			 /*return response()->json([
	            'status'=>'error',
	            'error'	=>	$e->getMessage()
	        ], 422);*/
			 // Network communication with Stripe failed
			} catch (\Stripe_Error $e) {
			 $error = TRUE;
			 $insertData = [

			   'error'      => $e,
			   'type' 		=>'stripe'
			 ];
			// DB::table('payment_errors')->insert($insertData);
				throw new \Exception($e->getMessage(), 1);
			 /*return response()->json([
	            'status'=>'error',
	            'error'	=>	$e->getMessage()
	        ], 422);*/
			 // Display a very generic error to the user, and maybe send
			 // yourself an email
			} catch (\Exception $e) {
			 $error = TRUE;
			 $insertData = [
			   'error'      => $e,
			   'type' 		=>'stripe'
			 ];
			// DB::table('payment_errors')->insert($insertData);
				throw new \Exception($e->getMessage(), 1);
			 /*return response()->json([
	            'status'=>'error',
	            'error'	=>	$e->getMessage()
	        ], 422);*/
			}
			catch (\Exception $e)
			{
				throw new \Exception($e->getMessage(), 1);

		        /*return response()->json([
		            'status'=>'error',
		            'error'	=>	$e->getMessage()
		        ], 422);*/
	        }

    }
}
