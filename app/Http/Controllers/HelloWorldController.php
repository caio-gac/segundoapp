<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloWorldController extends Controller
{
    public function index(){
        $helloWorld = 'Olá Mundo! Meu primeiro exemplo em laravel! Controllers';
        return view(
            'Hello_world.index',
            compact('helloworld')
        );
    }
}
