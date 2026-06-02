<div class="dropdown dropdown-top w-full">

    <div tabindex="0" role="button" class="flex items-center gap-2 w-full p-2 rounded-md border border-base-300 hover:bg-base-200 transition-colors cursor-pointer">
        <div class="text-left flex-1 min-w-0">
            <span class="truncate block text-sm font-medium">{{ auth()->user()->name }}</span>
            <span class="text-xs text-base-content/50 truncate block">{{ auth()->user()->email }}</span>
        </div>
        <x-kompass::elements.avatar :user="auth()->user()" size="w-9" />
    </div>

    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box border border-base-300 shadow-md w-full mb-1 p-1 z-50">
        <li>
            <a href="/admin/profile" class="flex items-center gap-2 text-sm" style="padding: 0.6rem;">
                <x-tabler-user class="size-4" />
                {{ __('Account Settings') }}
            </a>
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-sm w-full text-error">
                    <x-tabler-logout class="size-4" />
                    {{ __('Logout') }}
                </button>
            </form>
        </li>
    </ul>

</div>
