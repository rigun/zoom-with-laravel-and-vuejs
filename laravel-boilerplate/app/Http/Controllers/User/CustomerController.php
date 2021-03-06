<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserRequest;
use App\Http\Requests\GetDataRequest;
use App\User;
use App\Role;

class CustomerController extends Controller
{
    private $mainController;

    public function __construct()
    {
        $this->mainController = new MainController();
    }
    
    public function getData(GetDataRequest $request){
        return $this->mainController->getData($request,'customer');
    }

    public function store(UserRequest $request){
        $item = new User();
        $role = Role::where('name','customer')->first();
        $item->role_id = $role->id;
        return $this->mainController->store($request,$item);
    }
    public function customValidate($id){
        if(!$item = User::find($id)){
            return 'Data tidak ditemukan';
        }
        if($item->role_name != 'customer'){
            return 'Anda hanya dapat mengubah data customer';
        }
        return null;
    }
    public function update(UserRequest $request,$id){
        $msg = $this->customValidate($id);
        if($msg != null){
            return $this->responseJson($msg,0);
        }
        return $this->mainController->store($request,$item);
    }
    public function destroy($id){
        $msg = $this->customValidate($id);
        if($msg != null){
            return $this->responseJson($msg,0);
        }
        return $this->mainController->destroy($item);
    }
}
