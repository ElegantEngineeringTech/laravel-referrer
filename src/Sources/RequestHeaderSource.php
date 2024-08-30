<?php

namespace Elegantly\Referrer\Sources;

use Illuminate\Http\Request;

/**
 * @extends ReferrerSource<string|null>
 */
class RequestHeaderSource extends ReferrerSource
{
    final public function __construct(
        public ?string $referrer = null,

    ) {
        //
    }

    public function isEmpty(): bool
    {
        return blank($this->referrer);
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            referrer: $request->header('referer'), // spelling is a known mistake
        );
    }

    /**
     * @param array{
     *      referrer?: string|null,
     *  } $values
     */
    public static function fromArray(array $values): static
    {
        return new static(
            referrer: $values['referrer'] ?? null,
        );
    }

    /**
     * @return array{
     *      referrer?: string|null,
     *  }
     */
    public function toArray(): array
    {
        return [
            'referrer' => $this->referrer,
        ];
    }
}
