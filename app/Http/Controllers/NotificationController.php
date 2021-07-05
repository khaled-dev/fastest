<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Http\Response;
use App\Services\Logic\NotificationService;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\NotificationCollection;

class NotificationController extends Controller
{

    /**
     * List All user notifications.
     *
     * @return NotificationCollection
     */
    public function index(): NotificationCollection
    {
        /** @var User $user */
        $user = auth()->user();

        return new NotificationCollection($user->notifications()->desc()->paginate(10));
    }

    /**
     * Mark one notification as read.
     *
     * @param Notification $notification
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function read(Notification $notification): Response
    {
        $this->authorize('view', $notification);

        $notification->markAsRead();

        return $this->successResponse([
            'notification' => new NotificationResource($notification)
        ]);
    }

    /**
     * Store notification token.
     *
     * @param Request $request
     * @return NotificationCollection
     */
    public function storeNotificationToken(Request $request): NotificationCollection
    {
        $request->validate(['fb_registration_token' => 'required|string']);

        NotificationService::validateRegistrationToken($request->fb_registration_token);

        /** @var User $user */
        $user = $request->user();

        NotificationService::saveRegistrationToken($user, $request->fb_registration_token);

        return new NotificationCollection($user->notifications()->desc()->paginate(10));
    }
}
