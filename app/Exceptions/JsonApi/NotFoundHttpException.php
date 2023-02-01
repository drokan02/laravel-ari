<?php

namespace App\Exceptions\JsonApi;

use Exception;

class NotFoundHttpException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'errors' => [
                [
                    'title' => 'No Encontrado',
                    'detail' => 'No se encontro el recurso',
                    'status' => '404'
                ]
            ]
        ], 404);
    }
}
