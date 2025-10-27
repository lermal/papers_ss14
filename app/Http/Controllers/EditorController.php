<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function index()
    {
        return view('editor.index');
    }

    public function preview()
    {
        return response()->json([
            'html' => $this->parseMarkup(request('text'))
        ]);
    }

    private function parseMarkup($text)
    {
        return preg_replace([
            '/\[bold\](.*?)\[\/bold\]/s',
            '/\[color=(#[0-9A-Fa-f]{6})\](.*?)\[\/color\]/s',
            '/\[bullet\](.*?)(?:\n|$)/s',
            '/\[head=([1-5])\](.*?)\[\/head\]/s'
        ], [
            '<strong>$1</strong>',
            '<span style="color: $1">$2</span>',
            '<li>$1</li>',
            '<h$1>$2</h$1>'
        ], $text);
    }

    // Метод для будущего сохранения в БД
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255'
        ]);

        // Пока закомментировано до добавления модели
        // $document = Document::create($validated);
        // return response()->json(['id' => $document->id]);

        return response()->json(['status' => 'success']);
    }
}
