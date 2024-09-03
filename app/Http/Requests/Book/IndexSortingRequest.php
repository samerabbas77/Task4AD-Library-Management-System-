<?php

namespace App\Http\Requests\Book;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class IndexSortingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ((auth('api')->user()) &&($this->user()->hasPermissionTo('book-list') ));
    }   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "author"     => ["nullable","string","max:20","not_regex:/^.+@.+$/i"],
           
            "category"   => ["nullable","string","max:10","not_regex:/^.+@.+$/i","exists:categories,name"],

            "available"  => ["nullable","boolean"],

            "sort_by"    =>[ Rule::in(['ASC', 'DESC']),]
        ];
    }


     /**
     * Delete the white space 
     * and make sure that available is Standardizing Date Format
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'category'       => trim( $this->category),
            
            'author'         => trim( $this->author),     
        ]);
    }
    /**
     * Logging Valida.tion Success and Auto-Generating Related Data
     * i convert the shorthand that send by request to decide the 
     * @return void
     */
    protected function passedValidation()
    {
        Log::info('Validation passed for request.', [
            'author' => $this->author,
            'category' => $this->category,
            'available' => $this->category,
        ]);


        $category_id =  Category::where('name',$this->category)->get('id');
    
        $this->merge([
            'category_id' => $category_id,
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
            'exists'     => 'The :attribute  must be exists in the database.',
            'boolean'     => 'The :attribute  must be boolean.',
             ];
    }
    /**
     *  Get custom attributes for validator errors.
     * @return array
     */
    public function attributes(): array
    {
        return[
            'author' => 'author name',
            'category' => 'book category',
            'available' => 'book availablity',
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
