@extends('layouts.index')

@section('meta-description')
<meta name="description" content="{{ $page->excerpt }}"/>
@stop

@section('facebook-og')
<?php
    if (isset($page->data->facebook_cover_photo)) {
        $image = image()->load($page->data->facebook_cover_photo)->width(1200)->height(630)->object();
    }
?>
<meta property="og:url"                content="{{ request()->url() }}" />
<meta property="og:type"               content="article" />
<meta property="og:title"              content="{{ $page->name }}" />
<meta property="og:description"        content="{{ $page->excerpt }}" />
@if (isset($image->src))
<meta property="og:image"              content="{{ asset($image->src) }}" />
@endif
@if(isset($image->width))
<meta property="og:image:width"        content="{{ $image->width }}" />
@endif
@if(isset($image->height))
<meta property="og:image:height"       content="{{ $image->height }}" />
@endif
@stop

@section('template')

  <div class="page__block page__article-details page__block--no-bottom-padding">
    <div class="holder">
      <article class="article-details">
        @if ($page->image)
          <figure class="article-details__image">
            {!! image()->load($page->image)->width(840)->height(535)->get() !!}
          </figure>
        @endif

        <header class="article-details__header">
          <h2 class="article-details__date">{{ $page->published_at->format('d.m.y') }}</h2>
          <h1 class="article-details__heading">{{ $page->name }}</h1>
        </header>

        <div class="article-details__content">
          {!! $page->content !!}
        </div>

      </article>

    </div>
  </div>
@stop


