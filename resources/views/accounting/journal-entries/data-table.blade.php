<table  class="table table-hover table-bordered rounded-2 overflow-hidden display">
    <thead>
        <tr>
            <th>Date</th>
            <th>Particulars</th>
            <th>Debit</th>
            <th>Credit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($journalEntries as $journalEntry)
            <tr>
                <td rowspan="{{ $journalEntry->journalEntryLines->count() }}">{{ $journalEntry->date }}</td>
                <td>
                    {{ $journalEntry->journalEntryLines->first()?->account?->name }}
                </td>
                <td>
                    {{ $journalEntry->journalEntryLines->first()?->debit }}
                </td>
                <td>
                    {{ $journalEntry->journalEntryLines->first()?->credit }}
                </td>
            </tr>
            @foreach ($journalEntry->journalEntryLines as $sl => $line)
                @php
                    if ($sl == 0) {
                        continue;
                    }
                @endphp
                <td>{{$line->account->name}}</td>
                <td>{{$line->debit}}</td>
                <td>{{$line->credit}}</td>
            @endforeach
        @endforeach
    </tbody>
</table>
<div class="mt-4">
    <div class="inline-flex space-x-2">
        @if ($journalEntries->previousPageUrl())
            <button class="px-4 py-2 bg-gray-300 rounded"
                hx-get="{{ $journalEntries->previousPageUrl() }}"
                hx-target="#journal-entries-table"
                hx-push-url="true">Previous</button>
        @endif

        @if ($journalEntries->nextPageUrl())
            <button class="px-4 py-2 bg-gray-300 rounded"
                hx-get="{{ $journalEntries->nextPageUrl() }}"
                hx-target="#journal-entries-table"
                hx-push-url="true">Next</button>
        @endif
    </div>
</div>
