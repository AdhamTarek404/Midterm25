<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Book;

class BookController extends Controller
{
    public function list(Request $request)
    {
        $books = Book::all();
        return view('books.list', compact('books'));
    }

    public function edit(Request $request, Book $book = null)
    {
        $book = $book ?? new Book();
        return view('books.edit', compact('book'));
    }

    public function save(Request $request, Book $book = null)
    {
        // 🔴 IMPORTANT: Validate all inputs (Requirement #15 - Security)
        $this->validate($request, [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:13'],
            'copies' => ['required', 'integer', 'min:0'],
        ]);

        $book = $book ?? new Book();
        $book->fill($request->all());
        $book->save();

        return redirect()->route('books_list')->with('success', 'Book saved successfully!');
    }

    public function delete(Request $request, Book $book)
    {
        $book->delete();
        return redirect()->route('books_list')->with('success', 'Book deleted successfully!');
    }
}
