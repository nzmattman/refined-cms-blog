@extends('layouts.index')

@section('template')

  @if (isset($page->listing) && $page->listing && $page->listing->count())
    <div class="page__articles">
      @foreach ($page->listing as $article)
        @php
          $articleLink = request()->segment(1).'/'.$article->meta->uri;
        @endphp
        <article class="article">
          <figure class="article__image">
            <a href="{{ $articleLink }}">
              <img src="{{ asset(image()->load($article->image)->width(320)->height(500)->string()) }}" />
            </a>
          </figure>
          <div class="article__body">
            <header>
              <h3 class="article__heading">{{ $article->name }}</h3>
              <h4 class="article__date">Posted: {{ $article->published_at->format('d/m/y') }}</h4>
            </header>
            <div class="article__content">
              {!! $article->excerpt !!}
            </div>
          </div>
        </article>
      @endforeach
    </div>
  @endif

@stop


