<?php

namespace App\Http\Controllers;

/**
 * Stub for /local route. Replace with real implementation if needed.
 */
class TestingEnvironmentController extends Controller
{
    public function local_login()
    {
        return redirect()->route('portal');
    }
}
