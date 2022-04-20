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

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $web_platforms = WebPlatform::
        orWhere('name', 'LIKE', $keyWord)
            ->orWhere('url', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($web_platforms as $web_platform){
            $training_action = TrainingAction::where('web_platform_id', $web_platform['id'])->first();
            if ($training_action){
                $web_platform['used'] = true;
            } else {
                $web_platform['used'] = false;
            }
        }
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

        WebPlatform::create([
			'name' => $this-> name,
			'url' => $this-> url
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Plataforma creado con exito.');
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
			$record = WebPlatform::find($this->selected_id);
            $record->update([
			'name' => $this-> name,
			'url' => $this-> url
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Plataforma actualizado con exito.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = WebPlatform::where('id', $id);
            $record->delete();
        }
    }
}
