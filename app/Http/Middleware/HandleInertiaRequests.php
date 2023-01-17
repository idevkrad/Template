<?php

namespace App\Http\Middleware;

use App\Models\ListRole;
use App\Models\ListGroup;
// use App\Models\Leave;
use App\Models\ListDropdown;
use App\Models\ListPosition;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Http\Resources\UserResource;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => (\Auth::check()) ? new UserResource(\Auth::user()) : '',
            'flash' => [
                'message' => session('message'),
                'datares' => session('data'),
                'type' => session('type')
            ],
            'positions' => ListPosition::all(),
            'groups' => ListGroup::all(),
            'roles' => ListRole::all(),
            'dropdowns' => ListDropdown::all(),
            // 'leave' => Leave::where('status_id',54)->count()
        ]);
    }
}
