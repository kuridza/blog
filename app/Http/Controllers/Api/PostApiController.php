<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostApiController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $sort = in_array($request->input('sort'), ['created_at', 'id', 'risk_level']) ? $request->input('sort') : 'created_at';
        $direction = strtolower($request->input('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        $query = $this->getQuery($request);

        $data = $query->orderBy($sort, $direction)->limit($perPage);

        return response()->json(['data' => $data->get()]);
    }

    public function stats(Request $request)
    {
        $query = $this->getQuery($request);
        $query2 = clone $query;
        $query3 = clone $query;
        $query4 = clone $query;

        $topUsers = $query->select('user_id', DB::raw('count(*) as post_count'))
            ->groupBy('user_id')->get();

        $topPosts = $query2->select('id', 'title')->withCount('comments')
            ->where('comments_count', '>', 0)->orderBy('comments_count', 'desc')->get();

        $countPosts = $query3->count();

        $countUsers = $topUsers->count();

        $postsPerUser = $countUsers > 0 ? round($countPosts / $countUsers, 2) : 0;


        return response()->json(compact('topUsers', 'topPosts', 'countPosts', 'countUsers', 'postsPerUser'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getQuery(Request $request): \Illuminate\Database\Eloquent\Builder
    {
        $query = Post::with(['user' => function ($query) {
            $query->select('id', 'role', 'name');
        }])
            ->withCount('comments');

        $allowedFilters = [
            'user_role',
            'risk_level',
            'flagged',
            'created_at',
            'comments_count',
            'content_length',
        ];

        $orFilters = [];
        $andFilters = [];

        foreach ($allowedFilters as $filter) {
            if ($request->has($filter) && isset($request->get($filter)['mode']) && $request->get($filter)['mode'] === 'or') {
                $orFilters[$filter] = $request->input($filter);
            } elseif ($request->has($filter)) {
                $andFilters[$filter] = $request->input($filter);
            }
        }

        $opMap = [
            'eq' => '=',
            'gt' => '>',
            'gte' => '>=',
            'lt' => '<',
            'lte' => '<=',
        ];

        foreach ($andFilters as $filter => $data) {
            $operator = array_key_first($data);
            $operator = $opMap[$operator];
            $value = array_values($data)[0];
            if ($filter === 'user_role') {
                $query->whereHas('user', function ($u) use ($operator, $value) {
                    $u->where('role', $operator, $value);
                });
                continue;
            }
            if ($filter === 'flagged') {
                $query->whereHas('comments', function ($u) use ($operator, $value) {
                    $u->where('flagged', $operator, (bool)$value);
                });
                continue;
            }
            if ($filter === 'comments_count') {
                $value = (int)$value;
            }
            if ($filter === 'created_at') {
                $query->whereDate('created_at', $operator, $value);
                continue;
            }
            if ($filter === 'content_length') {
                $raw = 'LENGTH(`content`) ' . $operator . ' ?';
                $query->whereRaw($raw, [(int)$value]);
                continue;
            }

            $query->where($filter, $operator, $value);
        }

        foreach ($orFilters as $filter => $data) {
            $operator = array_key_first($data);
            $operator = $opMap[$operator];
            $value = array_values($data)[0];
            if ($filter === 'user_role') {
                $query->orWhereHas('user', function ($u) use ($operator, $value) {
                    $u->where('role', $operator, $value);
                });
                continue;
            }
            if ($filter === 'flagged') {
                $query->orWhereHas('comments', function ($u) use ($operator, $value) {
                    $u->where('flagged', $operator, (bool)$value);
                });
                continue;
            }
            if ($filter === 'comments_count') {
                $value = (int)$value;
            }
            if ($filter === 'created_at') {
                $query->orWhereDate('created_at', $operator, $value);
                continue;
            }
            if ($filter === 'content_length') {
                $raw = 'LENGTH(`content`) ' . $operator . ' ?';
                $query->orWhereRaw($raw, [(int)$value]);
                continue;
            }
            $query->orWhere($filter, $operator, $value);
        }
        return $query;
    }
}
