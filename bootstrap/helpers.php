<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('storage_url')) {
    /**
     * Generate a URL for a stored file.
     *
     * If the path is already a full URL, return it as-is.
     * If R2 is configured, generate the R2 public URL.
     * Otherwise, fall back to the local asset() helper.
     */
    function storage_url(string $path): string
    {
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        if (env('R2_BUCKET')) {
            return Storage::disk('r2')->url($path);
        }

        return asset($path);
    }
}
