<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GetStream\StreamChat\Client;
use App\Message;

class MessagesController extends Controller
{
    protected $client;
    //
    public function __construct()
    {
        $this->client =  new Client(
            getenv("MIX_STREAM_API_KEY"),
            getenv("MIX_STREAM_API_SECRET")
        );
    }

    public function generateToken(Request $request)
    {
        return response()->json([
            'token' => $this->client->createToken($request->input('name'))
        ], 200);
    }

    public function getChannel(Request $request)
    {
        // $from = $request->input('from');
        // $to = $request->input('to');

        // $from_username = $request->input('from_username');
        // $to_username = $request->input('to_username');
        $from = "admin";
        $to = "client";

        $from_username = "admin";
        $to_username = "client";

        $channel_name = "livechat-{$from_username}-{$to_username}";

        $channel = $this->client->getChannel("messaging", $channel_name);
        $channel->create($from_username, [$to_username]);

        return response()->json([
            'channel' => $channel_name
        ], 200);
    }
}
