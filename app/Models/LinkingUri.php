<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Base\LinkingUri as BaseLinkingUri;

class LinkingUri extends BaseLinkingUri
{
	public static function createLinkingUriForAuthedUser() {
        DB::transaction(function () {
            LinkingUri::where('expires','<',Carbon::now())->delete();
            $uris = LinkingUri::where('target_user',Auth::id())->get();
            if (count($uris) === 0) {
                $uri = new LinkingUri;
                $uri->expires = Carbon::now()->addMinutes(5);
                $uri->target_user = Auth::id();
				$uri->uri = base64_encode(random_bytes(16));
                $uri->save();
            }
        });
	}

	public static function getLinkingUriForAuthedUser() {
		$uri = null;

        DB::transaction(function () use (&$uri) {
            LinkingUri::where('expires','<',Carbon::now())->delete();
            $uris = LinkingUri::where('target_user',Auth::id())->get();
            if (count($uris) > 0) {
				$uri = $uris->first();
			}
        });

		return $uri;
	}
}
