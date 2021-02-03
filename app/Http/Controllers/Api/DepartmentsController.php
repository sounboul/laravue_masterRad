<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Department;

class DepartmentsController extends Controller
{
    public function fetchDepartments()
    {
    	$departments = Department::orderBy('name')->get();
    	return response()->json(new JsonResponse(['departments' => $departments]));
    }
}
