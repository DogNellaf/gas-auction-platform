<?php

namespace App\Http\Controllers;

use App\Enums\AuctionStatus;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(): View
    {
        $auctions = Auction::where('end_at', '>', now())
            ->where('status', AuctionStatus::Opened)
            ->latest()
            ->paginate(10);

        return view('index', ['auctions' => $auctions]);
    }

    public function detail(Auction $auction): View
    {
        return view('detail', ['auction' => $auction]);
    }

    public function bidForm(Auction $auction): View
    {
        abort_if(!Auth::check(), 403);

        return view('home.create', ['auction' => $auction]);
    }

    public function submitBid(Auction $auction, Request $request): RedirectResponse
    {
        abort_if(!Auth::check(), 403);

        $minPrice = $auction->minimumNextBid();

        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:' . $minPrice],
        ]);

        Bid::create([
            'user_id'    => Auth::id(),
            'auction_id' => $auction->id,
            'price'      => $validated['price'],
            'status'     => \App\Enums\BidStatus::Waiting,
        ]);

        return redirect()->route('home')->with('success', 'Заявка успешно подана.');
    }
}
