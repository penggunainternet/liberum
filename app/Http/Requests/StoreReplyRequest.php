<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReplyRequest extends FormRequest
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
            'body' => ['required', 'string', 'max:1000'],
            'replyable_id' => ['required', 'integer'],
            'replyable_type' => ['required', 'string', Rule::in(['threads'])],
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => [
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:5120'  // 5MB per image
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('images')) {
                $totalSize = 0;
                foreach ($this->file('images') as $image) {
                    $totalSize += $image->getSize();
                }

                // Check total size (10MB = 10485760 bytes)
                if ($totalSize > 10485760) {
                    $validator->errors()->add('images', 'Total ukuran gambar tidak boleh melebihi 10MB.');
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'body.required' => 'Isi komentar harus diisi.',
            'body.max' => 'Isi komentar tidak boleh lebih dari 1000 karakter.',
            'images.max' => 'Maksimal 3 gambar yang dapat diunggah.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Gambar harus berformat: jpeg, png, jpg, gif, atau webp.',
            'images.*.max' => 'Setiap gambar tidak boleh lebih dari 5MB.',
        ];
    }
}
