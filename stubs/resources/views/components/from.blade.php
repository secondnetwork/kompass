<form wire:submit.prevent="submit"  class="contactform">

    <div class="grid-2 gap">
        <div class="form-group">
            <label for="exampleInputName">E-Mail-Adresse</label>
            <input type="text" class="form-control" id="exampleInputName"  wire:model="emailadresse">
            @error('emailadresse') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="exampleInputName">Titel</label>
            <input placeholder="Optional" type="text" class="form-control" id="exampleInputName"  wire:model="titel">
            @error('titel') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

    </div>


    <div class="grid-2 gap">
        <div class="form-group">
            <label for="exampleInputName">Anrede</label>
                <div class="flex gap-4">

                    <div class="flex gap-1 "><label><input type="radio" name="anrede" value="Herr" wire:model="anrede"><span></span></label><div> Herr </div></div>
                    <div class="flex gap-1 "><label><input type="radio" name="anrede" value="Frau" wire:model="anrede"><span></span></label><div> Frau </div></div>
                    <div class="flex gap-1 "><label><input type="radio" name="anrede" value="Divers" wire:model="anrede"><span></span></label><div> Divers </div></div>
                </div>


                @error('anrede') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

        </div>
        <select name="" id="">
            <option value="sad">asdad</option>
            <option value="sad">asdad</option>
        </select>
    <div class="grid-2 gap">
        <div class="form-group">
            <label for="exampleInputName">Vorname</label>
            <input type="text" class="form-control" id="exampleInputName"  wire:model="vorname">
            @error('vorname') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="exampleInputName">Nachname</label>
            <input type="text" class="form-control" id="exampleInputName"  wire:model="nachname">
            @error('nachname') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


    </div>

    <div class="grid-2 gap">
        <div class="form-group">
            <label for="exampleInputName">Institution/Firma</label>
            <input type="text" class="form-control" id="exampleInputName" wire:model="firma">
            @error('firma') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="exampleInputName">Position</label>
            <input placeholder="Optional" type="text" class="form-control" id="exampleInputName"  wire:model="position">
            @error('position') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


    </div>

    <div class="form-group">
        <label for="exampleInputName">Strasse / Hausnummer</label>
        <input type="text" class="form-control" id="exampleInputName"  wire:model="adresse">
        @error('adresse') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="grid-2-3 gap">
        <div class="form-group">
            <label for="exampleInputName">PLZ</label>
            <input pattern="^[0-9]{5}$" maxlength="5" type="text" class="form-control" id="exampleInputName"  wire:model="zip">
            @error('zip') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    <div class="form-group">
        <label for="exampleInputName">Stadt</label>
        <input type="text" class="form-control" id="exampleInputName"  wire:model="city">
        @error('city') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    </div>





    <br>
    <div x-data="{ open: false }" >
        <div style="display: flex"><span style="margin-right: 1rem">Ich bringe eine Begleitung mit: </span><span class="default-checkbox"><label><input type="checkbox" @click="open = ! open"></input><span></span></label></span></div>
        <div class="gap">
        <div class="form-group" x-show="open" x-transition>

            <input type="text" class="form-control" id="exampleInputName" placeholder="Name deiner Begleitung"  wire:model="begleitung">

        </div>

          </div>
    </div>



    <br>



    <div class="">

        <div class="default-checkbox"><label><input type="checkbox" name="dsgvo" value="true" wire:model="dsgvo"><span></span></label><div class="font-semibold">Datenschutz</div></div>

    </div>

        <div class="mt-4">
            <div class="default-checkbox"><label><input type="checkbox" name="werbung" value="JA" wire:model="werbung"><span></span></label><div class="font-semibold"></div></div>

        </div>



{{--
    <div class="footer-info font-semibold py-8">* Pflichtfeldangaben</div> --}}
    <br class="clear">
    @if(count($errors) > 0) <div><p class="text-danger">Bitte überprüfen Sie Ihre Eingaben und füllen Sie alle rot markierten Felder aus!</p></div> @endif
    <button class="btn btn-primary bg-ruby-500 py-2 px-8 rounded text-white font-semibold text-lg" type="submit">Anmelden</button>

    <button class="btn btn-primary py-2 px-8 rounded font-semibold text-lg" type="submit"
      disabled="">Anmelden</button>

</form>
