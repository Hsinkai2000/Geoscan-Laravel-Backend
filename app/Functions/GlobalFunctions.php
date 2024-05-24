<?php

function debug_log($message, array $context = [])
{
    Log::channel('debug')->debug($message, $context);
}