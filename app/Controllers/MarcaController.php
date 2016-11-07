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
            'link' => $request->getParam('link'),
        ]);

        $this->flash->addMessage('info', 'The marca has been added!');
        return $response->withRedirect($this->router->pathFor('marcas.index'));
    }

    public function getEdit($request, $response, $args)
    {
        $marca = Marca::find($args['id']);

        if (!isset($marca)) {
            $this->flash->addMessage('error', 'Marca not found');
            return $response->withRedirect($this->router->pathFor('marcas.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $images = Image::all();

        return $this->view->render(
            $response,
            'marcas/edit.twig',
            [
                'marca' => $marca,
                'images' => $images,
            ]
        );
    }

    public function postEdit($request, $response, $args)
    {
        $marca = Marca::find($args['id']);

        if (!isset($marca)) {
            $this->flash->addMessage('error', 'Marca not found');
            return $response->withRedirect($this->router->pathFor('marcas.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $image = Image::find($request->getParam('image'));

        if (!isset($image)) {
            $this->flash->addMessage('error', 'Image not found');
            return $response->withRedirect($this->router->pathFor('slides.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $marca->image()->associate($image);
        $marca->order = $request->getParam('order');
        $marca->name = $request->getParam('name');
        $marca->link = $request->getParam('link');
        $marca->save();

        $this->flash->addMessage('info', 'Marca updated');
        return $response->withRedirect($this->router->pathFor('marcas.index'));
    }

    public function getDelete($request, $response, $args)
    {
        $marca = Marca::find($args['id']);

        if (!isset($marca)) {
            $this->flash->addMessage('error', 'Marca not found');
            return $response->withRedirect($this->router->pathFor('marcas.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        return $this->view->render(
            $response,
            'marcas/delete.twig',
            [
                'marca' => $marca
            ]
        );
    }

    public function postDelete($request, $response, $args)
    {
        $marca = Marca::find($args['id']);

        if (!isset($marca)) {
            $this->flash->addMessage('error', 'Marca not found');
            return $response->withRedirect($this->router->pathFor('marcas.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $marca->delete();

        $this->flash->addMessage('info', 'Marca deleted');
        return $response->withRedirect($this->router->pathFor('marcas.index'));
    }
}