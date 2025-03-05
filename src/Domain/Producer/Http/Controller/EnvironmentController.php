<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Controller;

use App\Domain\System\Repository\SystemRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\SystemResource;

final class EnvironmentController extends Controller
{
    /**
     * @param SystemRepository $systemRepository
     * @return Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|View|Application
     */
    public function __invoke(SystemRepository $systemRepository): Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|View|Application
    {
        $systems = $systemRepository->findAllByProducerId(Auth::user()->{Auth::user()->getKeyName()});
        $resource = SystemResource::schemas($systems);

        return view('producer.environment', ['systems' => $resource]);
    }
}
