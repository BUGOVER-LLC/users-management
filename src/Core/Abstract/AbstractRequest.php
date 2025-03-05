<?php

declare(strict_types=1);

namespace App\Core\Abstract;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RuntimeException;

/**
 * Class BookkeepingCompanyPaginateRequest
 *
 * @package App\Http\Requests\SystemWorker
 * @method void moreValidation(\Illuminate\Validation\Validator $validator)
 * @method bool authorize()
 */
abstract class AbstractRequest extends FormRequest
{
    /**
     * @return \Illuminate\Validation\Validator
     */
    public function validator(): \Illuminate\Validation\Validator
    {
        $v = Validator::make($this->all(), $this->rules(), $this->messages(), $this->attributes());

        if (method_exists(static::class, 'moreValidation')) {
            $this->moreValidation($v);
        }

        return $v;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    final public function messages(): array
    {
        return $this->errorMessages();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function errorMessages(): array
    {
        return [];
    }

    abstract public function toDTO(): object;


    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $validationErrors = (new ValidationException($validator))->errors();
        $error['message'] = __('http-statuses.422');
        $error['errors'] = [];

        if (!$validationErrors) {
            return;
        }

        $validationMessages = [];

        foreach ($validationErrors as $field => $messages) {
            [$attribute] = explode('.', $field);

            foreach ($messages as $message) {
                $validationMessages[$attribute][] = $message;
            }
        }

        foreach ($validationMessages as $field => $messages) {
            $error['errors'][$field] = implode(' ', array_unique($messages));
        }

        throw new HttpResponseException(
            response()->json($error, Response::HTTP_UNPROCESSABLE_ENTITY),
        );
    }


    /**
     * Handle a passed validation attempt.
     *
     * @throws Exception
     */
    protected function passedValidation(): void
    {
        if (!config('app.strict') || app()->isProduction()) {
            return;
        }

        $all_with_dots = Arr::dot($this->all());
        $validated_with_dots = Arr::dot($this->validated());

        $not_validated_fields = array_keys(array_diff_key($all_with_dots, $validated_with_dots));

        if (!empty($not_validated_fields)) {
            throw new RuntimeException(
                __('validation.all_request_validation', ['fields' => implode(', ', $not_validated_fields)]),
                423
            );
        }

        $rules_with_dots = Arr::dot($this->rules());
        $empty_rules = array_filter($rules_with_dots, static fn($rule) => empty($rule));

        if (!empty($empty_rules)) {
            throw new RuntimeException(
                __('validation.all_request_empty', ['fields' => implode(', ', array_keys($empty_rules))]),
                423
            );
        }

        parent::passedValidation();
    }
}
