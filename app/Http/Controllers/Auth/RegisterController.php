<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LegalForm;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function showRegistrationForm(): View
    {
        return view('auth.register', ['forms' => LegalForm::all()]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'         => ['required', 'string', 'max:255', 'unique:users'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['required', 'string', 'max:255', 'unique:users'],
            'form_id'      => ['required', 'integer', 'exists:legal_forms,id'],
            'phone'        => ['required', 'string', 'max:20', 'unique:users'],
        ]);
    }

    protected function create(array $data): User
    {
        return User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'company_name' => $data['company_name'],
            'form_id'      => $data['form_id'],
            'phone'        => $data['phone'],
            'password'     => Hash::make($data['password']),
            'is_approved'  => false,
            'is_admin'     => false,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $this->validator($request->all())->validate();

        event(new Registered($this->create($request->all())));

        return redirect()->route('login')->with('success', 'Регистрация завершена. Ожидайте подтверждения администратором.');
    }
}
