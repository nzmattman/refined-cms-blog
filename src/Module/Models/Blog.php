<?php

namespace RefinedDigital\Blog\Module\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use RefinedDigital\CMS\Modules\Core\Models\CoreModel;
use RefinedDigital\CMS\Modules\Pages\Traits\IsPage;
use RefinedDigital\CMS\Modules\Tags\Traits\Taggable;

class Blog extends CoreModel
{
    use SoftDeletes, IsPage, Taggable;

    protected $order = [ 'column' => 'published_at', 'direction' => 'desc'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'published_at'];

    protected $fillable = [
        'published_at', 'active', 'position', 'name', 'image', 'content',
    ];

    protected $appends = [ 'excerpt' ];

    protected $hidden = [ 'taggables' ];

    /**
     * The fields to be displayed for creating / editing
     *
     * @var array
     */
    public $formFields = [
        [
            'name' => 'Content',
            'blocks' => [
                'left' => [
                    [
                        'name' => 'Content',
                        'fields' => [
                            [
                                [ 'label' => 'Active', 'name' => 'active', 'required' => true, 'type' => 'select', 'options' => [1 => 'Yes', 0 => 'No'] ],
                                [ 'label' => 'Date', 'name' => 'published_at', 'required' => true, 'type' => 'datetime' ],
                            ],
                            [
                                [ 'label' => 'Image', 'name' => 'image', 'required' => true, 'type' => 'image' ],
                            ],
                            [
                                [ 'label' => 'Heading', 'name' => 'name', 'required' => true, 'attrs' => ['v-model' => 'content.name', '@keyup' => 'updateSlug' ] ],
                            ],
                            [
                                [ 'label' => 'Content', 'name' => 'content', 'required' => true, 'type' => 'richtext' ],
                            ]
                        ]
                    ]
                ],
                'right' => [
                    [
                        'name' => 'Tags',
                        'fields' => [
                            [
                                [ 'label' => 'Tags', 'name' => 'tags', 'type' => 'tags', 'hideLabel' => true, 'tagType'=> 'tags'],
                            ]
                        ]
                    ],
                    [
                        'name' => 'Categories',
                        'fields' => [
                            [
                                [ 'label' => 'Categories', 'name' => 'categories', 'type' => 'tags', 'hideLabel' => true, 'tagType'=> 'categories'],
                            ]
                        ]
                    ],
                ]
            ]
        ]
    ];

    public function getExcerptAttribute()
    {
        $content = strip_tags($this->content);

        $excerpt = substr($content, 0, 200);
        if (strlen($content) > $excerpt) {
            $excerpt .= '...';
        }

        return $excerpt;

    }
}
