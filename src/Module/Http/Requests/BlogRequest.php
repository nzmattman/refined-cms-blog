<?php

namespace RefinedDigital\Blog\Module\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    /**
     * Determine if the service is authorized to make this request.
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

        $args = [
            'name'                => ['required' => 'required'],
            'content'             => ['required' => 'required'],
        ];

        $config = config('blog');

        if ($config['thumbnail']['required'] && $config['thumbnail']['show']) {
            $args['image'] = ['required' => 'required'];
        }

        // return the results to set for validation
        return $args;
    }

    public function messages()
    {
        $messages = [];

        $requiredImage = config('blog.thumbnail.required');
        if ($requiredImage) {
            $messages['image.required'] = 'The thumbnail field is required.';
        }

        $messages['text.required'] = 'The content field is required.';

        return $messages;
    }
}
