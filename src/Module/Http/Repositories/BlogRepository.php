<?php

namespace RefinedDigital\Blog\Module\Http\Repositories;

use RefinedDigital\Blog\Module\Models\Blog;
use RefinedDigital\CMS\Modules\Core\Http\Repositories\CoreRepository;
use RefinedDigital\CMS\Modules\Core\Models\Uri;
use RefinedDigital\CMS\Modules\Tags\Models\Tag;

class BlogRepository extends CoreRepository
{

    public function __construct()
    {
        $this->setModel('RefinedDigital\Blog\Module\Models\Blog');
    }

    public function getForFront($perPage = 5, $excludeCurrent = false)
    {
        $query = Blog::with(['meta', 'meta.template'])
                     ->whereActive(1)
                     ->published()
                     ->search(['name','content'])
                     ->orderBy('published_at', 'desc');
        if ($excludeCurrent) {
            $uri = request()->segment(count(request()->segments()));
            $current = Uri::whereUri($uri)->first();

            if (isset($current->id)) {
                $query->where('id', '!=', $current->uriable_id);
            }
        }

        return $query->paging($perPage);
    }

    public function getForFrontWithTags($tag, $type, $perPage = 5)
    {
        return $this->model::allWithTags([$tag], $type)
            ->whereActive(1)
            ->published()
            ->search(['name','content'])
            ->orderBy('published_at', 'desc')
            ->paging($perPage);

    }

    public function getForHomePage($limit = 6)
    {
        return $this->model::with(['meta', 'meta.template'])
            ->whereActive(1)
            ->published()
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getRandom($limit = 6, $avoid = false)
    {
        $data = $this->model::with(['meta', 'meta.template'])
            ->whereActive(1)
            ->published()
            ->orderBy('published_at', 'desc')
        ;

        if ($avoid) {
            $data->where('id', '!=', $avoid);
        }

        return $data
            ->limit($limit)
            ->inRandomOrder()
            ->get();
    }

    public function getRandomWithTags($tag, $limit = 6, $avoid = false)
    {
        $data = $this->model::allWithTags([$tag->name], $tag->type)
            ->with(['meta', 'meta.template'])
            ->whereActive(1)
            ->published()
            ->orderBy('published_at', 'desc')
        ;

        if ($avoid) {
            $data->where('id', '!=', $avoid);
        }

        return $data
            ->limit($limit)
            ->inRandomOrder()
            ->get();
    }

    public function getByRelatedTags($tags, $limit = 6, $avoid = false)
    {
        // extract the ids
        $ids = array_map(function($tag) {
            return $tag['name'];
        }, $tags);

        $data = $this->model::whereIn('id', $ids)
            ->with(['meta', 'meta.template'])
            ->whereActive(1)
            ->published()
            ->orderBy('published_at', 'desc')
        ;

        if ($avoid) {
            $data->where('id', '!=', $avoid);
        }

        return $data
            ->limit($limit)
            ->inRandomOrder()
            ->get();
    }

    public function getFirstPostByCategory()
    {
        $categories = $this->getCategories();
        $data = collect([]);
        if ($categories->count()) {
            foreach($categories as $category) {
                $d = $this->model::whereActive(1)
                        ->whereHas('taggables', function($q) use ($category) {
                            return $q->where('tag_id', $category->id);
                        })
                        ->published()
                        ->orderBy('published_at', 'desc')
                        ->first();

                if (isset($d->id)) {
                    $d->theCategory = $category;
                    $data->push($d);
                }
            }
        }

        return $data;
    }

    public function getTags()
    {
        return $this->getTagCollection('tags', $this->model);
    }

    public function getCategories()
    {
        return $this->getTagCollection('categories', $this->model);
    }

    public function getForSelect()
    {
        $posts = Blog::active()->orderBy('name', 'asc')->get();
        $data = [];
        if ($posts->count()) {
            foreach ($posts as $post) {
                $data[] = [
                    'id' => $post->id,
                    'name' => $post->name,
                ];
            }
        }

        return $data;
    }

    public function getLoadMoreScripts($page, $perPage, $holder, $templateVariables = [])
    {
        return view()->make('blog::scripts')
            ->with(compact('page'))
            ->with(compact('perPage'))
            ->with(compact('holder'))
            ->with(compact('templateVariables'))
        ;

    }

    public function getFeatured($limit = null, $excludeCurrent = false)
    {
        $posts = Blog::
                     active()
                     ->published()
                     ->featured()
                     ->order();

        if ($limit) {
            $posts->limit($limit);
        }

        if ($excludeCurrent) {
            $posts->where('id', '!=', $excludeCurrent);
        }

        if ($limit && $limit == 1) {
            return $posts->first();
        }

        $data = $posts->get();

        if ($data->count() == 1) {
            return $data->first();
        }

        return $data;
    }
}
