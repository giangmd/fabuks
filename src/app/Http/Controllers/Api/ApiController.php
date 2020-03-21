<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Pagination\LengthAwarePaginator;

class ApiController extends Controller
{
    protected function jsonRender($data = [])
    {
        $this->compacts['message'] = [
            'code' => 200,
            'status' => true,
        ];

        $compacts = array_merge($data, $this->compacts);

        return response()->json($compacts);
    }

    protected function reFormatPaginate(LengthAwarePaginator $paginate, callable $callback = null)
    {
        $data =  $paginate->items();

        if (is_callable($callback)) {
            $data = call_user_func_array($callback, [$paginate]);
        }

        $currentPage = $paginate->currentPage();

        return [
            'total' => $paginate->total(),
            'per_page' => $paginate->perPage(),
            'current_page' => $currentPage,
            'next_page' => ($paginate->lastPage() > $currentPage) ? $currentPage + 1 : null,
            'prev_page' => $currentPage - 1 ?: null,
            'data' => $data,
        ];
    }
}