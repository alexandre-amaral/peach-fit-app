<?php

namespace App\Http\Controllers\Panel\HelpDesk;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['creator', 'assignedTo'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:baixa,media,alta,urgente',
        ]);

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'aberto',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Chamado criado com sucesso!');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['creator', 'assignedTo', 'comments.user']);
        $supportUsers = User::where('role', 'admin')->get();
        
        return view('tickets.show', compact('ticket', 'supportUsers'));
    }

    public function edit(Ticket $ticket)
    {
        $supportUsers = User::where('role', 'admin')->get();
        return view('tickets.edit', compact('ticket', 'supportUsers'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:baixa,media,alta,urgente',
            'status' => 'required|in:aberto,em_andamento,resolvido,fechado',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Chamado atualizado com sucesso!');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'em_andamento'
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Chamado atribuído com sucesso!');
    }

    public function myTickets()
    {
        $tickets = Ticket::where('assigned_to', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('tickets.my-tickets', compact('tickets'));
    }
}
