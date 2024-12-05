<?php

namespace Secondnetwork\Kompass\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Secondnetwork\Kompass\Models\Role;
use Secondnetwork\Kompass\Mail\Invitation;

class AccountForm extends Component
{
    use WithPagination;

    public $headers;

    public $action;

    public $selectedItem;

    public $FormDelete = false;

    public $FormAdd = false;

    public $FormEdit = false;

    public $perPage = 100;

    public $search = '';

    public $orderBy = 'created_at';

    public $orderAsc = true;

    public $role;

    public $Roles;

    public $anrede;

    public $name;

    public $email;

    public $password;

    public $success;

    protected $rules = [
        'name' => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:255',
        'email' => 'required|min:3|max:255|email',
        'role' => 'required',
    ];

    private function headerConfig()
    {
        return [
            'name' => 'Name',
            'status' => 'Status',
            'role' => 'Role',
            'edit' => '',
        ];
    }

    public function mount()
    {
        $this->headers = $this->headerConfig();
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;

        if ($action == 'add') {
            // This will show the modal on the frontend
            $this->reset(['name', 'email', 'password', 'role']);
            $this->FormEdit = true;
        }
        if ($action == 'update') {

            $this->dispatch('getModelId', $this->selectedItem);
            $model = User::findOrFail($this->selectedItem);

            foreach ($model->roles as $user_role) {
                $roleid = $user_role->id;
            }

            $this->role = $roleid;
            $this->name = $model->name;
            $this->email = $model->email;
            $this->FormEdit = true;
        }

        if ($action == 'delete') {
            $this->FormDelete = true;
        }
    }

    private function resultDate()
    {
        return User::where('name', 'like', '%'.$this->search.'%')->Paginate(100);
    }

    public function create($id)
    {
        $user = User::findOrFail($id);

        return view('auth.password.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $user->password = Hash::make($request->input('password'));
        $user->save();

        Auth::login($user);

        return redirect('/admin');
    }

    public function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%');
    }

    public function createOrUpdateUser()
    {
        $user = User::find($this->selectedItem);

        if ($user) {
            $validateData = $this->validate();
            $user->update($validateData);
            $user->roles()->sync($validateData['role']);
            $this->FormEdit = false;

        } else {
            $validate = $this->validate();
            $passwordrandom = Str::random(12);
            $passwordHash = Hash::make($passwordrandom);

            $now = Carbon::now()->toDateTimeString();
            $array = Arr::prepend($validate, $passwordrandom, 'password');
            $maildata = Arr::prepend($array, $now, 'email_verified_at');

            $arrayHash = Arr::prepend($validate, $passwordHash, 'password');
            $maildataBank = Arr::prepend($arrayHash, $now, 'email_verified_at');

            $user = User::create($maildataBank);
            $user->roles()->sync($maildataBank['role']);

            Mail::to($maildata['email'])->send(new Invitation($maildata));

            //->subject(__('Willkomenn bei Kompass für').env('APP_NAME'))
            $this->FormEdit = false;

            $this->reset(['name', 'email', 'password', 'role']);
        }

    }

    public function delete()
    {
        User::destroy($this->selectedItem);
        $this->FormDelete = false;
    }
    
    #[Layout('kompass::admin.layouts.app')] 
    public function render()
    {
        return view('kompass::livewire.account', [
            'users' => User::search($this->search)
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
                ->simplePaginate($this->perPage),
            'roles' => Role::all(),
        ]);
    }
}
