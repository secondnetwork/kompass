<?php

namespace Secondnetwork\Kompass\Features;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Features;

trait HasProfilePhoto
{
    /**
     * Update the user's profile photo.
     *
     * @return void
     */
    public function updateProfilePhoto(UploadedFile $photo)
    {
        tap($this->profile_photo_path, function ($previous) use ($photo) {
            $this->forceFill([
                'profile_photo_path' => $photo->storePublicly(
                    'profile-photos', ['disk' => $this->profilePhotoDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        if (! Features::managesProfilePhotos()) {
            return;
        }

        Storage::disk($this->profilePhotoDisk())->delete($this->profile_photo_path);

        $this->forceFill([
            'profile_photo_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
                    ? Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path)
                    : $this->defaultProfilePhotoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        return kompass_asset('avatar.png');
        // return 'https://eu.ui-avatars.com/api/?name='.urlencode($this->name).'?color=36424A&background=FFA700&bold=true';
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function profilePhotoDisk()
    {
        return config('kompass.profile_photo_disk', 'public');
    }
}
