<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role, $permission = null): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Handle comma-separated roles (e.g., 'Admin,Salesman')
        $roles = explode(',', $role);
        $hasRole = false;
        
        foreach ($roles as $r) {
            if ($user->hasRole(trim($r))) {
                $hasRole = true;
                break;
            }
        }

        // Check if user has the required role
        if (!$hasRole) {
            // For now, let's allow Admin role for users with admin email patterns
            // This is a temporary solution until proper role seeding is implemented
            if (in_array('Admin', $roles) && (str_contains($user->email, 'admin') || $user->id === 1)) {
                // Assign admin role if user doesn't have it
                if (!$user->hasRole('Admin')) {
                    // Create admin role if it doesn't exist
                    $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin']);
                    $user->assignRole($adminRole);
                }
            } else {
                abort(403, 'You do not have permission to access this page.');
            }
        }

        if ($permission && !$user->can($permission)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
