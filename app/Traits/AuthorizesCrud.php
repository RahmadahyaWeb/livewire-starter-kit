<?php

namespace App\Traits;

use Flux\Flux;
use Illuminate\Support\Facades\DB;

trait AuthorizesCrud
{
    protected function authorizeIndex($model)
    {
        $this->authorize('viewAny', $model);
    }

    protected function authorizeStore($model)
    {
        $this->authorize('create', $model);
    }

    protected function authorizeUpdate($modelInstance)
    {
        $this->authorize('update', $modelInstance);
    }

    protected function authorizeDelete($modelInstance)
    {
        $this->authorize('delete', $modelInstance);
    }

    protected function transaction(callable $callback)
    {
        DB::beginTransaction();

        try {
            $result = $callback();

            DB::commit();

            return $result;

        } catch (\Throwable $e) {
            DB::rollBack();

            Flux::toast(
                heading: 'Error',
                text: $e->getMessage(),
                variant: 'danger'
            );
        }
    }
}
