<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use App\Services\Logic\NotificationService;
use Illuminate\Http\Response;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    /**
     * List All user notifications.
     *
     * @return Response
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = auth()->user();

        return $this->successResponse([
            'notifications' => NotificationResource::collection($user->notifications)
        ]);
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
     * @return Response
     */
    public function storeNotificationToken(Request $request): Response
    {
        $request->validate(['fb_registration_token' => 'required|string']);

        NotificationService::validateRegistrationToken($request->fb_registration_token);

        /** @var User $user */
        $user = $request->user();

        NotificationService::saveRegistrationToken($user, $request->fb_registration_token);

        return $this->successResponse([
            'notifications' => NotificationResource::collection($user->notifications)
        ]);
    }
}
