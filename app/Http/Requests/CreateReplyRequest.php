<?php

namespace App\Http\Requests;

use App\Models\ReplyAble;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateReplyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body'              => ['required'],
            'replyable_id'      => ['required'],
            'replyable_type'    => ['required', 'in:' . Thread::TABLE],
            'images'            => ['nullable', 'array', 'max:3'],
            'images.*'          => [
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
            'images.max' => 'Maksimal 3 gambar yang dapat diunggah.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Gambar harus berformat: jpeg, png, jpg, gif, atau webp.',
            'images.*.max' => 'Setiap gambar tidak boleh lebih dari 5MB.',
        ];
    }

    public function replyAble(): ReplyAble
    {
        return $this->findReplyAble($this->get('replyable_id'),  $this->get('replyable_type'));
    }

    private function findReplyAble(int $id, string $type): ReplyAble
    {
        switch ($type) {
            case Thread::TABLE:
                return Thread::find($id);
        }
        abort(404);
    }

    public function author(): User
    {
        return $this->user();
    }

    public function body(): string
    {
        return $this->get('body');
    }

    /**
     * Get uploaded images if any
     */
    public function images(): array
    {
        return $this->file('images', []);
    }
}
