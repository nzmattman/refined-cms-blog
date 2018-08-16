<?php

namespace RefinedDigital\Blog\Module\Http\Repositories;

use RefinedDigital\CMS\Modules\Core\Http\Repositories\CoreRepository;
use RefinedDigital\CMS\Modules\Tags\Models\Tag;

class BlogRepository extends CoreRepository
{

    public function __construct()
    {
        $this->setModel('RefinedDigital\Blog\Module\Models\Blog');
    }

    public function getForFront()
    {
        $data = $this->model::with(['meta', 'meta.template'])
            ->whereActive(1)
            ->published()
            ->search(['name','content'])
            ->orderBy('published_at', 'desc')
            ->paging(5);

        return $data;
    }

    public function getForFrontWithTags($tag, $type)
    {
        return $this->model::allWithTags([$tag], $type)
            ->whereActive(1)
            ->published()
            ->search(['name','content'])
            ->orderBy('published_at', 'desc')
            ->paging(5);

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

    public function getTags()
    {
        return $this->getTagCollection('tags', $this->model);
    }

    public function getCategories()
    {
        return $this->getTagCollection('categories', $this->model);
    }
}
