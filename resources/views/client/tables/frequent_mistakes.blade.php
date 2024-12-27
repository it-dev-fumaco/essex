<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Correct Anwswer</th>
            <th>Common Anwswer</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody class="table-body">
        @forelse ($frequent_mistakes_data as $item)
            <tr>
                <td style="text-align: center">{{ $item->question_id }}</td>
                <td>{!! $item->questions !!}</td>
                <td>{{ $item->correct_answer }}</td>
                <td>{{ $examinee_answers[$item->question_id][0]->examinee_answer }}</td>
                <td style="text-align: center">{{ $item->wrong_answers_count }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No Records Found.</td>
            </tr>
        @endforelse
    </tbody>
</table>