<?php

namespace Habib\Dashboard\Helpers;

use Habib\Dashboard\Http\Requests\RequestClient;
use Habib\Dashboard\Models\Visitor;
use Illuminate\Http\Request;

class Helper
{
    public static function notify(array $notify, bool $toast = false)
    {
        return match ($toast) {
            true => toast($notify['message'], $notify['type'], $notify['position'] ?? 'top-right'),
            default => alert($notify['title'], $notify['message'], $notify['type'] ?? 'success')
        };
    }

    /**
     * @param Request $request
     * @return Visitor
     */
    public static function visitor(Request $request): Visitor
    {
        $reqClient = RequestClient::new();

        $location = $reqClient->locationByIp($request->ip());

        return Visitor::firstOrCreate([
            'ip' => $request->ip(),
            'owner_type' => $request->user()?->getMorphClass(),
            'owner_id' => $request->user()?->id,
        ], [
            'country' => $location->countryName,
            'device' => $reqClient->getCurrentDevice(),
            'operating_system' => $reqClient->getOs(),
            'browser' => $reqClient->getCurrentUserAgent(),
        ]);
    }

    public function getVisitor(Request $request): Visitor
    {
        return Visitor::where('ip', $request->ip())->first();
    }
}
