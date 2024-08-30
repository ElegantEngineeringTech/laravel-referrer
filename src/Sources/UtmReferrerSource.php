<?php

namespace Elegantly\Referrer\Sources;

use Illuminate\Http\Request;

/**
 * @extends ReferrerSource<string|null>
 */
class UtmReferrerSource extends ReferrerSource
{
    final public function __construct(
        /**
         * Used to identify which ads campaign this referral references. Use utm_id to identify a specific ads campaign.
         * Ex: abc.123
         */
        public ?string $utm_id = null,
        /**
         * Use utm_source to identify a search engine, newsletter name, or other source
         * Ex: google
         */
        public ?string $utm_source = null,
        /**
         * Use utm_medium to identify a medium such as email or cost-per-click.
         * Ex: cpc
         */
        public ?string $utm_medium = null,
        /**
         * Used for keyword analysis. Use utm_campaign to identify a specific product promotion or strategic campaign.
         * Ex: spring_sale
         */
        public ?string $utm_campaign = null,
        /**
         * Used for paid search. Use utm_term to note the keywords for this ad.
         * Ex: running+shoes
         */
        public ?string $utm_term = null,
        /**
         * Used for A/B testing and content-targeted ads. Use utm_content to differentiate ads or links that point to the same URL.
         * Ex: logolink
         */
        public ?string $utm_content = null,
    ) {
        //
    }

    public function isEmpty(): bool
    {
        return blank($this->utm_source);
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            utm_id: $request->string('utm_id')->value() ?: null,
            utm_source: $request->string('utm_source')->value() ?: null,
            utm_medium: $request->string('utm_medium')->value() ?: null,
            utm_campaign: $request->string('utm_campaign')->value() ?: null,
            utm_term: $request->string('utm_term')->value() ?: null,
            utm_content: $request->string('utm_content')->value() ?: null,
        );
    }

    /**
     * @param array{
     *      utm_id?: string|null,
     *      utm_source?: string|null,
     *      utm_medium?: string|null,
     *      utm_campaign?: string|null,
     *      utm_term?: string|null,
     *      utm_content?: string|null,
     *  } $values
     */
    public static function fromArray(array $values): static
    {
        return new static(
            utm_id: $values['utm_id'] ?? null,
            utm_source: $values['utm_source'] ?? null,
            utm_medium: $values['utm_medium'] ?? null,
            utm_campaign: $values['utm_campaign'] ?? null,
            utm_term: $values['utm_term'] ?? null,
            utm_content: $values['utm_content'] ?? null,
        );
    }

    /**
     * @return array{
     *      utm_id: string|null,
     *      utm_source: string|null,
     *      utm_medium: string|null,
     *      utm_campaign: string|null,
     *      utm_term: string|null,
     *      utm_content: string|null,
     *  }
     */
    public function toArray(): array
    {
        return [
            'utm_id' => $this->utm_id,
            'utm_source' => $this->utm_source,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign,
            'utm_term' => $this->utm_term,
            'utm_content' => $this->utm_content,
        ];
    }
}
