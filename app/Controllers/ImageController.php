<?php

namespace App\Controllers;

use App\Models\Image;
use Slim\Views\Twig as View;

class ImageController extends Controller
{
    public function index($request, $response)
    {
        $images = Image::all();

        return $this->view->render(
            $response,
            'images/index.twig',
            [
                'images' => $images,
            ]
        );
    }

    public function show($request, $response, $args)
    {
        $image = Image::find($args['id']);

        if (!isset($image)) {
            $this->flash->addMessage('error', 'Image not found');
            return $response->withRedirect($this->router->pathFor('images.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $filepath = $this->getFilepath($image->filename);

        if (file_exists($filepath)) {
            $fileExtension = pathinfo($image->filename, PATHINFO_EXTENSION);

            if (isset(Image::$mimeTypes[$fileExtension])) {
                $mimeType = Image::$mimeTypes[$fileExtension];
            } else {
                $mimeType = 'application/octet-stream';
            }

            $image = file_get_contents($filepath);

            $response->write($image);
            return $response->withHeader('Content-Type', $mimeType);
        }
    }

    public function getUpload($request, $response)
    {
        return $this->view->render($response, 'images/upload.twig');
    }

    public function postUpload($request, $response)
    {
        $files = $request->getUploadedFiles();

        if (empty($files['newfile'])) {
            throw new Exception('Expected a new file');
        }

        $newfile = $files['newfile'];

        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $regExp = '/\.(' . Image::getAllowedExtensions(true) . ')$/i';

            if (!preg_match($regExp, $newfile->getClientFilename())) {
                $this->flash->addMessage('error', 'Filetype not allowed');
                return $response->withRedirect(
                    $this->router->pathFor('images.upload')
                );
            }

            $filename = $this->getFilename($newfile);
            $filepath = $this->getFilepath($filename);
            $newfile->moveTo($filepath);

            $user = Image::create(
                [
                    'name' => $request->getParam('name', null),
                    'filename' => $filename,
                ]
            );

            $this->flash->addMessage('info', 'Your file has been uploaded!');
        } else {
            $this->flash->addMessage('error', 'The file can not be uploaded...!');
        }

        return $response->withRedirect($this->router->pathFor('images.index'));
    }

    public function getEdit($request, $response, $args)
    {
        $image = Image::find($args['id']);

        if (!isset($image)) {
            $this->flash->addMessage('error', 'Image not found');
            return $response->withRedirect($this->router->pathFor('images.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        return $this->view->render(
            $response,
            'images/edit.twig',
            [
                'image' => $image
            ]
        );
    }

    public function postEdit($request, $response, $args)
    {
        $image = Image::find($args['id']);

        if (!isset($image)) {
            $this->flash->addMessage('error', 'Image not found');
            return $response->withRedirect($this->router->pathFor('images.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $image->name = $request->getParam('name', null);
        $image->save();

        $this->flash->addMessage('info', 'Image updated');
        return $response->withRedirect($this->router->pathFor('images.index'));
    }

    public function getDelete($request, $response, $args)
    {
        $image = Image::find($args['id']);

        if (!isset($image)) {
            $this->flash->addMessage('error', 'Image not found');
            return $response->withRedirect($this->router->pathFor('images.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        return $this->view->render(
            $response,
            'images/delete.twig',
            [
                'image' => $image
            ]
        );
    }

    public function postDelete($request, $response, $args)
    {
        $image = Image::find($args['id']);

        if (!isset($image)) {
            $this->flash->addMessage('error', 'Image not found');
            return $response->withRedirect($this->router->pathFor('images.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $filepath = $this->getFilepath($image->filename);

        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $image->delete();

        $this->flash->addMessage('info', 'Image deleted');
        return $response->withRedirect($this->router->pathFor('images.index'));
    }

    private function getFilename($file)
    {
        $timestamp = (new \DateTime)->getTimeStamp();
        return "{$timestamp}_{$file->getClientFilename()}";
    }

    private function getFilepath($filename)
    {
        $uploadPath = $this->container['settings']['files']['uploadPath'];
        return "{$uploadPath}/{$filename}";
    }
}
