<?php

namespace Secondnetwork\Kompass\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Secondnetwork\Kompass\Models\Datafield;

class DatafieldItem extends Component
{
    public Datafield $datafield;

    public $getId;

    public $data;

    public $class;

    public $cssClassname;

    public function mount()
    {
        $this->fill(
            $this->datafield->only('data')
        );

    }

    public function selectitem($action, $itemId, $fieldOrPageName = null, $blockgroupId = null)
    {
        $this->getId = $itemId;

        if ($action == 'addMedia') {

            $this->dispatch('FormMedia');
            $this->dispatch('getIdField_changnd', $this->getId, $fieldOrPageName);
            $this->dispatch('getIdBlock', $blockgroupId);
        }

    }

    #[on('refreshmedia')]
    public function reestPage()
    {
        // $this->FormMedia = false;
        $this->mount();
    }

    public function removemedia($id)
    {
        Datafield::whereId($id)->update(['data' => null]);
    }

    #[on('saveTheDatafield')]
    public function saveTheDatafield()
    {

        Datafield::whereId($this->datafield->id)->update(['data' => $this->data]);
        $this->dispatch('refreshmedia')->self();
    }

    #[Layout('kompass::admin.layouts.app')]
    public function render()
    {
        return view('kompass::livewire.datafield-item');
    }
}
