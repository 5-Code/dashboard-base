<?php

namespace Habib\Dashboard\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class SeoCast implements CastsAttributes
{
    /**
     * @param $model
     * @param $key
     * @param $value
     * @param $attributes
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {
        return json_decode($value, true);
    }

    /**
     * @param $model
     * @param $key
     * @param $value
     * @param $attributes
     * @return false|mixed|string
     */
    public function set($model, $key, $value, $attributes)
    {
        $value = array_merge($value ?? [], [
            'title' => [
                'en' => null,
                'ar' => null,
            ],
            'description' => [
                'en' => null,
                'ar' => null,
            ],
            'keywords' => [],
            'author' => null,
            'publisher_at' => null,
            'meta' => [],
            'properties' => [],
            'url' => null,
            'image' => [],
            'type' => null,
            'locale' => null,
            'robots' => null,
            'canonical' => null,
            'alternate' => [],
            'sitemap' => [
                'priority' => null,
                'changefreq' => null,
                'lastmod' => null,
            ],
            'article' => [
                'published_time' => null,
                'modified_time' => null,
                'expiration_time' => null,
                'section' => null,
                'tag' => [],
            ],
            'book' => [
                'author' => null,
                'isbn' => null,
                'release_date' => null,
                'tag' => [],
            ],
            'profile' => [
                'first_name' => null,
                'last_name' => null,
                'username' => null,
                'gender' => null,
            ],
            'video' => [
                'actor' => [],
                'actor:role' => [],
                'director' => [],
                'writer' => [],
                'duration' => null,
                'release_date' => null,
                'tag' => [],
                'series' => null,
            ],
            'music' => [
                'duration' => null,
                'album' => null,
                'album:disc' => null,
                'album:track' => null,
                'musician' => [],
            ],
            'place' => [
                'location:latitude' => null,
                'location:longitude' => null,
                'street_address' => null,
                'locality' => null,
                'region' => null,
                'postal_code' => null,
                'country_name' => null,
                'email' => null,
                'phone_number' => null,
                'fax_number' => null,
            ],
            'product' => [
                'availability' => null,
                'price:amount' => null,
                'price:currency' => null,
                'price:valid_until' => null,
                'retailer_item_id' => null,
                'brand' => null,
                'category' => null,
                'color' => null,
                'condition' => null,
                'material' => null,
                'size' => null,
                'weight:value' => null,
                'weight:units' => null,
                'gtin' => null,
                'mpn' => null,
                'isbn' => null,
            ],
            'restaurant' => [
                'contact_info:street_address' => null,
                'contact_info:locality' => null,
                'contact_info:region' => null,
                'contact_info:postal_code' => null,
                'contact_info:country_name' => null,
                'contact_info:email' => null,
                'contact_info:phone_number' => null,
                'contact_info:fax_number' => null,
                'contact_info:website' => null,
                'contact_info:hours' => null,
                'contact_info:price_range' => null,
                'contact_info:menu' => null,
                'contact_info:reservation' => null,
                'contact_info:ordering' => null,
                'contact_info:delivery' => null,
                'contact_info:takeout' => null,
                'contact_info:curbside_pickup' => null,
                'contact_info:wheelchair_accessible' => null,
                'contact_info:smoking' => null,
                'contact_info:outdoor_seating' => null,
                'contact_info:parking' => null,
                'contact_info:parking:street' => null,
                'contact_info:parking:validated' => null,
                'contact_info:parking:lot' => null,
                'contact_info:parking:valet' => null,
                'contact_info:parking:garage' => null,
                'contact_info:parking:covered' => null,
                'contact_info:parking:fee' => null,
                'contact_info:parking:valet_fee' => null,
                'contact_info:parking:garage_fee' => null,
                'contact_info:parking:lot_fee' => null,
                'contact_info:parking:street_fee' => null,
                'contact_info:parking:validated_fee' => null,
                'contact_info:parking:covered_fee' => null,
                'contact_info:parking:credit_cards' => null,
                'contact_info:parking:valet_credit_cards' => null,
                'contact_info:parking:garage_credit_cards' => null,
                'contact_info:parking:lot_credit_cards' => null,
                'contact_info:parking:street_credit_cards' => null,
                'contact_info:parking:validated_credit_cards' => null,
                'contact_info:parking:covered_credit_cards' => null,
                'contact_info:parking:lot_hours' => null,
                'contact_info:parking:garage_hours' => null,
                'contact_info:parking:street_hours' => null,
                'contact_info:parking:validated_hours' => null,
                'contact_info:parking:covered_hours' => null,
                'contact_info:parking:lot_price' => null,
                'contact_info:parking:garage_price' => null,
                'contact_info:parking:street_price' => null,
                'contact_info:parking:validated_price' => null,
                'contact_info:parking:covered_price' => null,
                'contact_info:parking:lot_price_unit' => null,
                'contact_info:parking:garage_price_unit' => null,
                'contact_info:parking:street_price_unit' => null,
                'contact_info:parking:validated_price_unit' => null,
                'contact_info:parking:covered_price_unit' => null,
            ],
            'movie' => [
                'actor' => [],
                'actor:role' => [],
                'director' => [],
                'writer' => [],
                'duration' => null,
                'release_date' => null,
                'tag' => [],
                'trailer' => [],
            ],
            'website' => [
                'author' => null,
                'publisher' => null,
                'published_time' => null,
                'modified_time' => null,
                'expiration_time' => null,
                'tag' => [],
            ],
            'video.episode' => [
                'actor' => [],
                'actor:role' => [],
                'director' => [],
                'writer' => [],
                'duration' => null,
                'release_date' => null,
                'tag' => [],
                'series' => null,
                'episode' => null,
                'season' => null,
            ],
            'video.movie' => [
                'actor' => [],
                'actor:role' => [],
                'director' => [],
                'writer' => [],
                'duration' => null,
                'release_date' => null,
                'tag' => [],
                'trailer' => [],
            ],
            'video.other' => [
                'actor' => [],
                'actor:role' => [],
                'director' => [],
                'writer' => [],
                'duration' => null,
                'release_date' => null,
                'tag' => [],
                'trailer' => [],
            ],
            'video.tv_show' => [
                'actor' => [],
                'actor:role' => [],
                'director' => [],
                'writer' => [],
                'duration' => null,
                'release_date' => null,
                'tag' => [],
                'trailer' => [],
            ],
            'og:video' => [
                'url' => null,
                'secure_url' => null,
                'type' => null,
                'width' => null,
                'height' => null,
                'alt' => null,
            ],
            'og:audio' => [
                'url' => null,
                'secure_url' => null,
                'type' => null,
            ],
            'og:image' => [
                'url' => null,
                'secure_url' => null,
                'type' => null,
                'width' => null,
                'height' => null,
                'alt' => null,
            ],
            'og:video:actor' => [
                'og:video:actor:id' => null,
                'og:video:actor:role' => null,
                'og:video:actor:profile' => null,
            ],
            'og:video:director' => [
                'og:video:director:id' => null,
                'og:video:director:profile' => null,
            ],
            'og:video:writer' => [
                'og:video:writer:id' => null,
                'og:video:writer:profile' => null,
            ],
            'og:video:tag' => [],
            'og:video:series' => null,
            'og:video:episode' => null,
            'og:video:season' => null,
            'og:video:release_date' => null,
            'og:video:duration' => null,
            'og:video:trailer' => [],
            'og:video:requires_subscription' => null,
            'og:video:upload_date' => null,
            'og:video:expiration_time' => null,
            'og:video:live' => null,
            'og:video:is_live_broadcast' => null,
            'og:place' => [
                'location:latitude' => null,
                'location:longitude' => null,
                'location:altitude' => null,
                'street_address' => null,
                'locality' => null,
                'region' => null,
                'postal_code' => null,
                'country_name' => null,
                'email' => null,
                'phone_number' => null,
                'fax_number' => null,
            ],
            'og:profile' => [
                'first_name' => null,
                'last_name' => null,
                'username' => null,
            ],
        ]);

        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
