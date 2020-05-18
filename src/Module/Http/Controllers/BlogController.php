<?php

namespace RefinedDigital\Blog\Module\Http\Controllers;

use Illuminate\Http\Request;
use RefinedDigital\CMS\Modules\Core\Http\Controllers\CoreController;
use RefinedDigital\Blog\Module\Http\Requests\BlogRequest;
use RefinedDigital\Blog\Module\Http\Repositories\BlogRepository;
use RefinedDigital\CMS\Modules\Core\Http\Repositories\CoreRepository;

class BlogController extends CoreController
{
    protected $model = 'RefinedDigital\Blog\Module\Models\Blog';
    protected $prefix = 'blog::';
    protected $route = 'blog';
    protected $heading = 'Blog';
    protected $button = 'a Post';

    protected $blogRepository;

    public function __construct(CoreRepository $coreRepository)
    {
        $this->blogRepository = new BlogRepository();
        $this->blogRepository->setModel($this->model);

        parent::__construct($coreRepository);
    }

    public function setup() {

        $table = new \stdClass();
        $table->fields = [
            (object) [ 'name' => 'Name', 'field' => 'name', 'sortable' => true],
            (object) [ 'name' => 'Date', 'field' => 'published_at', 'sortable' => true, 'type' => 'datetime', 'classes' => ['data-table__cell--date-time']],
            (object) [ 'name' => 'Active', 'field' => 'active', 'type'=> 'select', 'options' => [1 => 'Yes', 0 => 'No'], 'sortable' => true, 'classes' => ['data-table__cell--active']],
        ];

        $table->routes = (object) [
            'edit'      => 'refined.blog.edit',
            'destroy'   => 'refined.blog.destroy'
        ];
        $table->sortable = false;

        $this->table = $table;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($item)
    {
        // get the instance
        $data = $this->model::findOrFail($item);

        // todo: make this more dynamic
        if (isset($data->data) && is_object($data->data)) {
            foreach ($data->data as $key => $value) {
                $data->{'data__'.$key} = $value;
            }
        }

        return parent::edit($data);
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(BlogRequest $request)
    {
        return parent::storeRecord($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogRequest $request, $id)
    {
        return parent::updateRecord($request, $id);
    }

    /**
     * Grab the tags
     */
    public function getAllTags()
    {
        $tags = $this->blogRepository->getAllTags();

        return response()->json($tags);
    }


    public function getForFront(Request $request)
    {
        if ($request->has('tag')) {
            $data = $this->blogRepository->getForFrontWithTags($request->input('tag.name'), $request->input('tag.type'), $request->get('perPage'));
        } else {
            $data = $this->blogRepository->getForFront($request->get('perPage'));
        }
        return parent::formatGetForFront($data, $request);
    }



}
