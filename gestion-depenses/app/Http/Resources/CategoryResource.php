<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            // Relations conditionnelles
            'expenses_count' => $this->when($request->has('include_counts'), function () {
                return $this->expenses->count();
            }),
            'incomes_count' => $this->when($request->has('include_counts'), function () {
                return $this->incomes->count();
            }),
            // Relations chargées (eager loaded)
            'user' => new UserResource($this->whenLoaded('user')),
            'expenses' => ExpenseResource::collection($this->whenLoaded('expenses')),
            'incomes' => IncomeResource::collection($this->whenLoaded('incomes')),
        ];
    }
}