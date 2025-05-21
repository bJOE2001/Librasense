<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function checkRole($role)
    {
        if (!auth()->check() || auth()->user()->role->name !== $role) {
            abort(403, 'Unauthorized action.');
        }
    }

    protected function checkAdmin()
    {
        $this->checkRole('admin');
    }

    protected function checkStudent()
    {
        $this->checkRole('student');
    }

    protected function checkNonStudent()
    {
        $this->checkRole('non-student');
    }
}
