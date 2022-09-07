<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
        $default_categories = \App\Models\Category::whereNull("account_id")->orWhere("account_id", auth()->user()->id)
            ->select("id")
            ->get()
            ->toArray();
        $value_only_categories = array_map(
            fn($val) => (string) $val["id"],
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
                    ->where("id", $this->id)
                    ->where("account_id", auth()->user()->id),
            ],
        ];
    }
}
