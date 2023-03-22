<?php

namespace App\Http\Requests\Usb;

use Illuminate\Foundation\Http\FormRequest;

class UsbExpenseRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET'://Read
            case 'PATCH'://Update/Modify
            case 'DELETE'://Delete
                $arrRules = [
                ];
                break;
            case 'PUT'://Update/Replace
                $arrRules = [
                    'dateexpense' => 'required|date_format:Y-m-d',
                    'id_expense' => 'required|numeric|min:1',
                    'amount' => 'required|numeric|min:1',
                ];
                break;
            case 'POST'://Create

                $arrRules = [
                    'dateexpense' => 'required|date_format:Y-m-d',
                    'id_expense' => 'required|numeric|min:1',
                    'amount' => 'required|numeric|min:1',
                ];
                //$arrRules = [];
                break;
        }
        return $arrRules;
    }
}
