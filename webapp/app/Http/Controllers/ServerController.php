<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServerResource;
use App\Models\Peer;
use App\Models\Subnet;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function server(Subnet $subnet)
    {
        return $subnet->config();
    }

    public function servers()
    {
        return ServerResource::collection(Subnet::all());
    }
}
