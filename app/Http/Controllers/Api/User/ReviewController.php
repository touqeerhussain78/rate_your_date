<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends BaseController
{
    
    public function review()
    {
        

        return $this->sendResponse(null, __('Review done successfully.'));
    }
}
