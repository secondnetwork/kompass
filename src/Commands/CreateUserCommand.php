<?php

namespace Secondnetwork\Kompass\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class CreateUserCommand extends Command implements PromptsForMissingInput
{
    public $signature = 'kompass:newuser
    {--name= : The name of the user}
    {--email= : A valid and unique email address}
    {--password= : The password for the user (min. 8 characters)}';

    public $description = 'Create a new User';

    /**
     * @var array{'name': string | null, 'email': string | null, 'password': string | null}
     */
    protected array $options;

    /**
     * @return array{'name': string, 'email': string, 'password': string}
     */
    protected function getUserData(): array
    {
        return [
            'name' => $this->options['name'] ?? text(
                label: 'Name',
                required: true,
            ),

            'email' => $this->options['email'] ?? text(
                label: __('E-Mail Address'),
                required: true,
                validate: fn (string $email): ?string => match (true) {
                    ! filter_var($email, FILTER_VALIDATE_EMAIL) => 'The email address must be valid.',
                    User::where('email', $email)->exists() => 'A user with this email address already exists',
                    default => null,
                },
            ),

            'password' => Hash::make($this->options['password'] ?? password(
                label: __('Password'),
                required: true,
            )),
        ];
    }

    protected function createUser(): Authenticatable
    {
        $now = Carbon::now()->toDateTimeString();
        $maildata = Arr::prepend($this->getUserData(), $now, 'email_verified_at');
        $user = User::create($maildata);
        // $user->roles()->sync(1);
        $user->syncRoles('admin');

        return $user;
    }

    protected function sendSuccessMessage(): void
    {
        $loginUrl = env('APP_URL').'/login';
        info("Logging at {$loginUrl} with you credentials.");
    }

    public function handle(): int
    {
        $this->options = $this->options();

        $addNewUser = select(
            label: 'Create new Admin User?',
            options: [
                true => 'Yes',
                false => 'no',
            ]
        );

        if ($addNewUser) {
            $this->createUser();
        }

        $this->sendSuccessMessage();

        return static::SUCCESS;
    }
}
