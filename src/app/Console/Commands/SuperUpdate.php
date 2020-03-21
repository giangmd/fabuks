<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;

use Hash;

class SuperUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'super:update {email} {password} {password_confirmation} {--create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the super-manager credentials';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arguments = $this->arguments();
        $options = $this->options();

        $valid = $this->validateArguments($arguments);
        if (!$valid['status']) {
            $this->error($valid['message']);
            exit();
        }

        $admin = User::where('role', 0)->first();
        if (empty($admin)) {
            // create admin
            if (!$options['create']) {
                $this->warn('This super-manager not found. Please add more option {--create} to create new super-manager.');
                exit();
            } else {
                $user = User::create([
                    'email'             => $arguments['email'],
                    'password'          => Hash::make($arguments['password']),
                    'name'              => '@root',
                    'first_name'        => 'root',
                    'last_name'         => '@',
                    'phone_number'      => '0909123456',
                    'role'              => 0,
                ]);
            }
        } else {
            // update admin
            $admin->email = $arguments['email'];
            $admin->password = Hash::make($arguments['password']);
            $admin->save();
        }

        $this->info('Update super-manager credentials successfully.');
    }

    protected function validateArguments($arguments)
    {
        $response = ['status' => false, 'message' => ''];

        if (empty($arguments['email']) || empty($arguments['password']) || empty($arguments['password_confirmation'])) {
            $response['message'] = "Email and Password is required.";
            return $response;
        }

        if (!$this->is_email($arguments['email'])) {
            $response['message'] = trans('validation.email', ['attribute' => 'email']);
            return $response;
        }

        if (strlen($arguments['password']) < 6) {
            $response['message'] = trans('validation.min.numeric', ['attribute' => 'Password', 'min' => 6]);
            return $response;
        }

        if ($arguments['password'] !== $arguments['password_confirmation']) {
            $response['message'] = trans('validation.same', ['attribute' => 'Password', 'other' => 'Password Confirmation']);
            return $response;
        }

        $response['status'] = true;
        return $response;
    }

    protected function is_email($email) {
        if (empty($email)) {
            return false;
        }

        // Remove all illegal characters from email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Validate e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}
