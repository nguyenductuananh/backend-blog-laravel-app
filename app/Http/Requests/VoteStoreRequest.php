<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoteStoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment_id' => ['required', 'numeric', Rule::in([$this->comment]), Rule::exists('comment', 'id')->where('id', $this->comment)]
        ];
    }

    public function messages()
    {
        return [
            'comment_id.exists' => "The :attribute isn't exist.",
            'comment_id.in' => "The :attribute doesn't match."
        ];
    }
}
