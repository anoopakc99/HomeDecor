<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with('user')->latest()->paginate(20);
        return view('admin.support.index', compact('tickets'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,pending,processing,resolved,closed',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        // Send Notification
        $ticket->user->notify(new \App\Notifications\SupportTicketStatusUpdated($ticket));

        return back()->with('success', 'Ticket status updated successfully.');
    }
}
