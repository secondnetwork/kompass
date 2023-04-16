
@props([
    'layout' => '',
    'blockid' => '',
])

@if ('anmeldemaske' == $layout)
<section>

    @livewire('eventform', [
    'uberschrift' => $this->get_field('uberschrift',$blockid),
    'datenschutz' => $this->get_field('datenschutz',$blockid),
    'titelfurwerbung' => $this->get_field('titel-fur-werbung',$blockid),
    'werbungtext' => $this->get_field('werbung',$blockid),
    'togglewerbung' => $this->get_field('toggle',$blockid),
    'toggleBegleitung' => $this->get_field('begleitung',$blockid),
    'toggleAdresse' => $this->get_field('adresse',$blockid),
    ])

</section>
@endif
