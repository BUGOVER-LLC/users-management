<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Http\Request;

use App\Core\Enum\AuthGuard;
use App\Domain\UMRP\DTO\GetPermissionDTO;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Request\DefaultPaginateRequest;
use Override;

/**
 * @property int $per_page
 * @property int $page
 */
class GetPermissionRequest extends DefaultPaginateRequest
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
        return new GetPermissionDTO($this->page, $this->per_page, $this->search);
    }
}
