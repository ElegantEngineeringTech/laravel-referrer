<?php

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
        );
    }

    /**
     * @param array{
     *      ttclid?: string|null,
     *  } $values
     */
    public static function fromArray(array $values): static
    {
        return new static(
            ttclid: $values['ttclid'] ?? null,
        );
    }

    /**
     * @return array{
     *      ttclid?: string|null,
     *  }
     */
    public function toArray(): array
    {
        return [
            'ttclid' => $this->ttclid,
        ];
    }
}