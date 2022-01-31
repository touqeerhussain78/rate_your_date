<?php

namespace App\Http\Controllers\Api\User;

use Hash;
use Mail;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use App\Mail\ForgotCode;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\User\ContactUsRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UpdatePasswordRequest;

class AuthController extends BaseController
{

    /**
     * AuthController Register.
     *
     * @param RegisterRequest $request
     */
    public function register(RegisterRequest $request)
    {

        $password = Hash::make($request->password);
        $userCreated = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $password,
            'device_id' => $request->device_id,
            'device_type' => $request->device_type,
        ]);

        $roles = [$request->role_id];
        $userCreated->roles()->attach($roles);
        if ($userCreated) {
            return $this->sendResponse(null, __('User registered successfully.'));
        }
    }

    /**
     * AuthController Login.
     *
     * @param LoginRequest $request
     */

    public function login(LoginRequest $request)
    {

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {

            $token = auth()->user()->createToken('Personal Access token')->accessToken;


            return $this->sendResponse($token, __('Login successfully.'), 'access_token');
        }

        return $this->sendError(__('responseMessages.errorUserLogin'), false);
    }


    /**
     * AuthController logoutUser.
     *
     * @param LogoutRequest $request
     */
    public function logoutUser(Request $request)
    {

        if (true) {
            $user = $request->user();

            $user->token()->revoke();

            return $this->sendResponse(true, __('logout successfully.'));
        }

        return $this->sendError(__('responseMessages.errorLogout'), false);
    }

    /**
     * AuthController sendForgotCode.
     *
     * @param Request $request
     */
    public function sendForgotCode(Request $request)
    {
        if (User::where('email', $request->email)->count() > 0) {
            $request['code'] = $this->generatePIN(4);
            $user = User::where('email', $request->email)->update([
                'forgot_code' => $request['code']
            ]);

            Mail::to($request['email'])->send(new ForgotCode($request));
            return $this->sendResponse(null, __('Code sent successfully.'));
        }

        return $this->sendError(__('responseMessages.userNotFound'), false);
    }

    /**
     * AuthController verifiedForgotCode.
     *
     * @param Request $request
     */
    public function verifiedForgotCode(Request $request)
    {
        $user = User::where('forgot_code', $request->code)->first();

        if (!empty($user)) {
            $user->update(['forgot_code' => null]);
            return $this->sendResponse(null, __('Code verified successfully.'));
        }

        return $this->sendError(__('responseMessages.errorVerifiedForgotCode'), true);
    }

    /**
     * AuthController PasswordChange.
     *
     * @param UpdatePasswordRequest $request
     */
    public function forgotPasswordChange(UpdatePasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (isset($user)) {
            $user->update([
                'password' => bcrypt($request['password']),
                'forgot_code' => null
            ]);

            return $this->sendResponse(null, __('Password changed successfully.'));
        }

        return $this->sendError(__('responseMessages.errorForgotChangePassword'), false);
    }

    /**
     * AuthController getProfile.
     *
     * @param
     */
    public function getProfile()
    {

        $user = auth()->user();
        if ($user) {
            return $this->sendResponse(auth()->user(), __('User Accessed!'));
        }

        return $this->sendResponse(__('No User Found!'), false);
    }

    /**
     * AuthController updateProfile.
     *
     * @param UpdateProfileRequest $request
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $image = null;
        if(request('image')) {
            $file = request('image');
            $image = time() . '.' . $file->extension();

            $filetype = $file->getMimeType();
            $file->move(public_path("/assets/upload/user/"), $image);
        }

        $profileUpdated = User::where('id', auth()->user()->id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'age' => $request->age,
            'dob' => $request->dob,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'weight' => $request->weight,
            'height' => $request->height,
            'gender_interest' => $request->gender_interest,
            'radius' => $request->radius,
            'about' => $request->about,
            'image' => isset($image) ? $image : null,
        ]);

        if ($profileUpdated) {
            return $this->sendResponse(null, __('Profile updated successfully.'));
        }
        return $this->sendError(__('responseMessages.errorEditingProfile'), false);
    }

    /**
     * AuthController contactUs.
     *
     * @param ContactUsRequest $request
     */
    public function contactUs(ContactUsRequest $request)
    {

        contactUs::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'message' => $request->message,
        ]);
        return $this->sendResponse(null, __('Form submitted successfully.'));
    }

    /**
     * AuthController changePassword.
     *
     * @param ChangePasswordRequest $request
     */
    public function changePassword(ChangePasswordRequest $request)
    {

        if (Hash::check($request->current_password, auth()->user()->password)) {

            $user_updated = auth()->user()->update(['password' => Hash::make($request->password)]);

            if ($user_updated) {
                return $this->sendResponse(null, __('responseMessages.passwordUpdated'));
            }

            return $this->sendError(__('responseMessages.errorUpdatingPassword'), false);
        } else {
            return $this->sendError(__('responseMessages.oldPasswordMismatch'), false);
        }
    }

}
