<?php

namespace App\Services\Notifications;


use App\Models\Notification; 
use App\Models\User; 

class CreateNotificationService
{
    protected $icon;
    protected $title;
    protected $msg;
    protected $user; 


    public function __construct(User $user, string $icon, string $title, string $msg)
    {
        $this->user = $user;
        $this->icon = $icon;
        $this->title = $title;
        $this->msg = $msg;
    }

    /**
     * Salva notificações na tabela 
     * @return Notification
     */

    public function saveNotification()
    {
        return Notification::create([
            'user_id' => $this->user->id,
            'icon' => $this->icon,
            'title' => $this->title,
            'message' => $this->msg, 
        ]);
    }
}