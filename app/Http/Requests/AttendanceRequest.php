<?php

namespace App\Http\Requests;

use App\Http\Traits\Helpers;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    use Helpers;
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'attendance_date' => ['required','date'],
            'check_in' => ['nullable', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i'],
            'leave_status' => ['nullable', 'in:sick,casual'],
            'user_id' => ['required']
        ];
    }
    public function attributes()
    {
        return [
            'attendance_date' => 'Date',
            'check_in' => 'Check In',
            'check_out' => 'Check Out',
            'leave_status' => 'Leave Status',
        ];
    }

    public function prepareForValidation()
    {
       $current_time = date('H:i', strtotime(now()));
        $this->merge([
            'user_id' => auth()->user()->is_admin!=1 ? auth()->user()->id : $this->decryptFind(User::class,$this->user)->id,
            'check_in' => $current_time,
            'attendance_date' => date('Y-m-d'),
        ]);
    }
}
