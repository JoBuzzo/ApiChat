<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\MessageBag;

trait HttpResponses
{
  public static function response(string $message, string|int $status, array|Model|JsonResource|Collection|LengthAwarePaginator $data = [])
  {
    return response()->json([
      'message' => $message,
      'status' => $status,
      'data' => $data
    ], $status);
  }

  public static function error(string $message, string|int $status, array|MessageBag|string $errors = [], array $data = [])
  {
    return response()->json([
      'message' => $message,
      'status' => $status,
      'errors' => $errors,
      'data' => $data
    ], $status);
  }

  public static function noContent()
  {
    return response()->noContent();
  }
}
