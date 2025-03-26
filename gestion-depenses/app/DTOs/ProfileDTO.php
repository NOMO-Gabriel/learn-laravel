<?php

namespace App\DTOs;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password = null
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->validated('name', $request->user()->name),
            email: $request->validated('email', $request->user()->email),
            password: $request->validated('password') ? Hash::make($request->validated('password')) : null
        );
    }

    public static function fromModel(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];
        
        if ($this->password) {
            $data['password'] = $this->password;
        }
        
        return $data;
    }
}