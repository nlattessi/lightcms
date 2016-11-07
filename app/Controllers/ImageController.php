<?php

namespace App\Controllers;

use App\Helpers\UploadHandler\CustomUploadHandler;
use App\Models\Image;

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

    public function showThumb($request, $response, $args)
    {
        $image = Image::find($args['id']);

        if (!isset($image)) {
            $this->flash->addMessage('error', 'Image not found');
            return $response->withRedirect($this->router->pathFor('images.index'));
            // $handler = $this->notFoundHandler;
            // return $handler($request, $response);
        }

        $filepath = $this->getThumbFilepath($image->filename);

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
        // $files = $request->getUploadedFiles();

        // if (empty($files['newfile'])) {
        //     throw new Exception('Expected a new file');
        // }

        // $newfile = $files['newfile'];

        // if ($newfile->getError() === UPLOAD_ERR_OK) {
        //     $regExp = '/\.(' . Image::getAllowedExtensions(true) . ')$/i';

        //     if (!preg_match($regExp, $newfile->getClientFilename())) {
        //         $this->flash->addMessage('error', 'Filetype not allowed');
        //         return $response->withRedirect(
        //             $this->router->pathFor('images.upload')
        //         );
        //     }

        //     $filename = $this->getFilename($newfile);
        //     $filepath = $this->getFilepath($filename);
        //     $newfile->moveTo($filepath);

        //     $image = Image::create(
        //         [
        //             'name' => $request->getParam('name', null),
        //             'filename' => $filename,
        //         ]
        //     );

        //     $this->flash->addMessage('info', 'Your image has been uploaded!');
        // } else {
        //     $this->flash->addMessage('error', 'The image can not be uploaded...!');
        // }

        $uploadHandler = new CustomUploadHandler([
            'upload_dir' => $this->container['settings']['files']['uploadPath'] . '/',
            'upload_url' => null,
            'access_control_allow_methods' => ['POST'],
            'accept_file_types' => '/\.(' . Image::getAllowedExtensions(true) . ')$/i',
            'param_name' => 'newfile',
            'image_library' => 0,
            'image_versions' => [
                '' => ['auto_orient' => false],
                'thumb' => [
                    'upload_dir' => $this->container['settings']['files']['thumbUploadPath'] . '/',
                    'upload_url' => null,
                    'crop' => true,
                    'max_width' => 252,
                    'max_height' => 252
                ]
            ],
        ]);


        if (isset($uploadHandler->getResultContent()['newfile'][0]->error)) {
            $this->flash->addMessage('error', "Image can not be uploaded!");
            $this->flash->addMessage('error', "ERROR :: {$uploadHandler->getResultContent()['newfile'][0]->error}");
            return $response->withRedirect($this->router->pathFor('images.upload'));
        }

        $image = Image::create(
            [
                'name' => $request->getParam('name', null),
                'filename' => $uploadHandler->getUploadedFileName(),
            ]
        );

        $this->flash->addMessage('info', 'Your image has been uploaded!');
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

        $thumbFilepath = $this->getThumbFilepath($image->filename);

        if (file_exists($thumbFilepath)) {
            unlink($thumbFilepath);
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

    private function getThumbFilepath($filename)
    {
        $uploadPath = $this->container['settings']['files']['thumbUploadPath'];
        return "{$uploadPath}/{$filename}";
    }
}
