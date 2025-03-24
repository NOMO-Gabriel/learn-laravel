<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $type;
    public $color;
    
    public function __construct($type = 'button', $color = 'blue')
    {
        $this->type = $type;
        $this->color = $color;
    }
    
    public function render()
    {
        return view('components.button');
    }
    
    public function colorClasses()
    {
        return match($this->color) {
            'blue' => 'bg-blue-500 hover:bg-blue-700',
            'red' => 'bg-red-500 hover:bg-red-700',
            'green' => 'bg-green-500 hover:bg-green-700',
            default => 'bg-gray-500 hover:bg-gray-700',
        };
    }
}
