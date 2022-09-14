<?php

namespace App\Http\Requests;

use App\DTO\BlogDTO;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class BlogRequest extends BaseRequest
{
    public function authorize()
    {
        return DB::table("blog")->where("id", $this->post('blog_id'))->where("account_id", auth()->user()->id)->get()->count();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $default_categories = Category::whereNull("account_id")->orWhere("account_id", auth()->user()->id)->select("id")->get()->toArray();
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
        $validated['id'] = array_column_default($validated, 'blog_id', null);
        return new BlogDTO($validated);
    }

    public function messages(): array
    {
        return ['blog_id.in' => "The :attribute isn't match.", 'blog_id.exists' => "The :attribute is forbidden."];
    }
}
