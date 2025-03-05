<?php

declare(strict_types=1);

namespace Infrastructure\Http\Middleware;

use App\Domain\Producer\Repository\ProducerRepository;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckProducerHasEnvironment
{
    /**
     * @param ProducerRepository $producerRepository
     */
    public function __construct(
        private readonly ProducerRepository $producerRepository
    )
    {
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $complexCheck = Auth::check()
            && !$this->producerRepository->hasEnvironment(Auth::user()->{Auth::user()->getKeyName()})
            && 'startedProducer.setEnvironment' !== $request->route()->getName()
            && !$request->ajax();

        if ($complexCheck) {
            return redirect()->route('startedProducer.setEnvironment');
        }

        return $next($request);
    }
}
