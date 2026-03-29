<form wire:submit.prevent="submit" class="space-y-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-control">
            <label class="label">
                <span class="label-text">E-Mail-Adresse *</span>
            </label>
            <input type="email" class="input input-bordered" wire:model="emailadresse" required>
            @error('emailadresse') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Titel</span>
            </label>
            <input type="text" class="input input-bordered" placeholder="Optional" wire:model="titel">
            @error('titel') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-control">
            <label class="label">
                <span class="label-text">Anrede *</span>
            </label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="anrede" value="Herr" class="radio" wire:model="anrede">
                    <span>Herr</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="anrede" value="Frau" class="radio" wire:model="anrede">
                    <span>Frau</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="anrede" value="Divers" class="radio" wire:model="anrede">
                    <span>Divers</span>
                </label>
            </div>
            @error('anrede') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-control">
            <label class="label">
                <span class="label-text">Vorname *</span>
            </label>
            <input type="text" class="input input-bordered" wire:model="vorname">
            @error('vorname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="form-control">
            <label class="label">
                <span class="label-text">Nachname *</span>
            </label>
            <input type="text" class="input input-bordered" wire:model="nachname">
            @error('nachname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="form-control">
            <label class="label">
                <span class="label-text">Institution/Firma</span>
            </label>
            <input type="text" class="input input-bordered" wire:model="firma">
            @error('firma') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="form-control">
            <label class="label">
                <span class="label-text">Position</span>
            </label>
            <input type="text" class="input input-bordered" placeholder="Optional" wire:model="position">
            @error('position') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="form-control">
        <label class="label">
            <span class="label-text">Strasse / Hausnummer</span>
        </label>
        <input type="text" class="input input-bordered" wire:model="adresse">
        @error('adresse') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="form-control">
            <label class="label">
                <span class="label-text">PLZ *</span>
            </label>
            <input pattern="^[0-9]{5}$" maxlength="5" type="text" class="input input-bordered" wire:model="zip">
            @error('zip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="form-control md:col-span-2">
            <label class="label">
                <span class="label-text">Stadt *</span>
            </label>
            <input type="text" class="input input-bordered" wire:model="city">
            @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div x-data="{ open: false }">
        <div class="flex items-center gap-4">
            <span>Ich bringe eine Begleitung mit:</span>
            <input type="checkbox" class="checkbox" @click="open = ! open">
        </div>
        <div class="form-control mt-2" x-show="open" x-transition>
            <input type="text" class="input input-bordered" placeholder="Name deiner Begleitung" wire:model="begleitung">
        </div>
    </div>

    <div class="form-control">
        <label class="label cursor-pointer justify-start gap-4">
            <input type="checkbox" class="checkbox" name="dsgvo" value="true" wire:model="dsgvo" required>
            <span class="label-text font-semibold">Datenschutz *</span>
        </label>
    </div>

    <div class="form-control">
        <label class="label cursor-pointer justify-start gap-4">
            <input type="checkbox" class="checkbox" name="werbung" value="JA" wire:model="werbung">
            <span class="label-text font-semibold">Ich möchte Werbung erhalten</span>
        </label>
    </div>

    @if(count($errors) > 0)
        <div class="alert alert-error">
            <span>Bitte überprüfen Sie Ihre Eingaben und füllen Sie alle rot markierten Felder aus!</span>
        </div>
    @endif

    <button class="btn btn-primary bg-ruby-500 py-2 px-8 rounded text-white font-semibold text-lg" type="submit">
        Anmelden
    </button>

</form>
