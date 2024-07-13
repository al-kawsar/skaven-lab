@props(['header', 'name'])
<div class="table-responsive">
    <table id="{{ $name }}" class="table border-0 star-student table-hover table-center mb-0 w-100">
        <thead class="student-thread">
            <tr>
                @foreach ($header as $index => $item)
                    <th>{!! str_replace(' ', '<br>', $item) !!}</th>
                @endforeach
            </tr>
        </thead>
    </table>
</div>
