<?php

declare(strict_types=1);

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
        public ?float $timestamp = null,

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
            timestamp: (float) $request->server('REQUEST_TIME')
        );
    }

    /**
     * @param array{
     *      fbclid?: string|null,
     *      timestamp?: float|null,
     *  } $values
     */
    public static function fromArray(array $values): static
    {
        return new static(
            fbclid: $values['fbclid'] ?? null,
            timestamp: $values['timestamp'] ?? null,
        );
    }

    /**
     * @return array{
     *      fbclid?: string|null,
     *      timestamp?: float|null,
     *  }
     */
    public function toArray(): array
    {
        return [
            'fbclid' => $this->fbclid,
            'timestamp' => $this->timestamp,
        ];
    }

    public function __toString(): string
    {
        return $this->fbclid ?? '';
    }
}
