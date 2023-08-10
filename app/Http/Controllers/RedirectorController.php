<?php

namespace App\Http\Controllers;

use App\Contracts\RedirectorServiceInterface;
use App\DTO\MarketerProductDto;
use App\Http\Requests\RedirectRequest;
use App\Traits\SharedBetweenControllers;

class RedirectorController extends Controller
{
    use SharedBetweenControllers;

    public function __construct(
        private RedirectorServiceInterface $redirectorService
    ) {}

    public function redirect(RedirectRequest $request)
    {
        $dto = MarketerProductDto::fromRequest($request->safe());

        return response()->success($this->redirectorService->redirect($dto));
    }
}
