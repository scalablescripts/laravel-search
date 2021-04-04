<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function frontend()
    {
        return Product::all();
    }

    public function backend(Request $request)
    {
        $query = Product::query();

        if ($s = $request->input('s')) {
            $query->whereRaw("title LIKE '%" . $s . "%'")
                ->orWhereRaw("description LIKE '%" . $s . "%'");
        }

        if ($sort = $request->input('sort')) {
            $query->orderBy('price', $sort);
        }

        $perPage = 9;
        $page = $request->input('page', 1);
        $total = $query->count();

        $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

        return [
            'data' => $result,
            'total' => $total,
            'page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
}
