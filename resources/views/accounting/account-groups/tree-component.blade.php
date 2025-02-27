<ul>
    @foreach ($items as $group)
        <option value="{{ $group->id }}">
            {{ str_repeat('-', $group->level) }} {{ $group->name }}
        </option>
        @if ($group->childrens->isNotEmpty())
            @include('accounting.account-groups.tree-component', ['items' => $group->childrens])
        @endif
    @endforeach
</ul>
