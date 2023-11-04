@props([
    'set' => '',
    'layout' => '',
    'blockid' => '',
])

@if ('wysiwyg' == $layout)
<section class="{{$set->layout ?? ''}} prose m-0 max-w-none prose-p:m-0 {{$set->alignment ?? ''}}">

@php
$data = json_decode($this->get_field('wysiwyg', $blockid));
@endphp



@foreach ($data->blocks as $block)

    @switch($block->type)

    @case('header')
    <h{{ $block->data->level }}>
        {!! $block->data->text !!}
    </h{{ $block->data->level }}>

@break
        @case('paragraph')
        {!! $block->data->text !!}
        @break
        @case('table')
            @foreach ($block->data->content as $items)
                {{-- @if ($loop->first && $loop->index == 0)
                    <thead>
                @endif --}}
                <tr  class="@if ($loop->first && $loop->index == 0) bg-slate-200 font-bold @endif" >
                    {{-- @if ($loop->first && $loop->index == 0)
                        <th>
                    @endif
                    @if ($loop->index !== 0)
                        <td>
                    @endif --}}
                    @foreach ($items as $item)


                            <td class="p-4"> {{ $item }} </td>

                    @endforeach
                    {{-- @if ($loop->first && $loop->index == 0)
                        </th>
                    @endif
                    @if ($loop->index !== 0)
                        </td>
                    @endif --}}
                </tr>
                {{-- @if ($loop->last && $loop->index == 0)
                    <thead>
                @endif --}}


                {{-- <tbody>
                    @if ($loop->index !== 0)
                    @endif
                </tbody> --}}
            @endforeach
        @break

        @default
    @endswitch
</table>
@endforeach



</section>
@endif
