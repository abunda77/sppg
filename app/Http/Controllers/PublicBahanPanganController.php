<?php

namespace App\Http\Controllers;

use App\Models\BahanPangan;
use Illuminate\View\View;

class PublicBahanPanganController extends Controller
{
    public function index(): View
    {
        $items = BahanPangan::orderBy('nama')->get();

        return view('katalog.bahan-pangan', compact('items'));
    }
}
