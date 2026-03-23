<?php
namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;

class Utils{
  

public function uploadImage($request)
{
    if ($request->hasFile('image')) {
        return $request->file('image')->store('users', 'public');
    }

    return null;
}
}