<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class ArticleForm extends Component
{
    public ?int $articleId = null;

    public string $title = '';
    public string $excerpt = '';
    public string $content = '';
    public string $image_url = '';
    public string $published_at = '';
    public bool $is_published = false;

    public function mount(?Article $article = null): void
    {
        if ($article && $article->exists) {
            $this->articleId    = $article->id;
            $this->title        = $article->title;
            $this->excerpt      = $article->excerpt ?? '';
            $this->content      = $article->content;
            $this->image_url    = $article->image_url ?? '';
            $this->published_at = optional($article->published_at)->format('Y-m-d\TH:i') ?? '';
            $this->is_published = (bool) $article->is_published;
        }
    }

    public function save(): void
    {
        $this->validate([
            'title'        => ['required', 'string', 'max:255'],
            'excerpt'      => ['nullable', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'image_url'    => ['nullable', 'url'],
            'published_at' => ['nullable', 'date'],
        ]);

        $baseSlug = Str::slug($this->title);
        $slug     = $baseSlug;
        $counter  = 1;

        while (Article::query()
            ->where('slug', $slug)
            ->when($this->articleId, fn($q) => $q->whereKeyNot($this->articleId))
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $data = [
            'title'        => $this->title,
            'excerpt'      => $this->excerpt ?: null,
            'content'      => $this->content,
            'image_url'    => $this->image_url ?: null,
            'slug'         => $slug,
            'is_published' => $this->is_published,
            'published_at' => $this->is_published
                ? ($this->published_at ?: now())
                : null,
        ];

        if ($this->articleId) {
            Article::findOrFail($this->articleId)->update($data);
            $message = 'Artikel berhasil diperbarui.';
        } else {
            Article::query()->create($data + ['author_id' => Auth::id()]);
            $message = 'Artikel berhasil dibuat.';
        }

        session()->flash('status', $message);
        $this->redirect(route('admin.articles.index'));
    }

    public function render()
    {
        return view('livewire.admin.article-form');
    }
}
