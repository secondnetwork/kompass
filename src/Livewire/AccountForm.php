<?php

namespace Secondnetwork\Kompass\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Secondnetwork\Kompass\Mail\Invitation;
use Secondnetwork\Kompass\Models\Role;

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
    // protected $queryString = ['search'];

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
            // 'id' => '#',
            'name' => 'Name',
            // 'title' => 'Title',
            'status' => 'Status',
            'role' => 'Role',
            'edit' => '',
        ];
    }

    // public function row()
    // {
    //     return [
    //         $user->id,
    //         UI::avatar(asset('storage/' . $user->avatar)),
    //         $user->name,
    //         $user->email,
    //         $user->active ? UI::icon('check', 'success') : '',
    //         ucfirst($user->type),
    //         $user->created_at->diffforHumans()
    //     ];
    // }
    public function mount()
    {
        $this->headers = $this->headerConfig();
        // $this->datarow = $this->row();
    }

    public function selectItem($itemId, $action)
    {
        $this->selectedItem = $itemId;

        if ($action == 'add') {
            // This will show the modal on the frontend
            // $this->reset(['name', 'email', 'password', 'role']);
            $this->FormAdd = true;
        }
        if ($action == 'update') {
            $this->dispatch('getModelId', $this->selectedItem);
            $model = User::findOrFail($this->selectedItem);
            // $this->Rolrs = Role::all();
            $roleid = '3';
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

    public function addNewUser()
    {
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
        $this->FormAdd = false;
        $this->reset(['name', 'email', 'password', 'role']);
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

    public function update()
    {
        $user = User::findOrFail($this->selectedItem);

        $validateData = $this->validate();

        $user->update($validateData);
        $user->roles()->sync($validateData['role']);
        // User::updateOrCreate()
        $this->FormEdit = false;
        $this->resetPage();
    }

    public function delete()
    {
        User::destroy($this->selectedItem);
        $this->FormDelete = false;
    }

    public function render()
    {
        //sleep(2);

        // $query = '%'.  dd(User::search);$this->searchTerm.'%';
        return view('kompass::livewire.account', [
            // 'users' => $this->resultDate(),
            'users' => User::search($this->search)
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
                ->simplePaginate($this->perPage),
            'roles' => Role::all(),
        ])->layout('kompass::admin.layouts.app');
    }
}
