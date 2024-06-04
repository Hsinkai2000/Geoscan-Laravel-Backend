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

function linearise_leq($leq)
{
    return pow(10, $leq / 10);
}

function convert_to_db($avg_leq)
{
    return round(10 * log10($avg_leq), 1);
}