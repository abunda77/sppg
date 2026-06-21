<?php

namespace App\Http\Controllers;

use App\Models\MenuBergizi;
use Illuminate\View\View;

class PublicMenuBergiziController extends Controller
{
    public function index(): View
    {
        $items = MenuBergizi::orderBy('nama')->get();

        return view('katalog.menu-bergizi', compact('items'));
    }
}
