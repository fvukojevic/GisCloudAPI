<?php

namespace App\QueryExpanders;

use Illuminate\Http\Request;

class PostsQueryExpander
{
    private $limit;
    private $sort;
    private $order;

    private $validSortFields = ['title', 'body', 'user_id'];
    private $validOrderFields = ['asc', 'desc'];

    public function __construct(Request $request)
    {
        if($request->get('limit') !== null && is_numeric($request->get('limit'))) {
            $this->limit = $request->get('limit');
        }

        if($request->get('sort') !== null && in_array($request->get('sort'), $this->validSortFields, true)) {
            $this->sort = $request->get('sort');
        }

        if($request->get('order') !== null && in_array($request->get('order'), $this->validOrderFields, true)) {
            $this->order = $request->get('order');
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query)
    {
        if($this->sort) {
            $query->orderBy($this->sort, $this->order ?? 'asc');
        }

        if($this->limit) {
            $query->limit($this->limit);
        }

        return $query;
    }
}
