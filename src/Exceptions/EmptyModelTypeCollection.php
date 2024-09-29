<?php
namespace Veneridze\ModelTypes\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Exception;

class EmptyModelTypeCollection extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
    }

    /**
     * Render the exception as an HTTP response.
     */
    //public function render(Request $request): Response
    //{
    //    //
    //}
}
