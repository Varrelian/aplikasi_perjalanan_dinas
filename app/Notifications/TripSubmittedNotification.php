<?php

namespace App\Notifications;

use App\Models\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TripSubmittedNotification extends Notification
{
    use Queueable;

    public TravelRequest $trip;

    /**
     * Create a new notification instance.
     */
    public function __construct(TravelRequest $trip)
    {
        $this->trip = $trip;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $code = $this->trip->request_code ?? ('TRV-' . $this->trip->id);
        $route = "{$this->trip->origin_code} → {$this->trip->dest_code}";
        $isTraveler = ($notifiable->id == $this->trip->user_id);

        if ($isTraveler) {
            return [
                'type'          => 'trip_submitted',
                'target_role'   => 'employee',
                'trip_id'       => $this->trip->id,
                'request_code'  => $code,
                'title'         => "Travel Request #{$code} Submitted",
                'message'       => "Your travel requisition for {$route} has been submitted to Line Manager for review.",
                'amount'        => $this->trip->total_cost,
                'icon'          => 'outbox',
                'icon_color'    => 'text-[#00254e] bg-[#f0f3ff]',
                'action_url'    => route('trips.show', $this->trip->id),
            ];
        }

        $travelerName = optional($this->trip->traveler)->name ?? 'An employee';
        return [
            'type'          => 'trip_submitted',
            'target_role'   => 'approver',
            'trip_id'       => $this->trip->id,
            'request_code'  => $code,
            'title'         => "New Travel Request #{$code}",
            'message'       => "{$travelerName} submitted a business trip ({$route}) for Line Manager review.",
            'amount'        => $this->trip->total_cost,
            'icon'          => 'pending_actions',
            'icon_color'    => 'text-amber-500 bg-amber-50',
            'action_url'    => route('approvals.show', $this->trip->id),
        ];
    }
}
