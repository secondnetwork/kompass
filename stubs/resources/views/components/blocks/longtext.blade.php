@props([
    'item' => '',
])

@if ('wysiwyg' == $item->type)
    <div {{ $attributes }}
    {{-- class="{{ $item->set->layout ?? '' }} prose m-0 max-w-none prose-p:m-0 {{ $item->set->alignment ?? '' }}" --}}
    >



        @php
            $data = json_decode(get_field('wysiwyg',$item->datafield));
        @endphp

        @if($data)
            @foreach ($data->blocks as $block)
                @switch($block->type)
                    @case('header')
                    <div class="not-prose">
                        <h{{ $block->data->level }} >
                            {!! $block->data->text !!}
                        </h{{ $block->data->level }}>
                    </div>
                        @break

                        @case('paragraph')
                            <p>{!! $block->data->text !!}</p>
                        @break

                        @case('table')
                            <table>
                                @foreach ($block->data->content as $items)
                                    {{-- @if ($loop->first && $loop->index == 0)
                                        <thead>
                                    @endif --}}
                                    <tr class="@if ($loop->first && $loop->index == 0) bg-slate-200 font-bold @endif">
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
                            </table>
                        @break

                        @default
                    @endswitch
            @endforeach
        @endif
                        </div>
@endif
