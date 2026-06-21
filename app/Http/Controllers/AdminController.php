<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View
    {
        return view('admin.auctions.index', [
            'auctions' => Auction::latest()->paginate(5),
        ]);
    }

    public function auctionCreate(): View
    {
        return view('admin.auctions.create');
    }

    public function auctionEdit(Auction $auction): View
    {
        return view('admin.auctions.edit', ['auction' => $auction]);
    }

    public function bids(): View
    {
        return view('admin.bids.index', [
            'bids' => Bid::with(['user', 'auction'])->latest()->paginate(10),
        ]);
    }

    public function bidShow(Bid $bid): View
    {
        return view('admin.bids.show', ['bid' => $bid->load(['user', 'auction'])]);
    }

    public function users(): View
    {
        return view('admin.users.index', [
            'pendingUsers'  => User::where('is_admin', false)->where('is_approved', false)->with('legalForm')->latest()->paginate(10, ['*'], 'pending'),
            'approvedUsers' => User::where('is_admin', false)->where('is_approved', true)->with('legalForm')->latest()->paginate(10, ['*'], 'approved'),
        ]);
    }

    public function approveUser(User $user): RedirectResponse
    {
        $user->update(['is_approved' => true]);

        return back()->with('success', "Пользователь «{$user->name}» одобрен.");
    }

    public function rejectUser(User $user): RedirectResponse
    {
        $user->update(['is_approved' => false]);

        return back()->with('success', "Пользователь «{$user->name}» заблокирован.");
    }
}
