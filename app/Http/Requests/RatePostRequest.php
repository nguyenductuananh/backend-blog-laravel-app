<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class RatePostRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rate' => "required|numeric|max:5|min:0",
            'blog_id' => ['required', 'numeric', Rule::in([$this->blog_id]), Rule::exists('blog', 'id')->where("id", $this->blog)],
        ];
    }

    public function messages()
    {
        return [
            'blog_id.in' => "The :attribute isn't match.",
            'blog_id.exists' => "The :attribute isn't exist",
        ];
    }
}
