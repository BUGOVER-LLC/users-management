<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Controller;

use App\Domain\Producer\Http\Resource\ProfileResource;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Infrastructure\Http\Controllers\Controller;

final class BoardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|Factory|View|Application
     */
    public function __invoke(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|Factory|View|Application
    {
        $producer = new ProfileResource(Auth::user());

        return view('producer.board', ['producer' => $producer]);
    }
}
