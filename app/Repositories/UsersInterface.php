<?php
namespace App\Repositories;

interface UsersInterface{
    public function all();
    public function findOrFail($id);
    public function find($id);
    public function updateUser($request);
}