<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateOrResetAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'admin:create-or-reset {--email=} {--name=} {--password=}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new admin account or reset an existing admin password';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->option('email') ?: text(
            label: 'Email',
            placeholder: 'mygreatemail@greatdomain.com',
            required: true,
        );

        $name = $this->option('name') ?: text(
            label: 'Name',
            placeholder: 'John Doe',
            required: true,
        );

        $password = $this->option('password') ?: password(
            label: 'Password (leave empty to generate)',
            placeholder: 'Enter password or press Enter to auto-generate',
            required: false,
        );

        // Generate password if none provided
        if (empty($password)) {
            $password = $this->generateSecurePassword();
            $this->info('Generated secure password for admin account.');
        }

        // Create or update the admin user
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        if ($user->wasRecentlyCreated) {
            $this->info('✅ Admin account created successfully!');
        } else {
            $this->info('✅ Admin password updated successfully!');
        }

        $this->newLine();
        $this->line('📧 Email: <fg=green>'.$email.'</>');
        $this->line('🔐 Password: <fg=yellow>'.$password.'</>');
        $this->newLine();
        $this->warn('⚠️  Please store this password securely and change it after first login.');

        return self::SUCCESS;
    }

    /**
     * Generate a secure random password.
     */
    private function generateSecurePassword(): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';

        return substr(str_shuffle(str_repeat($characters, 16)), 0, 16);
    }
}
