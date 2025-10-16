<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;



use App\Models\UserModule;

use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    //

        public function index()
    {
        return Module::all();
    }

    public function activate($id)
    {
        $module = Module::findOrFail($id);

        UserModule::updateOrCreate(
            ['user_id' => Auth::id(), 'module_id' => $module->id],
            ['active' => true]
        );

        return response()->json(['message' => 'Module activated']);
    }

    public function deactivate($id)
    {
        $module = Module::findOrFail($id);

        UserModule::updateOrCreate(
            ['user_id' => Auth::id(), 'module_id' => $module->id],
            ['active' => false]
        );

        return response()->json(['message' => 'Module deactivated']);
    }
}
