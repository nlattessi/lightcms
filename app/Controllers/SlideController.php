<?php

namespace App\Controllers;

use App\Models\Image;
use App\Models\Slide;

class SlideController extends Controller
{
    public function index($request, $response)
    {
        $slides = Slide::orderBy('order', 'asc')->get();

        return $this->view->render(
            $response,
            'slides/index.twig',
            [
                'slides' => $slides
            ]
        );
    }

    public function getAdd($request, $response)
    {
        $images = Image::all();

        return $this->view->render(
            $response,
            'slides/add.twig',
            [
                'images' => $images
            ]
        );
    }

    public function postAdd($request, $response)
    {
        $image = Image::find($request->getParam('image'));

        if (!isset($image)) {
            $this->flash->addMessage('error', 'Image not found');
            return $response->withRedirect($this->router->pathFor('slides.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $slide = $image->slides()->create([
            'order' => $request->getParam('order'),
            'name' => $request->getParam('name'),
            'caption' => $request->getParam('caption'),
        ]);

        $this->flash->addMessage('info', 'The slide have been added!');
        return $response->withRedirect($this->router->pathFor('slides.index'));
    }
}
