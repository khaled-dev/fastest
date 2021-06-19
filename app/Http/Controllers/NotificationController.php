<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Response;
use App\Http\Resources\NotificationResource;

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
}
