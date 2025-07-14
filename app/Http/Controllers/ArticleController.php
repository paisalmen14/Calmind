<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar semua artikel untuk pengguna.
     */
    public function index()
    {
        $articles = Article::latest()->paginate(9); // Ambil artikel terbaru, 9 per halaman
        return view('articles.index', compact('articles'));
    }

    /**
     * Menampilkan satu artikel secara detail.
     */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }
}