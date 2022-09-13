<?php

namespace App\Http\Requests;

use App\DTO\BlogDTO;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class BlogRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $default_categories = \App\Models\Category::whereNull("account_id")->orWhere("account_id", auth()->user()->id)->select("id")->get()->toArray();
        $value_only_categories = array_map(
            fn($val) => (string)$val["id"],
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
                Rule::exists("blog", "id")->where("id", $this->post('blog_id'))->where("account_id", auth()->user()->id),
                Rule::in([(int)$this->blog])
            ],
        ];
    }

    /**
     * @throws UnknownProperties
     */
    public function validated()
    {
        $validated = parent::validated();
        return new BlogDTO($validated);
    }

    public function messages(): array
    {
        return ['blog_id.in' => "The :attribute isn't match.", 'blog_id.exists' => "The :attribute is forbidden."];
    }
}
