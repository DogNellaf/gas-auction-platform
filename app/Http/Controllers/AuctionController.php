<?php

namespace App\Http\Controllers;

use App\Enums\AuctionStatus;
use App\Models\Auction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'start_price' => ['required', 'numeric', 'min:0.01'],
            'end_at'      => ['required', 'date', 'after:now'],
            'price_step'  => ['required', 'numeric', 'min:1', 'max:100'],
            'description' => ['required', 'string'],
        ]);

        Auction::create($validated);

        return redirect()->route('admin')->with('success', 'Аукцион создан.');
    }

    public function update(Request $request): RedirectResponse
    {
        $auction = Auction::findOrFail($request->integer('id'));

        $validated = $request->validate([
            'start_price' => ['required', 'numeric', 'min:0.01'],
            'end_at'      => ['required', 'date'],
            'price_step'  => ['required', 'numeric', 'min:1', 'max:100'],
            'description' => ['required', 'string'],
            'status'      => ['required', 'in:Opened,Hidden,Closed'],
        ]);

        $auction->update($validated);

        return redirect()->route('admin')->with('success', 'Аукцион обновлён.');
    }

    public function end(Request $request): RedirectResponse
    {
        $auction = Auction::findOrFail($request->integer('id'));

        $auction->close();

        return redirect()->route('admin')->with('success', 'Аукцион завершён.');
    }
}
