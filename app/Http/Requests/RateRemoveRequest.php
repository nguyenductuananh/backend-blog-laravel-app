<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class RateRemoveRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'blog_id' => ['required', 'numeric', Rule::in([$this->blog]), Rule::exists('rate', 'blog_id')->where('account_id', auth()->user()->id)->where('blog_id', $this->post("blog_id"))]
        ];
    }
}
