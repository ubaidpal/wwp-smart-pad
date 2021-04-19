<?php
namespace App\Repository\admin\Contracts;

interface UsersInterfaces {

    public function index();
    public function create($request);
    public function destroy($id);
    public function update($id);


}
