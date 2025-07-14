<?php

namespace App\Http\Controllers;

use App\Models\managerLogModel;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class managerLogController extends Controller
{
    public function addLog(Request $request){
        $request->validate([
            'account'=>'require',
            'action'=>'require',
            'dataNum'=>'Nullable'
        ]);

        managerLogModel::create([
            'account'=>$request->account,
            'action'=>$request->action,
            'dataNum'=>$request->dataNum
        ]);
    }
}
