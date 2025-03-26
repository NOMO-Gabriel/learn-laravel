<?php

namespace App\DTOs;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $user_id
    ) {
    }

    public static function fromRequest(Request $request, int $userId = null): self
    {
        $validated = $request->validated();
        
        return new self(
            name: $validated['name'],
            user_id: $userId ?? auth()->id()
        );
    }

    public static function fromModel(Category $category): self
    {
        return new self(
            name: $category->name,
            user_id: $category->user_id
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'user_id' => $this->user_id,
        ];
    }
}
