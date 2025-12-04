<?php

namespace App\Rules;

use App\Domain\Admin\Entities\ValueObjects\BandcampPlayer;
use App\Domain\Admin\Exceptions\InvalidBandcampPlayerException;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidBandcampEmbed implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Allow null or empty values (handled by nullable validation)
        if ($value === null || trim((string) $value) === '') {
            return;
        }

        try {
            BandcampPlayer::fromString((string) $value);
        } catch (InvalidBandcampPlayerException $e) {
            $fail($e->getMessage());
        }
    }
}
