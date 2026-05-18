<?php

namespace App\Livewire;

use Livewire\Component;

class FlashNotification extends Component
{
  public ?string $message = null;
  public string $type = 'success'; // success | error | warning
  public bool $visible = false;

  protected $listeners = [
    'notify' => 'show',
  ];

  public function show(string $message, string $type = 'success'): void
  {
    $this->message = $message;
    $this->type    = $type;
    $this->visible = true;
  }

  public function hide(): void
  {
    $this->visible = false;
  }

  public function render()
  {
    return view('livewire.flash-notification');
  }
}
