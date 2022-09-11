<?php

namespace App\Http\Requests;

use App\Enums\HttpStatusCode;
use App\Exceptions\ValidationFailException;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $default_categories = \App\Models\Category::whereNull("account_id")->orWhere("account_id", auth()->user()->id)
            ->select("id")
            ->get()
            ->toArray();
        $value_only_categories = array_map(
            fn ($val) => (string) $val["id"],
            $default_categories
        );
        return [
            'account_id' => 'sometimes|digits:' . auth()->user()->id,
            "content" => "required",
            "title" => "required",
            "categories" => [
                "required",
                "array",
                Rule::in($value_only_categories)
            ],
            "blog_id" => [
                "sometimes",
                "numeric",
                Rule::exists("blog", "id")
                    ->where("id", $this->post('blog_id'))
                    ->where("account_id", auth()->user()->id),
                Rule::in([(int) $this->blog])
            ],
        ];
    }

    public function messages()
    {
        return ['blog_id.in' => "The :attribute isn't match.", 'blog_id.exists' => "The :attribute is forbbiden."];
    }
}
