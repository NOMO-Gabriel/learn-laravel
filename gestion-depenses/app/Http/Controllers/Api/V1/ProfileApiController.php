<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\ProfileDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileUpdateRequest;
use App\Http\Requests\Api\ProfileImageUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileApiController extends Controller
{
    /**
     * Get the authenticated user's profile.
     */
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }
    
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        
        // Création du DTO à partir de la requête validée
        $profileDTO = ProfileDTO::fromRequest($request);
        
        // Vérifier si l'email est modifié
        if ($user->email !== $profileDTO->email) {
            $user->email_verified_at = null;
        }
        
        // Mise à jour des informations
        $user->update($profileDTO->toArray());
        
        return new UserResource($user);
    }
    
    /**
     * Update the user's profile image.
     */
    public function updateImage(ProfileImageUpdateRequest $request)
    {
        $user = $request->user();
        
        // Supprimer l'ancienne image si elle existe
        if ($user->profile_image) {
            Storage::disk('public')->delete('profiles/' . $user->profile_image);
        }
        
        // Télécharger la nouvelle image
        $imageName = time() . '_' . $user->id . '.' . $request->profile_image->extension();
        $request->profile_image->storeAs('profiles', $imageName, 'public');
        
        // Mettre à jour l'utilisateur
        $user->profile_image = $imageName;
        $user->save();
        
        return new UserResource($user);
    }
    
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
        
        $user = $request->user();
        
        // Supprimer l'image de profil si elle existe
        if ($user->profile_image) {
            Storage::disk('public')->delete('profiles/' . $user->profile_image);
        }
        
        // Révoquer tous les tokens
        $user->tokens()->delete();
        
        // Supprimer l'utilisateur
        $user->delete();
        
        return response()->json([
            'message' => 'Account successfully deleted'
        ]);
    }
}