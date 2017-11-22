<?php

namespace App\Traits;

trait IsEventable
{
    public static function boot()
    {
        parent::boot();

        static::created(function($model)
        {
            if(method_exists($model, 'eCreated'))
                $model->eCreated($model);
        });

        static::deleting(function($model)
        {
            if(method_exists($model, 'eDeleting'))
                $model->eDeleting($model);
        });
    }
}