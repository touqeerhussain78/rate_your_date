<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class FriendshipController extends BaseController
{
    
    public function befriend(Request $request, User $sender)
    {
        $user = $request->user();
        $user->befriend($sender);

        return $this->sendResponse(null, __('Friend request is sent.'));
    }

    public function unfriend(Request $request, User $sender)
    {
        $user = $request->user();
        $user->unfriend($sender);

        return $this->sendResponse(null, __('Friend is removed.'));
    }

    public function rejectRequest(Request $request, User $sender)
    {
        $user = $request->user();
        $user->denyFriendRequest($sender);

        return $this->sendResponse(null, __('Friend request is rejected.'));
    }   

    public function acceptRequest(Request $request, User $sender)
    {
        $user = $request->user();
        $user->acceptFriendRequest($sender);

        return $this->sendResponse(null, __('Friend request is accepted.'));
    }

    public function getFriendRequest(Request $request){
        $user = $request->user();

        return $this->sendResponse($user->getFriendRequests(), __('Friend request fetched successfully.'));
    }
}
