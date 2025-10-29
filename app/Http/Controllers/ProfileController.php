<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show(Request $request, $id = null)
    {
        $user = $id ? User::with(['farmerProfile', 'buyerProfile'])->findOrFail($id) : $request->user();

        if ($user->isFarmer()) {
            $user->load(['farmerProfile', 'plots.images', 'products', 'byproducts']);
        } else {
            $user->load('buyerProfile');
        }

        return Inertia::render('Profile/Show', [
            'user' => $user,
            'isOwnProfile' => ! $id || $id == $request->user()->id,
        ]);
    }

    public function edit(Request $request)
    {
        $user = $request->user();

        if ($user->isFarmer()) {
            $user->load('farmerProfile');
        } else {
            $user->load('buyerProfile');
        }

        return Inertia::render('Profile/Edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'bio' => 'nullable|string|max:1000',
            'farm_name' => 'nullable|string|max:255',
            'farm_size' => 'nullable|numeric|min:0',
            'years_experience' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        if ($user->isFarmer()) {
            $user->farmerProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'bio' => $validated['bio'] ?? null,
                    'farm_name' => $validated['farm_name'] ?? null,
                    'farm_size' => $validated['farm_size'] ?? null,
                    'years_experience' => $validated['years_experience'] ?? null,
                    'location' => $validated['location'] ?? null,
                ]
            );
        } else {
            $user->buyerProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'bio' => $validated['bio'] ?? null,
                    'company_name' => $validated['company_name'] ?? null,
                    'business_type' => $validated['business_type'] ?? null,
                ]
            );
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
    }
}
