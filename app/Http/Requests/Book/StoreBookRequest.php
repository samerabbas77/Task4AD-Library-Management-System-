<?php

namespace App\Http\Requests\Book;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ((auth('api')->user()) &&($this->user()->hasPermissionTo('book-create') ));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => ['required','string','max:40'],
            'author'       => ['required','string','min:3','max:40'],
            'description'  => ['required','string','max:100'],
            'published_at' => ['required','date_format:Y-m-d'],
            'category'     => ['required',Rule::in(['Scince','Fiction','Crime','Drama','kids'])]
        ];
    }
    /**
     * Delete the white space 
     * and make sure that published_at is Standardizing Date Format
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'title'       => trim($this->title),
            'author'      => preg_replace('/\s+/', '', $this->author),
            'description' => preg_replace('/\s+/', '', $this->description),
            'published_at'   =>\Carbon\Carbon::parse($this->published_at)->format('Y-m-d'),
        ]);
    }

    /**
     * thing to do after validation
     * @return void
     */
    protected function passedValidation(): void
    {
        // Log a message that validation passed
        Log::info('Validation passed for request: ', $this->all());

        //find the id of the category
        $cat_id =Category::where('name', $this->category)->first('id');
        
        //trimming the description to a certain length
        $this->merge([
            'description' => substr($this->description, 0, 100),
            'category_id'      => $cat_id['id'] //must be like the name of book column(category_id)
        ]);
    }

    /**
     * Get custom messages for validator errors.
     * @return array
     */
    public function messages(): array
    {
        return[
            'required' => 'The :attribute  is required',
            'string'   => 'The :attribute  must be string.',
            'max'      => 'The :attribute  must be at max 40.',              
            'min'      => 'The :attribute  must be at min 3.',
            'date'     => 'The :attribute  must be date type.',
             ];
    }
    /**
     *  Get custom attributes for validator errors.
     * @return array
     */
    public function attributes(): array
    {
        return[
            'title'=> 'book title',
            'author' => 'author name',
            'description' => 'book description',
            'published_at' => 'publication date',
        ];
    }
    /**
     * hook the exception when the validation of the request fails.
     * Customize how validation failures are handled.
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([

            'success'   => false,
            'message'   => 'Filter Validation errors',
            'data'      => $validator->errors(),
            'status'    => 400

        ]));

    }

   
}
