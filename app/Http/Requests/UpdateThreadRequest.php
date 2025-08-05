<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateThreadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && $this->route('thread')->author()->is(Auth::user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => [
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:5120', // 5MB per image
            ],
            'removed_images' => ['nullable', 'string'],
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
            $thread = $this->route('thread');
            $currentImageCount = $thread->images()->count();
            $removedImageIds = array_filter(explode(',', $this->input('removed_images', '')));
            $remainingImages = $currentImageCount - count($removedImageIds);
            $newImageCount = $this->hasFile('images') ? count($this->file('images')) : 0;
            $totalImages = $remainingImages + $newImageCount;

            // Check total image count
            if ($totalImages > 5) {
                $validator->errors()->add('images', 'Total gambar tidak boleh lebih dari 5.');
            }

            // Check total size of new images
            if ($this->hasFile('images')) {
                $totalSize = 0;
                foreach ($this->file('images') as $image) {
                    $totalSize += $image->getSize();
                }

                if ($totalSize > 15728640) { // 15MB in bytes
                    $validator->errors()->add('images', 'Total ukuran gambar baru tidak boleh lebih dari 15MB.');
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
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'body.required' => 'Konten wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'images.max' => 'Maksimal 5 gambar yang dapat diupload.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, atau webp.',
            'images.*.max' => 'Ukuran gambar maksimal 5MB per file.',
        ];
    }
}
