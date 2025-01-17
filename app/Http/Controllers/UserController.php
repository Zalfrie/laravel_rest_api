<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'name' => 'required|string|min:3|max:50',
        ]);

        // Start a database transaction to ensure atomicity
        \DB::beginTransaction();

        try {
            // Create user
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'name' => $validated['name'],
            ]);

            // Create an initial order for the user (or multiple if needed)
            $initialOrders = [
                ['user_id' => $user->id, 'created_at' => now()],
            ];
            Order::insert($initialOrders);

            // Commit the transaction
            \DB::commit();

            // Send emails
            Mail::to($user->email)->send(new \App\Mail\AccountCreated($user));
            Mail::to(config('mail.admin_email'))->send(new \App\Mail\NewUserNotification($user));

            // Return response
            return response()->json([
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'created_at' => $user->created_at,
            ], 201);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            \DB::rollBack();
            return response()->json(['error' => 'User creation failed.'], 500);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        $search = $request->query('search');
        $page = $request->query('page', 1);
        $sortBy = $request->query('sortBy', 'created_at');

        // Query users with filtering and sorting
        $usersQuery = User::withCount('orders')
            ->where('active', true)
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->orderBy($sortBy);

        // Paginate results
        $users = $usersQuery->paginate(10, ['id', 'email', 'name', 'created_at']);

        return response()->json([
            'page' => $page,
            'users' => $users->items(),
        ], 200);
    }
}
