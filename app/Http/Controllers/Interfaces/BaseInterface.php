<?php

namespace App\Http\Controllers\Interfaces;

use Illuminate\Http\Request;

interface BaseInterface
{	
    public function index(Request $request);

    public function create(Request $request, $id = false);

    public function edit(Request $request, $id);

    public function store(Request $request);

    public function update(Request $request, $id);
    
    public function destroy(Request $request, $id);
}