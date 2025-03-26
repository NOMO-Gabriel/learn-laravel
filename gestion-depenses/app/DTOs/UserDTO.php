<?php

namespace App\DTOs;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password = null,
        public readonly ?string $profile_image = null,
        public readonly bool $is_active = true,
        public readonly ?string $role = 'user'
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validated();
        
        return new self(
            name: $validated['name'],
            email: $validated['email'],
            password: isset($validated['password']) ? Hash::make($validated['password']) : null,
            profile_image: $validated['profile_image'] ?? null,
            is_active: $validated['is_active'] ?? true,
            role: $validated['role'] ?? 'user'
        );
    }

    public static function fromModel(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            profile_image: $user->profile_image,
            is_active: $user->is_active,
            role: $user->roles->first()?->name ?? 'user'
        );
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];
        
        if ($this->password) {
            $data['password'] = $this->password;
        }
        
        if ($this->profile_image) {
            $data['profile_image'] = $this->profile_image;
        }
        
        return $data;
    }
}
