<?php

namespace App\DTOs;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly string $description,
        public readonly string $date,
        public readonly int $category_id,
        public readonly int $user_id
    ) {
    }

    public static function fromRequest(Request $request, int $userId = null): self
    {
        $validated = $request->validated();
        
        return new self(
            amount: (float) $validated['amount'],
            description: $validated['description'],
            date: $validated['date'],
            category_id: (int) $validated['category_id'],
            user_id: $userId ?? auth()->id()
        );
    }

    public static function fromModel(Income $income): self
    {
        return new self(
            amount: $income->amount,
            description: $income->description,
            date: $income->date->format('Y-m-d'),
            category_id: $income->category_id,
            user_id: $income->user_id
        );
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'description' => $this->description,
            'date' => $this->date,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
        ];
    }
}