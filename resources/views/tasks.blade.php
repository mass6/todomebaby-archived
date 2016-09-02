<h1>Today</h1>
<ul>
    @foreach($tasks as $task)
        <li>{{ $task->title }}</li>
    @endforeach
</ul>