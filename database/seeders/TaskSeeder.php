<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();

        if (!$user) {
            $this->command->warn('Test user not found. Run UserSeeder first.');
            return;
        }

        Task::create([
            'user_id' => $user->id,
            'title' => 'Buy groceries',
            'description' => 'Milk, Bread, Eggs',
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(2)->toDateString(),
        ]);

        Task::create([
            'user_id' => $user->id,
            'title' => 'Submit project',
            'description' => 'Submit Laravel API assignment',
            'status' => 'in_progress',
            'due_date' => Carbon::now()->addDays(3)->toDateString(),
        ]);

        Task::create([
            'user_id' => $user->id,
            'title' => 'Call client',
            'description' => 'Discuss requirements',
            'status' => 'completed',
            'due_date' => Carbon::now()->subDay()->toDateString(),
        ]);
    }
}
