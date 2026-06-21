<?php

namespace App\Http\Controllers;

use App\Models\LegalForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin');
        }

        return view('home.index', [
            'bids' => $user->bids()->with('auction')->latest()->get(),
            'user' => $user,
        ]);
    }

    public function dataEditor(): View
    {
        return view('home.data-editor', [
            'user'  => Auth::user(),
            'forms' => LegalForm::all(),
        ]);
    }

    public function dataSave(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user)],
            'email'        => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'company_name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user)],
            'form_id'      => ['required', 'integer', 'exists:legal_forms,id'],
            'phone'        => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user)],
        ]);

        $user->update($validated);

        return redirect()->route('home.data.editor')->with('success', 'Данные сохранены.');
    }
}
