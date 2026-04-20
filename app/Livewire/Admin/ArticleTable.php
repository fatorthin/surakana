<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleTable extends Component
{
    use WithPagination;

    public string $notification = '';

    public function delete(Article $article): void
    {
        $title = $article->title;
        $article->delete();

        $this->notification = "Artikel \"{$title}\" berhasil dihapus.";
        $this->js('setTimeout(() => $wire.set("notification", ""), 3000)');
    }

    public function render()
    {
        $articles = Article::query()->with('author')->latest()->paginate(10);

        return view('livewire.admin.article-table', compact('articles'));
    }
}
