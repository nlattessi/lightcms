<?php

namespace App\Controllers;

use App\Models\Image;
use App\Models\Marca;

class MarcaController extends Controller
{
    public function index($request, $response)
    {
        $marcas = Marca::orderBy('order', 'asc')->get();

        return $this->view->render(
            $response,
            'marcas/index.twig',
            [
                'marcas' => $marcas,
            ]
        );
    }

    public function getAdd($request, $response)
    {
        $images = Image::all();

        return $this->view->render(
            $response,
            'marcas/add.twig',
            [
                'images' => $images,
            ]
        );
    }

    public function postAdd($request, $response)
    {
        $image = Image::find($request->getParam('image'));

        if (!isset($image)) {
            $this->flash->addMessage('error', 'Image not found');
            return $response->withRedirect($this->router->pathFor('marcas.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $marca = $image->marcas()->create([
            'order' => $request->getParam('order'),
            'name' => $request->getParam('name'),
            'caption' => $request->getParam('caption'),
            'link' => $request->getParam('link'),
        ]);

        $this->flash->addMessage('info', 'The marca has been added!');
        return $response->withRedirect($this->router->pathFor('marcas.index'));
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