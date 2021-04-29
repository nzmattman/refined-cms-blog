<?php

namespace RefinedDigital\Blog\Module\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use RefinedDigital\CMS\Modules\Core\Models\CoreModel;
use RefinedDigital\CMS\Modules\Core\Traits\IsArticle;
use RefinedDigital\CMS\Modules\Pages\Traits\IsPage;
use RefinedDigital\CMS\Modules\Tags\Traits\Taggable;

class Blog extends CoreModel
{
    use SoftDeletes, IsPage, Taggable, IsArticle;

    protected $order = [ 'column' => 'published_at', 'direction' => 'desc'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'published_at'];

    protected $fillable = [
        'published_at', 'active', 'position', 'name', 'image', 'content', 'data', 'external_link', 'file',
    ];

    protected $appends = [ 'excerpt' ];

    protected $hidden = [ 'taggables' ];

    protected $casts = [
        'data' => 'object'
    ];

    /**
     * The fields to be displayed for creating / editing
     *
     * @var array
     */
    public $formFields = [
        [
            'name' => 'Content',
            'sections' => [
                'left' => [
                    'blocks' => [
                        [
                            'name' => 'Content',
                            'fields' => [
                                [
                                    [ 'label' => 'Heading', 'name' => 'name', 'required' => true, 'attrs' => ['v-model' => 'content.name', '@keyup' => 'updateSlug' ] ],
                                    [ 'label' => 'Date', 'name' => 'published_at', 'required' => true, 'type' => 'datetime' ],
                                ],
                                [
                                    [ 'label' => 'Content', 'name' => 'content', 'required' => true, 'type' => 'richtext' ],
                                ],
                            ]
                        ]
                    ]
                ],
                'right' => [
                    'blocks' => [
                        [
                            'name' => 'Settings',
                            'fields' => [
                                [
                                    [ 'label' => 'Active', 'name' => 'active', 'required' => true, 'type' => 'select', 'options' => [1 => 'Yes', 0 => 'No'] ],
                                ],
                            ]
                        ],
                        [
                            'name' => 'Image',
                            'fields' => [
                                [
                                    [ 'label' => 'Image', 'name' => 'image', 'required' => true, 'hideLabel' => true, 'type' => 'image' ],
                                ],
                            ]
                        ],
                        [
                            'name' => 'File',
                            'fields' => [
                                [
                                    [ 'label' => 'File', 'name' => 'file', 'required' => true, 'hideLabel' => true, 'type' => 'file' ],
                                ],
                            ]
                        ],
                        [
                            'name' => 'External Link',
                            'fields' => [
                                [
                                    [ 'label' => 'External Link', 'name' => 'external_link', 'required' => true, 'hideLabel' => true, ],
                                ],
                            ]
                        ],
                    ]
                ]
            ]
        ]
    ];

    protected $blockTags = [
        'name' => 'Tags',
        'fields' => [
            [
                [ 'label' => 'Tags', 'name' => 'tags', 'type' => 'tags', 'hideLabel' => true, 'tagType'=> 'tags'],
            ]
        ]
    ];

    protected $blockCategories = [
        'name' => 'Categories',
        'fields' => [
            [
                [ 'label' => 'Categories', 'name' => 'categories', 'type' => 'tags', 'hideLabel' => true, 'tagType'=> 'categories'],
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


	public function scopePublished($query)
	{
	    $now = Carbon::now()->setTimezone(config('blog.timezone'));
	    $query->where('published_at', '<=', $now);
	}

	public function setFormFields()
    {
        $config = config('blog');
        $fields = $this->formFields;
        if ($config['categories']) {
            array_splice($fields[0]['sections']['right']['blocks'], 1, 0, [$this->blockCategories]);
        }
        if ($config['tags']) {
            array_splice($fields[0]['sections']['right']['blocks'], 1, 0, [$this->blockTags]);
        }

        if (isset($config['fields'], $config['fields']['external_link'], $config['fields']['external_link']['active']) && !$config['fields']['external_link']['active']) {
            unset($fields[0]['sections']['right']['blocks'][4]);
        }

        return $fields;
    }
}
