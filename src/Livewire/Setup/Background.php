<?php

namespace Secondnetwork\Kompass\Livewire\Setup;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Importiere Str für die Pfadprüfung
use Secondnetwork\Kompass\Models\Setting;

class Background extends Component
{
    use WithFileUploads;

    public $color;
    public $adminBackground; // Wird den Bildpfad speichern
    public $image_overlay_color;
    public $image_overlay_opacity; // Wird den Wert 0-100 speichern (für die Anzeige/Eingabe)

    // Definiere die Datenbank-Keys für die Einstellungen
    private $dbKeyImage = 'background_image';
    private $dbKeyOverlayColor = 'background_image_overlay_color';
    private $dbKeyOverlayOpacity = 'background_image_overlay_opacity'; // Datenbank speichert 0-1

    protected $listeners = ['component:refresh' => '$refresh'];

    public function mount()
    {
        // Lese Einstellungen aus der Datenbank, Gruppe 'global'
        $globalSettings = Setting::global()->get()->keyBy('key');

        // Weise die Werte den Component-Eigenschaften zu
        // Verwende optional() und ?? '' für sicheren Zugriff
        $this->adminBackground = optional($globalSettings->get($this->dbKeyImage))->data ?? '';
        $this->image_overlay_color = optional($globalSettings->get($this->dbKeyOverlayColor))->data ?? ''; // Standardwert
        // Lese die Opazität aus der DB (0-1) und multipliziere für die Anzeige (0-100)
        $dbOpacity = optional($globalSettings->get($this->dbKeyOverlayOpacity))->data ?? '0'; // Standardwert 0
        $this->image_overlay_opacity = (floatval($dbOpacity) * 100);
    }

    // Updating Hooks werden ausgelöst, BEVOR sich die Eigenschaft ändert (außer bei FileUpload)
    // Wir speichern hier direkt, da die Eigenschaft beim Aufruf der Methode $value den NEUEN Wert enthält

    public function updatingColor($value)
    {
        $this->updateSettingInDatabase($this->dbKeyColor, $value);
    }

    public function updatingImageOverlayOpacity($value)
    {
        // Speichere die Opazität in der Datenbank als Wert zwischen 0 und 1
        $this->updateSettingInDatabase($this->dbKeyOverlayOpacity, (string) floatval($value / 100));
    }

    public function updatingImageOverlayColor($value)
    {
        $this->updateSettingInDatabase($this->dbKeyOverlayColor, $value);
    }

    // Updated Hooks werden ausgelöst, NACHDEM sich die Eigenschaft geändert hat
    // Dies ist oft besser für FileUploads, aber die updating-Methode funktioniert auch.
    // Wir behalten hier updated für adminBackground, wie im Originalcode
    public function updated($property, $value)
    {
        // Handle den Dateiupload für adminBackground
        if ($property == 'adminBackground') {
            if ($value instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                 // Optional: Lösche das alte Bild, bevor du das neue speicherst
                 $this->deleteImageFile(); // Nur die Datei löschen, nicht den DB-Eintrag

                 $filename = $value->getClientOriginalName();
                 $extension = $value->getClientOriginalExtension();
                 // Standardisierter Dateiname
                 $newFilename = 'admin_background.' . $extension;

                 // Speichere das Bild im public Disk unter images/auth
                 $path = $value->storeAs('images/auth', $newFilename, 'public');

                 // Hole den öffentlichen Pfad/URL
                 $publicPath = Storage::disk('public')->url($path);

                 // Speichere den öffentlichen Pfad in der Datenbank
                 $this->updateSettingInDatabase($this->dbKeyImage, $publicPath);

                 // Setze die Eigenschaft im Component auf den neuen Pfad
                 $this->adminBackground = $publicPath;

                 // Kein value = null mehr nötig, Livewire managed das FileInput Property
            }
            // Wenn $value null ist (z.B. Upload abgebrochen), passiert hier nichts.
            // Das Löschen wird über deleteImage() gehandhabt.
        }
    }

    /**
     * Speichert oder aktualisiert ein Einstellungs-Schlüssel-Wert-Paar in der Datenbank.
     */
    private function updateSettingInDatabase($key, $value)
    {
        // Verwende den vollständigen Namespace \App\Models\Setting
        Setting::updateOrCreate(
            [
                'key' => $key,
                'group' => 'global', // Sicherstellen, dass es in der 'global' Gruppe gespeichert wird
            ],
            [
                'data' => $value, // Speichere den eigentlichen Wert in der 'data' Spalte
                // Füge einen Namen hinzu, falls der Datensatz neu erstellt wird
                'name' => ucwords(str_replace(['_', '.'], ' ', $key)), // Z.B. 'background_color' wird zu 'Background Color'
            ]
        );

        // Keine Config::write oder Artisan::call('config:clear') mehr nötig
    }

    /**
     * Löscht nur die Bilddatei aus dem Speicher.
     */
    private function deleteImageFile()
    {
        $imagePath = $this->adminBackground; // Hole den aktuellen Bildpfad aus der Eigenschaft

        // Entferne die Datei aus dem Speicher, falls sie existiert und ein Speicherpfad ist
        if ($imagePath && Str::startsWith($imagePath, '/storage/')) {
             // Entferne den '/storage/' Teil, um den Pfad relativ zum public Disk Root zu erhalten
             $relativePath = str_replace('/storage/', '', $imagePath);
             if (Storage::disk('public')->exists($relativePath)) {
                 Storage::disk('public')->delete($relativePath);
             }
        }
    }


    public function deleteImage()
    {
        // Lösche die Datei aus dem Speicher
        $this->deleteImageFile();

        // Aktualisiere den Datenbankeintrag, um den Bildpfad zu leeren
        $this->updateSettingInDatabase($this->dbKeyImage, '');

        // Setze die Component-Eigenschaft auf leer, um die Anzeige zu aktualisieren
        $this->adminBackground = '';

        // Sende eine Benachrichtigung oder ähnliches
        //$this->js('savedMessageOpen()');
    }

    public function render()
    {
        return view('kompass::livewire.setup.background');
    }
}