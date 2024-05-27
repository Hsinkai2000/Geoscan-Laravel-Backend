<?php

use Illuminate\Http\Response;

function debug_log($message, array $context = [])
{
    Log::channel('debug')->debug($message, $context);
}


function render_message($message)
{
    response()->json($message, Response::HTTP_OK)->send();
}
function render_error(string $error_message)
{
    response()->json(['error' => $error_message], Response::HTTP_UNPROCESSABLE_ENTITY)->send();
}