<?php

namespace Secondnetwork\Kompass;

class Features
{
    /**
     * Determine if the given feature is enabled.
     *
     * @return bool
     */
    public static function enabled(string $feature)
    {
        return in_array($feature, config('kompass.features', []));
    }

    /**
     * Determine if the feature is enabled and has a given option enabled.
     *
     * @return bool
     */
    public static function optionEnabled(string $feature, string $option)
    {
        return static::enabled($feature) &&
               config("kompass-options.{$feature}.{$option}") === true;
    }

    /**
     * Determine if the application is allowing profile photo uploads.
     *
     * @return bool
     */
    public static function managesProfilePhotos()
    {
        return static::enabled(static::profilePhotos());
    }

    /**
     * Determine if the application is using any API features.
     *
     * @return bool
     */
    public static function hasApiFeatures()
    {
        return static::enabled(static::api());
    }

    /**
     * Determine if the application is using any account deletion features.
     *
     * @return bool
     */
    public static function hasAccountDeletionFeatures()
    {
        return static::enabled(static::accountDeletion());
    }

    /**
     * Enable the profile photo upload feature.
     *
     * @return string
     */
    public static function profilePhotos()
    {
        return 'profile-photos';
    }

    /**
     * Enable the API feature.
     *
     * @return string
     */
    public static function api()
    {
        return 'api';
    }

    /**
     * Enable the account deletion feature.
     *
     * @return string
     */
    public static function accountDeletion()
    {
        return 'account-deletion';
    }
}
