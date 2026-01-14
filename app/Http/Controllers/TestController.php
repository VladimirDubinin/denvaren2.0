<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test():void
    {
        /** @var TelegraphChat $chat */

        $chat->html("<strong>Hello, world!</strong>\n\nI'm here!")->send();
    }
}
