<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Http\Request;

use App\Core\Enum\AuthGuard;
use App\Domain\UMRP\DTO\GetRolesDTO;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Request\DefaultPaginateRequest;
use Override;

/**
 * @property int $per_page
 * @property int $page
 */
class GetRolesRequest extends DefaultPaginateRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(AuthGuard::webProducer->value)->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[Override] public function rules(): array
    {
        return parent::rules();
    }

    #[Override] public function toDTO(): object
    {
        $dto = new GetRolesDTO(
            $this->page,
            $this->per_page,
            $this->search
        );
        $dto->setUser(Auth::user());

        return $dto;
    }
}
