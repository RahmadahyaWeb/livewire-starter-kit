<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteModal extends Component
{
    public string $name;

    public string $heading;

    public string $message;

    public string $action;

    public function __construct(
        string $name,
        string $heading = 'Delete item?',
        string $message = "You're about to delete this item. This action cannot be reversed.",
        string $action = 'destroy'
    ) {
        $this->name = $name;
        $this->heading = $heading;
        $this->message = $message;
        $this->action = $action;
    }

    public function render()
    {
        return view('components.delete-modal');
    }
}
