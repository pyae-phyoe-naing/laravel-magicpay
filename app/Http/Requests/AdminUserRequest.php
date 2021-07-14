<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:admin_users,email',
            'phone' => 'required|unique:admin_users,phone',
            'password' => 'required|min:6',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'နာမည်ထည့်ရန်လိုအပ်ပါသည်။',
            'name.min' => 'နာမည်သည်အနည်းဆုံး ၆ လုံးထည့်ရန်လိုအပ်ပါသည်။',
            'email.required' => 'အီးမေးလ် ထည့်ရန်လိုအပ်ပါသည်။',
            'email.email' => 'အီးမေးလ်ဖြစ်ရန်လိုအပ်ပါသည်။',
            'email.unique' => 'အီးမေးလ်သည် အသုံးပြုပီးသားဖြစ်ပါသည်။',
            'phone.required' => 'ဖုန်းနံပါတ် ထည့်ရန်လိုအပ်ပါသည်။',
            'phone.unique' => 'ဖုန်းနံပါတ်သည် အသုံးပြုပီးသားဖြစ်ပါသည်။',
            'password.required' => 'စကား၀ှက် ထည့်ရန်လိုအပ်ပါသည်။',
            'password.min' => 'စကား၀ှက်သည်အနည်းဆုံး ၆ လုံးထည့်ရန်လိုအပ်ပါသည်။',

        ];
    }
}
