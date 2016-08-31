<h1>All Tasks</h1>
<ul>
    @foreach($tasks as $task)
        <li>{{ $task->title }}</li>
    @endforeach
</ul>