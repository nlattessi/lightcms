<?php

namespace App\Controllers;

use App\Models\Marca;

class MarcaController extends Controller
{
    public function index($request, $response)
    {
        return $this->view->render(
            $response,
            'marcas/index.twig',
            [

            ]
        );
    }

    public function getAdd($request, $response)
    {
        
    }

    public function postAdd($request, $response)
    {
        
    }

    public function getEdit($request, $response, $args)
    {
        
    }

    public function postEdit($request, $response, $args)
    {
        
    }

    public function getDelete($request, $response, $args)
    {
        
    }

    public function postDelete($request, $response, $args)
    {
        
    }
}