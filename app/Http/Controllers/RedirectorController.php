<?php

namespace App\Http\Controllers;

use App\Contracts\RedirectorServiceInterface;
use App\Traits\SharedBetweenControllers;
use Illuminate\Http\Request;

class RedirectorController extends Controller
{
    use SharedBetweenControllers;

    public function __construct(private RedirectorServiceInterface $redirectorService)
    {
    }

    public function redirect(Request $request)
    {
        $request->validate($inputs = [
            'product' => 'required',
            'marketer' => 'required'
        ]);

        return response()->success($this->redirectorService->redirect($this->getParams(array_keys($inputs))));
    }
}
