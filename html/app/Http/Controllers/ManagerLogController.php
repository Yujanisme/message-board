<?php

namespace App\Http\Controllers;

use App\Models\managerLogModel;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class managerLogController extends Controller
{
    public function addLog(Request $request){
        $request->validate([
            'account' => 'require',
            'action_type' => 'require',
            'action' => 'require',
        ]);

        managerLogModel::create([
            'account'=>$request->account,
            'action_type'=>$request->action,
            'action'=>$request->action
        ]);
    }
}
