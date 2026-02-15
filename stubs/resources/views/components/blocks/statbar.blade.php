@props(['item' => ''])
@if($item->type == 'statbar')
    @php
        $datafields = $item->datafield;
        $layoutgrid = max(1, $item->layoutgrid ?? 12);
        // Debug toggle: allow debugging from meta or query param
        $debugStatbar = (bool) ($item->getMeta('debug_statbar') ?? request()->query('debug_statbar') ?? false);
        $gridCols = 'grid-cols-' . $layoutgrid;
        $colSpan = $item->layoutgrid ? 'col-span-' . $item->layoutgrid : '';
    @endphp

    <div class="statbar py-6 {{ $gridCols }} {{ $colSpan }}">
        @foreach($datafields as $index => $datafield)
            @php
                $raw = $datafield->text ?? $datafield->content ?? $datafield->value ?? $datafield->data ?? '';
                $str = (string) $raw;
                $content = $str;
                $hasNumber = false;
                $targetNumber = 0;
                $prefix = '';
                $suffix = '';
                if (preg_match('/-?\d[\d.,]*/', $str, $m)) {
                    $numStr = $m[0];
                    $targetNumber = (int) preg_replace('/[^0-9]/', '', $numStr);
                    $pos = strpos($str, $numStr);
                    $prefix = substr($str, 0, $pos);
                    $suffix = substr($str, $pos + strlen($numStr));
                    $hasNumber = true;
                }
            @endphp
            <div class="stat-item">
                @if($index == 0)
                    <h3>{!! $str !!}</h3>
                @else
                    @if($hasNumber)
                        <p class="stat-number" data-target="{{ $targetNumber }}" data-prefix="{{ trim($prefix) }}" data-suffix="{{ trim($suffix) }}" data-duration="2000" data-debug="{{ $debugStatbar ? 'true' : 'false' }}">{{ $prefix }}{{ $targetNumber }}{{ $suffix }}</p>
                    @else
                        <p>{!! $content !!}</p>
                    @endif
                @endif
            </div>
    
        @endforeach
</div>
@endif

<script>
(function(){
  function animateStat(el){
    const target = parseInt(el.dataset.target, 10) || 0;
    const prefix = el.dataset.prefix || '';
    const suffix = el.dataset.suffix || '';
    const duration = parseInt(el.dataset.duration || '2000');
    const debug = el.dataset.debug === 'true';
    if (debug) console.debug('Statbar animate start', {target, prefix, suffix, duration});
    const startTime = performance.now();
    function step(now){
      const elapsed = now - startTime;
      const p = Math.min(elapsed / duration, 1);
      const value = Math.floor(p * target);
      el.textContent = prefix + value + suffix;
      if (p < 1) {
        if (debug) console.debug('Statbar tick', {p, value});
        requestAnimationFrame(step);
      } else {
        el.textContent = prefix + target + suffix;
      }
    }
    requestAnimationFrame(step);
  }
  function init(){
    document.querySelectorAll('.stat-number[data-target]').forEach(function(el){
      if (el.dataset.animated) return;
      el.dataset.animated = 'true';
      const obs = new IntersectionObserver((entries, observer) => {
        entries.forEach(e => {
          if (e.isIntersecting) {
            animateStat(el);
            observer.unobserve(el);
          }
        });
      }, { rootMargin: '0px', threshold: 0.1 });
      obs.observe(el);
      // Fallback: if already visible
      try {
        const r = el.getBoundingClientRect();
        if (r.top < window.innerHeight && r.bottom > 0) {
          animateStat(el);
          obs.unobserve(el);
        }
      } catch(err) { /* ignore */ }
    });
  }
  document.addEventListener('DOMContentLoaded', init);
  document.addEventListener('livewire:load', init);
  document.addEventListener('livewire:updated', init);
})();
</script>
