<?php

namespace Elegantly\Referrer\Sources;

use Illuminate\Http\Request;

/**
 * @see https://developers.facebook.com/docs/marketing-api/conversions-api/parameters/fbp-and-fbc/
 *
 * @extends ReferrerSource<string|null>
 */
class MetaClickIdSource extends ReferrerSource
{
    final public function __construct(
        public ?string $fbclid = null,

    ) {
        //
    }

    public function isEmpty(): bool
    {
        return blank($this->fbclid);
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            fbclid: $request->string('fbclid')->value() ?: null,
        );
    }

    /**
     * @param array{
     *      fbclid?: string|null,
     *  } $values
     */
    public static function fromArray(array $values): static
    {
        return new static(
            fbclid: $values['fbclid'] ?? null,
        );
    }

    /**
     * @return array{
     *      fbclid?: string|null,
     *  }
     */
    public function toArray(): array
    {
        return [
            'fbclid' => $this->fbclid,
        ];
    }
}
