<?php

namespace App\Http\Requests\Book;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ((auth('api')->user()) &&($this->user()->hasPermissionTo('book-edit') ));    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => ['nullable','string','max:40'],
            'author'       => ['nullable','string','min:3','max:40'],
            'description'  => ['nullable','string','max:100'],
            'published_at' => ['nullable','date_format:Y-m-d'],
            'category'     => ['nullable',Rule::in(['Scince','Fiction','Crime','Drama','kids'])]

        ];
    }
    /**
     * Delete the white space 
     * and make sure that published_at is Standardizing Date Format
     * @return void
     */
    public function prepareForValidation(): void
    {
        
        if($this->title) $this->merge((['title'                  => preg_replace('/\s+/', '', $this->title)]));

        if($this->author) $this->merge((['author'                => preg_replace('/\s+/', '', $this->author)]));

        if($this->description) $this->merge((['description'      => preg_replace('/\s+/', '', $this->description)]));

        if($this->published_at) $this->merge((['published_at'    =>\Carbon\Carbon::parse($this->published_at)->format('Y-m-d')]));
    
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
        if($this->category)
        {
            $cat_id = Category::where('name', $this->category)->first('id');
            $this->merge([
                'category_id'      => $cat_id['id'
                ]]);
        } 
        
        //trimming the description to a certain length
        if($this->description)
        {
            $this->merge([
                'description' => substr($this->description, 0, 100),
            ]);
         }
    }


    /**
     * Get custom messages for validator errors.
     * @return array
     */
    public function messages(): array
    {
        return[
                'string' => 'The :attribute  must be string.',
                'max'    => 'The :attribute  must be at max 40.',              
                'min'    => 'The :attribute  must be at min 3.',
                'date'   => 'The :attribute  must be date type.',
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
