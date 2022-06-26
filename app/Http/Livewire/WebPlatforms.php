<?php

namespace App\Http\Livewire;

use App\Models\TrainingAction;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WebPlatform;

class WebPlatforms extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $url;
    public $updateMode = false;
    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $web_platforms =WebPlatform::getWebPlatforms($keyWord);
        return view('livewire.web-platforms.view', [
            'webPlatforms' => $web_platforms,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
		$this->name = null;
		$this->url = null;
    }

    public function store()
    {
        $this->validate([
		'name' => 'required',
		'url' => 'required',
        ]);

       $data = [
			'name' => $this-> name,
			'url' => $this-> url
        ];
       WebPlatform::createWebPlatform($data);
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Plataforma creado con exito.');
        $this->emit('toastr', 'success');
    }

    public function edit($id)
    {
        $record = WebPlatform::findOrFail($id);

        $this->selected_id = $id;
		$this->name = $record-> name;
		$this->url = $record-> url;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'name' => 'required',
		'url' => 'required',
        ]);

        if ($this->selected_id) {
            $data = [
                'name' => $this-> name,
                'url' => $this-> url
            ];
            WebPlatform::updateWebPlatform($this->selected_id, $data);

            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
			session()->flash('message', 'Plataforma actualizado con exito.');
            $this->emit('toastr', 'success');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $value = WebPlatform::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }
}
