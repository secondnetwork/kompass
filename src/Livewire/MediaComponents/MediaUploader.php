<?php

namespace Secondnetwork\Kompass\Livewire\MediaComponents;

use Livewire\Component;
use Livewire\WithFileUploads;
use Secondnetwork\Kompass\Models\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Reactive;

class MediaUploader extends Component
{
    use WithFileUploads;

    public $files = [];

    #[Reactive]
    public $dir = 'media';

    public function updatedFiles()
    {
        $filesystem = config('kompass.storage.disk', 'public');
        $driverClass = config('kompass.driver', \Intervention\Image\Drivers\Gd\Driver::class);
        $manager = new \Intervention\Image\ImageManager(new $driverClass());

        foreach ($this->files as $filedata) {
            $filename = pathinfo($filedata->getClientOriginalName(), PATHINFO_FILENAME);
            $original_ext = $filedata->getClientOriginalExtension();
            
            $filesSlug = genSlug($filename);
            $time = date('Y_m_d'); 
            $timefilesSlug = $time . '_' . $filesSlug;
            
            $fileModel = new File();
            $type = $fileModel->getType($original_ext);

            $storelink = $filedata->storeAs(
                $this->dir, 
                $timefilesSlug . '.' . $original_ext, 
                $filesystem
            );

            if ($storelink) {
                $file = File::create([
                    'path' => $this->dir,
                    'name' => $filename,
                    'slug' => $timefilesSlug,
                    'extension' => $original_ext,
                    'type' => $type,
                    'alt' => $filename,
                    'description' => '',
                ]);

                // if ($type === 'image') {
                //             // Pre-generate common sizes to avoid on-the-fly bottleneck
                // // 1. Image Manager initialisieren (nutzt konfigurierten Driver aus Kompass)


                // try {
                //     // 2. Bild einlesen
                //     $image = $manager->read($storelink);

                //     // 2.1 Max Auflösung auf 2500px begrenzen (skaliert nur runter, nie hoch)
                //     $image->scaleDown(width: 2000, height: 2000);

                //     // 3. Als WebP speichern
                //     $webpName = $filename . '.webp';
                //     try {
                //         $webpEncoded = $image->toWebp(70);
                //         Storage::disk('public')->put($this->dir . '/' . $webpName, (string) $webpEncoded);
                //         $finalPath = $this->dir . '/' . $webpName;
                //     } catch (\Exception $e) {
                //         // Fallback: Falls WebP nicht geht, Original speichern
                //         $finalPath = $this->photo->storeAs($this->dir, $filename . '.' . $original_ext, 'public');
                //     }

                //     // 4. Als AVIF speichern (Optionaler Versuch)
                //     try {
                //         $avifName = $filename . '.avif';
                //         $avifEncoded = $image->toAvif(50);
                //         Storage::disk('public')->put($this->dir . '/' . $avifName, (string) $avifEncoded);
                //     } catch (\Exception $e) {
                //         // AVIF nicht unterstützt? Kein Problem, Browser nimmt WebP
                //     }

                // } catch (\Exception $e) {
                //     // Falls das Einlesen des Bildes an sich fehlschlägt
                //     // session()->flash('error', 'Fehler bei der Bildverarbeitung: ' . $e->getMessage());
                // }
                // }
            }
        }

        $this->reset('files');
        $this->dispatch('refresh-media-list');
        $this->dispatch('$refresh');
    }

    public function render()
    {
        return view('kompass::livewire.medialibrary.media-uploader');
    }
}
