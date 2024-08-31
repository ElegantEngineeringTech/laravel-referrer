<?php

namespace Elegantly\Referrer\Sources;

use Illuminate\Http\Request;

/**
 * @extends ReferrerSource<string|null>
 */
class RequestHeaderSource extends ReferrerSource
{
    final public function __construct(
        public ?string $referer = null,

    ) {
        //
    }

    public function isEmpty(): bool
    {
        return blank($this->referer);
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            referer: $request->header('referer'), // spelling is a known mistake
        );
    }

    /**
     * @param array{
     *      referer?: string|null,
     *  } $values
     */
    public static function fromArray(array $values): static
    {
        return new static(
            referer: $values['referer'] ?? null,
        );
    }

    /**
     * @return array{
     *      referer?: string|null,
     *  }
     */
    public function toArray(): array
    {
        return [
            'referer' => $this->referer,
        ];
    }
}
