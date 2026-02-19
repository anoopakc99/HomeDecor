@extends('admin.layouts.app')

@section('title', 'Support Tickets')

@section('content')
    <div class="card">
        <div class="card-header flex justify-between items-center p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Support Tickets</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left">Ticket ID</th>
                        <th class="px-6 py-3 text-left">User</th>
                        <th class="px-6 py-3 text-left">Subject</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Message</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">#{{ $ticket->ticket_id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $ticket->user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $ticket->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-900">{{ $ticket->subject }}</td>
                            <td class="px-6 py-4 text-slate-500 text-sm">{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.support.updateStatus', $ticket->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-bold uppercase tracking-wider rounded px-2 py-1 border-0 cursor-pointer focus:ring-2 focus:ring-offset-2 focus:ring-slate-500
                                                            {{ $ticket->status == 'open' ? 'bg-slate-200 text-slate-800' : '' }}
                                                            {{ $ticket->status == 'pending' ? 'bg-slate-800 text-white' : '' }}
                                                            {{ $ticket->status == 'processing' ? 'bg-slate-600 text-white' : '' }}
                                                            {{ $ticket->status == 'resolved' ? 'bg-green-600 text-white' : '' }}
                                                            {{ $ticket->status == 'closed' ? 'bg-black text-white' : '' }}">
                                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>OPEN</option>
                                        <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>PENDING
                                        </option>
                                        <option value="processing" {{ $ticket->status == 'processing' ? 'selected' : '' }}>
                                            PROCESSING
                                        </option>
                                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>RESOLVED
                                        </option>
                                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>CLOSED</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate" title="{{ $ticket->message }}">
                                {{ Str::limit($ticket->message, 50) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">No tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
@endsection