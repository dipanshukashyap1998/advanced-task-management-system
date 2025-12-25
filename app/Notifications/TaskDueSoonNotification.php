<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskDueSoonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Task Due Soon: ' . $this->task->title)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('You have a task that is due soon.')
                    ->line('**Task:** ' . $this->task->title)
                    ->line('**Description:** ' . $this->task->description)
                    ->line('**Due Date:** ' . $this->task->due_date->format('M d, Y H:i'))
                    ->line('**Priority:** ' . ucfirst($this->task->priority))
                    ->line('**Status:** ' . ucfirst(str_replace('_', ' ', $this->task->status)))
                    ->action('View Task', url('/tasks/' . $this->task->id))
                    ->line('Please complete this task before the deadline.')
                    ->salutation('Best regards, Task Manager');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'task_description' => $this->task->description,
            'due_date' => $this->task->due_date,
            'priority' => $this->task->priority,
            'status' => $this->task->status,
            'message' => 'Your task "' . $this->task->title . '" is due soon.',
        ];
    }
}
