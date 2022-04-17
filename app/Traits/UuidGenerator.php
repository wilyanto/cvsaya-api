<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UuidGenerator
{
    protected static function bootUuidGenerator() {
        static::creating(function ($model) {
            if(!$model->getKey()) {
                $model->{$model->getKeyName()} = str_replace( array( '-' ), '',(string) Str::uuid());
            }
        });
    }

    public function getIncrementing() {
        return false;
    }
    
    public function getKeyType() {
        return 'string';
    }
}