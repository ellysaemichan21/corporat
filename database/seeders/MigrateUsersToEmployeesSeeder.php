<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class MigrateUsersToEmployeesSeeder extends Seeder
{
    /**
     * Move all non-admin users (those with a role) from users table
     * into the employees table, then delete them from users.
     */
    public function run(): void
    {
        // Grab every user that has a role set (i.e. not the main admin account)
        $employeeUsers = User::whereNotNull('role')->get();

        foreach ($employeeUsers as $user) {
            // Avoid duplicates if this has already been run
            $exists = Employee::where('name', $user->name)->exists();
            if ($exists) {
                $this->command->line("  ⚠ Skipped (already exists): {$user->name}");
                continue;
            }

            Employee::create([
                'name'      => $user->name,
                'phone'     => null,
                'role'      => $user->role,
                'join_date' => $user->created_at->toDateString(),
                'status'    => 'active',
                'photo'     => null, // user will upload manually
            ]);

            $this->command->line("  ✔ Moved to employees: {$user->name} ({$user->role})");

            // Delete the user account — they are not login accounts
            $user->delete();
        }

        $this->command->info('Done! Employee users have been moved to the employees table.');
    }
}
