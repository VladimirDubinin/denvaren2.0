<?php

namespace App\Http\Controllers;

use DefStudio\Telegraph\Models\TelegraphChat;

abstract class Controller
{
    public function test():void
    {
        /** @var TelegraphChat $chat */

        $chat->html("<strong>Hello!</strong>\n\nI'm here!")->send();
    }
}
