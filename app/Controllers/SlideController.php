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

        $this->flash->addMessage('info', 'The slide has been added!');
        return $response->withRedirect($this->router->pathFor('slides.index'));
    }

    public function getEdit($request, $response, $args)
    {
        $slide = Slide::find($args['id']);

        if (!isset($slide)) {
            $this->flash->addMessage('error', 'Slide not found');
            return $response->withRedirect($this->router->pathFor('slides.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $images = Image::all();

        return $this->view->render(
            $response,
            'slides/edit.twig',
            [
                'slide' => $slide,
                'images' => $images,
            ]
        );
    }

    public function postEdit($request, $response, $args)
    {
        $slide = Slide::find($args['id']);

        if (!isset($slide)) {
            $this->flash->addMessage('error', 'Slide not found');
            return $response->withRedirect($this->router->pathFor('slides.index'));
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

        $slide->image()->associate($image);
        $slide->order = $request->getParam('order');
        $slide->name = $request->getParam('name');
        $slide->caption = $request->getParam('caption');
        $slide->save();

        $this->flash->addMessage('info', 'Slide updated');
        return $response->withRedirect($this->router->pathFor('slides.index'));
    }

    public function getDelete($request, $response, $args)
    {
        $slide = Slide::find($args['id']);

        if (!isset($slide)) {
            $this->flash->addMessage('error', 'Slide not found');
            return $response->withRedirect($this->router->pathFor('slides.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        return $this->view->render(
            $response,
            'slides/delete.twig',
            [
                'slide' => $slide
            ]
        );
    }

    public function postDelete($request, $response, $args)
    {
        $slide = Slide::find($args['id']);

        if (!isset($slide)) {
            $this->flash->addMessage('error', 'Slide not found');
            return $response->withRedirect($this->router->pathFor('slides.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $slide->delete();

        $this->flash->addMessage('info', 'Slide deleted');
        return $response->withRedirect($this->router->pathFor('slides.index'));
    }
}
