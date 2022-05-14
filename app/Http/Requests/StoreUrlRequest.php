<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreUrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url.name' => 'required|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'url.name.required' => 'Некорректный URL',
            'url.name.max'      => 'URL не должен превышать 255 символов',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isEmpty()) {
                return;
            }

            $messages = array_reduce(
                $validator->errors()->messages(),
                fn(array $carry, array $message) => [...$carry, ...$message],
                [],
            );

            foreach ($messages as $message) {
                flash($message)->error();
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $urlData = isset($this->url) ? $this->url : [];
        $components = parse_url($urlData['name'] ?? '');
        $url = isset($components['scheme'], $components['host'])
            ? "{$components['scheme']}://{$components['host']}"
            : null;

        $this->merge(['url' => ['name' => $url]]);
    }
}
