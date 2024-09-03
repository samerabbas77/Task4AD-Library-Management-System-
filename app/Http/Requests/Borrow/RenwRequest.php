<?php

namespace App\Http\Requests\Borrow;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RenwRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ((auth('api')->user()) &&($this->user()->hasPermissionTo('borrow-create') ));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
              'borrowed_at'    => ['required', 'date_format:Y-m-d'],
        ];
    }

         /**
     * Delete the white space 
     * and make sure that published_at is Standardizing Date Format
     * @return void
     */
    public function prepareForValidation(): void
    {    
       if($this->borrowed_at)  $this->merge(['borrowed_at'  => Carbon::parse($this->borrowed_at)->format('Y-m-d')]);
                     
    }

    /**
     * thing to do after validation
     * find the id of the book with the  given title
     * send book_id with the request
     * @return void
     */
    protected function passedValidation(): void
    {
        // Log a message that validation passed
        Log::info('Validation passed for request: ', $this->all());


        if($this->borrowed_at) 
        {
            //claculate due date
            $dueDate = Carbon::parse($this->borrowed_at)->addDays(14)->format('Y-m-d');
            // Merge the due date into the request data 
            $this->merge(['due_date'    =>$dueDate]);
        }

    }

    /**
     * Get custom messages for validator errors.
     * @return array
     */
    public function messages(): array
    {
        return[
            'required' => 'The :attribute  is required',             
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
            'borrowed_at'  => 'borrowed date',     
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
