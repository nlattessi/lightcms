<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', 'HomeController:index')->setName('home');

$app
    ->group('', function () {
        $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
        $this->post('/auth/signup', 'AuthController:postSignUp');

        $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
        $this->post('/auth/signin', 'AuthController:postSignIn');
    })
    ->add(new GuestMiddleware($container));

$app
    ->group('', function () {
        $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

        $this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
        $this->post('/auth/password/change', 'PasswordController:postChangePassword');

        $this->get('/images', 'ImageController:index')->setName('images.index');
        $this->get('/images/{id:[0-9]+}', 'ImageController:show')->setName('images.show');
        $this->get('/images/upload', 'ImageController:getUpload')->setName('images.upload');
        $this->post('/images/upload', 'ImageController:postUpload');
        $this->get('/images/{id:[0-9]+}/edit', 'ImageController:getEdit')->setName('images.edit');
        $this->post('/images/{id:[0-9]+}/edit', 'ImageController:postEdit');
        $this->get('/images/{id:[0-9]+}/delete', 'ImageController:getDelete')->setName('images.delete');
        $this->post('/images/{id:[0-9]+}/delete', 'ImageController:postDelete');

        $this->get('/slides', 'SlideController:index')->setName('slides.index');
        $this->get('/slides/add', 'SlideController:getAdd')->setName('slides.add');
        $this->post('/slides/add', 'SlideController:postAdd');
        $this->get('/slides/{id:[0-9]+}/edit', 'SlideController:getEdit')->setName('slides.edit');
        $this->post('/slides/{id:[0-9]+}/edit', 'SlideController:postEdit');
        $this->get('/slides/{id:[0-9]+}/delete', 'SlideController:getDelete')->setName('slides.delete');
        $this->post('/slides/{id:[0-9]+}/delete', 'SlideController:postDelete');
    })
    ->add(new AuthMiddleware($container));
