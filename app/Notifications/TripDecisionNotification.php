<?php

namespace App\Notifications;

use App\Models\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TripDecisionNotification extends Notification
{
    use Queueable;

    public TravelRequest $trip;
    public string $decision;
    public ?string $decisionNotes;

    /**
     * Create a new notification instance.
     */
    public function __construct(TravelRequest $trip, string $decision, ?string $decisionNotes = null)
    {
        $this->trip = $trip;
        $this->decision = $decision;
        $this->decisionNotes = $decisionNotes;
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

        if ($this->decision === 'approve') {
            $title = "Trip #{$code} Approved";
            $message = "Your travel request for {$route} has been cleared to stage: {$this->trip->approval_stage}.";
            $icon = 'check_circle';
            $iconColor = 'text-emerald-600 bg-emerald-50';
        } elseif ($this->decision === 'reject') {
            $title = "Trip #{$code} Declined";
            $reasonText = $this->decisionNotes ? " Reason: {$this->decisionNotes}" : '';
            $message = "Your travel request for {$route} was declined by reviewer.{$reasonText}";
            $icon = 'cancel';
            $iconColor = 'text-red-600 bg-red-50';
        } else {
            $title = "Clarification Requested for #{$code}";
            $reasonText = $this->decisionNotes ? " Note: {$this->decisionNotes}" : '';
            $message = "Reviewer requested clarification regarding {$route}.{$reasonText}";
            $icon = 'help';
            $iconColor = 'text-blue-600 bg-blue-50';
        }

        return [
            'type'          => 'trip_decision',
            'target_role'   => 'employee',
            'trip_id'       => $this->trip->id,
            'request_code'  => $code,
            'decision'      => $this->decision,
            'title'         => $title,
            'message'       => $message,
            'amount'        => $this->trip->total_cost,
            'icon'          => $icon,
            'icon_color'    => $iconColor,
            'action_url'    => route('trips.show', $this->trip->id),
        ];
    }
}
