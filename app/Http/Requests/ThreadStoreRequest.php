<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ThreadStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'     => ['required', 'max:60', 'min:5'],
            'body'      => ['required', 'max:300', 'min:5'],
            'category_id'  => ['required', 'exists:categories,id'],
            'tags'      => ['nullable', 'string'],
        ];
    }

    public function author(): User
    {
        return $this->user();
    }

    public function title(): string
    {
        return $this->get('title');
    }

    public function body(): string
    {
        return $this->get('body');
    }

    public function category(): string
    {
        return $this->get('category_id');
    }

    public function tags(): array
    {
        return []; // This will be handled in the controller
    }
}
