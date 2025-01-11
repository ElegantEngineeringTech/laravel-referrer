<?php

declare(strict_types=1);

namespace Elegantly\Referrer\Sources;

use Illuminate\Http\Request;

/**
 * @see https://business-api.tiktok.com/portal/docs?id=1771100879787009
 *
 * @extends ReferrerSource<string|null>
 */
class TikTokClickIdSource extends ReferrerSource
{
    final public function __construct(
        public ?string $ttclid = null,
        public ?float $timestamp = null,

    ) {
        //
    }

    public function isEmpty(): bool
    {
        return blank($this->ttclid);
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            ttclid: $request->string('ttclid')->value() ?: null,
            timestamp: (float) $request->server('REQUEST_TIME')
        );
    }

    /**
     * @param array{
     *      ttclid?: string|null,
     *      timestamp?: float|null,
     *  } $values
     */
    public static function fromArray(array $values): static
    {
        return new static(
            ttclid: $values['ttclid'] ?? null,
            timestamp: $values['timestamp'] ?? null,
        );
    }

    /**
     * @return array{
     *      ttclid?: string|null,
     *      timestamp?: float|null,
     *  }
     */
    public function toArray(): array
    {
        return [
            'ttclid' => $this->ttclid,
            'timestamp' => $this->timestamp,
        ];
    }

    public function __toString(): string
    {
        return $this->ttclid ?? '';
    }
}
