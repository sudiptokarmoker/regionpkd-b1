<?php
namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class UsersServiceProvider extends ServiceProvider{
    public function register()
    {
        $this->app->bind(UsersInterface::class, UsersRepository::class);
}
}