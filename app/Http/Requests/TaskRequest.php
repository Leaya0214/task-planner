<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $user = Auth::user();
        if ($user->role == 'admin') {
            return [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'assigned_to' => 'required|exists:users,id',
                'status' => 'required|in:pending,in_progress,completed',
                'priority' => 'required|in:low,medium,high',
                'due_date' => 'nullable|date|after_or_equal:today',
            ];
        }

        // Employee restricted
        return [
            'status' => 'required|in:pending,in_progress,completed',
            'description' => 'nullable|string',
        ];
    }
}
