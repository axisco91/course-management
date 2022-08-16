<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th wire:click="sortBy('courses.name')">Curso
            @include('partials._sort-icon', ['field' => 'courses.name'])
            </th>
            <th wire:click="sortBy('companies.name')">Empresa
                @include('partials._sort-icon', ['field' => 'companies.name'])
            </th>
            <th wire:click="sortBy('students.name')">Alumno
                @include('partials._sort-icon', ['field' => 'students.name'])
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($chores as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ substr(str_replace( ' -', '/'.$row->course_group.' -', $row->course), 0 ,20) }}...</td>
                <td>{{ $row->company }}</td>
                <td>{{ $row->student }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $chores->links() }}
</div>
