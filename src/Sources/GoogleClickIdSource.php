<?php

declare(strict_types=1);

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
        public ?float $timestamp = null,

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
            timestamp: (float) $request->server('REQUEST_TIME')
        );
    }

    /**
     * @param array{
     *      gclid?: string|null,
     *      timestamp?: float|null,
     *  } $values
     */
    public static function fromArray(array $values): static
    {
        return new static(
            gclid: $values['gclid'] ?? null,
            timestamp: $values['timestamp'] ?? null,
        );
    }

    /**
     * @return array{
     *      gclid?: string|null,
     *      timestamp?: float|null,
     *  }
     */
    public function toArray(): array
    {
        return [
            'gclid' => $this->gclid,
            'timestamp' => $this->timestamp,
        ];
    }

    public function __toString(): string
    {
        return $this->gclid ?? '';
    }
}
