<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Database\ClientUser;
use DB;
use Storage;
use Hash;

class ClientUserController extends Controller
{
  public function index( ClientUser $clientUser )
  {
    return $clientUser->getClientId();
  }
}
