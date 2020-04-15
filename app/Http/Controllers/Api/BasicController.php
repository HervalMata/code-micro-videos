<?php
/**
 * Created by PhpStorm.
 * User: Herval
 * Date: 15/04/2020
 * Time: 19:44
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Request;

class BasicController extends Controller
{
    public function index()
    {
        return $this->model()::all();
    }

    protected abstract function model();

    public function store(Request $request)
    {
        $this->validate($request, $this->rulesStore());
    }

    protected abstract function rulesStore();
}
