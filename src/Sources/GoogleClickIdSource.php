<?php

namespace Elegantly\Referrer\Sources;

use Illuminate\Http\Request;

/**
 * @see https://support.google.com/google-ads/answer/3095550
 *
 * @extends ReferrerSource<string|null>
 */
class GoogleClickIdSource extends ReferrerSource
{
    final public function __construct(
        public ?string $gclid = null,

    ) {
        //
    }

    public function isEmpty(): bool
    {
        return blank($this->gclid);
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            gclid: $request->string('gclid')->value() ?: null,
        );
    }

    /**
     * @param array{
     *      gclid?: string|null,
     *  } $values
     */
    public static function fromArray(array $values): static
    {
        return new static(
            gclid: $values['gclid'] ?? null,
        );
    }

    /**
     * @return array{
     *      gclid?: string|null,
     *  }
     */
    public function toArray(): array
    {
        return [
            'gclid' => $this->gclid,
        ];
    }
}
