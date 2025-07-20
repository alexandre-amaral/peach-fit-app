<?php

namespace App\Http\Controllers\Panel\HelpDesk;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketCommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'comment' => 'required',
            'is_solution' => 'boolean',
        ]);

        $comment = new TicketComment([
            'comment' => $request->comment,
            'user_id' => Auth::id(),
            'is_solution' => $request->has('is_solution'),
        ]);

        $ticket->comments()->save($comment);

        // Se o comentário é uma solução, marcar o ticket como resolvido
        if ($request->has('is_solution')) {
            $ticket->update(['status' => 'resolvido']);
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Comentário adicionado com sucesso!');
    }
}
