<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Url extends Model
{
    use HasFactory;

    protected $fillable = ['original_url', 'short_url'];

    public static function generateShortUrl()
    {
        // Generate a random 6-character string
        $shortUrl = Str::random(6);

        // Check if the short URL already exists
        if (self::where('short_url', $shortUrl)->exists()) {
            // If it exists, recursively call the method again
            return self::generateShortUrl();
        }

        // If it doesn't exist, return the unique short URL
        return $shortUrl;
    }
}
