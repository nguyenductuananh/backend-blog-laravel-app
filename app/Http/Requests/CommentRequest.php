<?php

namespace App\Http\Requests;

use App\Exceptions\ValidationFailException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'blog_id' => ['required', 'numeric', Rule::exists('blog', 'id')->where('id', $this->blog_id), Rule::in([$this->blog_id])],
            'content' => "required",
            'reply_to' => ['numeric', 'nullable', Rule::exists('comment', 'id')->where("id", $this->post('reply_to'))->whereNull('reply_to')],
        ];
    }

    /**
     * Message when validate fails
     */
    public function messages()
    {
        return [
            'blog_id.exists' => "The :attribute isn't exist.",
            'blog_id.in' => "The :attribute isn't match",
        ];
    }
}
