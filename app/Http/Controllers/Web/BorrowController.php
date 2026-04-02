<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;

class BorrowController extends Controller
{
    public function borrow(Request $request, Book $book)
    {
        // 🔴 IMPORTANT: Check if copies > 0 (Requirement #11)
        if ($book->copies <= 0) {
            // 🔴 IMPORTANT: Exact message required (Requirement #12)
            return redirect()->route('books_list')
                ->with('error', 'Book Currently Unavailable');
        }

        // Check borrowing limit
        $borrowCount = Borrow::where('user_id', auth()->id())->count();
        if ($borrowCount >= 3) {
            return redirect()->route('books_list')
                ->with('error', 'You have reached your borrowing limit (3 books).');
        }

        // 🔴 IMPORTANT: Decrease copies count (Requirement #13)
        $book->copies -= 1;
        $book->save();

        // Save borrowing record
        $borrow = new Borrow();
        $borrow->user_id = auth()->id();
        $borrow->book_id = $book->id;
        $borrow->borrowed_at = now();
        $borrow->save();

        return redirect()->route('books_list')
            ->with('success', 'Book borrowed successfully!');
    }

    public function history(Request $request)
    {
        $borrows = Borrow::where('user_id', auth()->id())
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('borrows.history', compact('borrows'));
    }
}
