<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
  // GET /api/users → solo compradores (sin password)
  public function index()
  {
    $users = User::where('role', 'comprador')
      ->select('id', 'name', 'email', 'role', 'created_at')
      ->orderBy('name')
      ->get();

    return response()->json([
      'status' => 'success',
      'total'  => $users->count(),
      'data'   => $users
    ]);
  }

  // GET /api/users/{id}
  public function show($id)
  {
    $user = User::select('id', 'name', 'email', 'role', 'created_at')
      ->find($id);

    if (!$user) {
      return response()->json([
        'status'  => 'error',
        'message' => 'Usuario no encontrado'
      ], 404);
    }

    return response()->json([
      'status' => 'success',
      'data'   => $user
    ]);
  }
}
