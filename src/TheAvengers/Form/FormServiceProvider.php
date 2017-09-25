<?php

namespace TheAvengers\Form;

use TheAvengers\Form\ErrorStore\IlluminateErrorStore;
use TheAvengers\Form\OldInput\IlluminateOldInputProvider;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        $this->registerErrorStore();
        $this->registerOldInput();
        $this->registerFormBuilder();
    }

    protected function registerErrorStore()
    {
        $this->app->singleton('theavengers.form.errorstore', function ($app) {
            return new IlluminateErrorStore($app['session.store']);
        });
    }

    protected function registerOldInput()
    {
        $this->app->singleton('theavengers.form.oldinput', function ($app) {
            return new IlluminateOldInputProvider($app['session.store']);
        });
    }

    protected function registerFormBuilder()
    {
        $this->app->singleton('theavengers.form', function ($app) {
            $formBuilder = new FormBuilder;
            $formBuilder->setErrorStore($app['theavengers.form.errorstore']);
            $formBuilder->setOldInputProvider($app['theavengers.form.oldinput']);
            $formBuilder->setToken($app['session.store']->token());

            return $formBuilder;
        });
    }

    public function provides()
    {
        return ['theavengers.form'];
    }
}
