<?php

namespace App\Http\Controllers;


use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;


/**
 * Base Controller
 *
 * Provides common functionality and traits for all controllers.
 */
abstract class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponseTrait;
}
