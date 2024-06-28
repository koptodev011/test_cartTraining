<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
</head>
<body>
    <h2>Title:{{$title}}</h2>
    <h2>Title:{{$date}}</h2>

    <table class="table table=bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $item)
            <tr>{{$item->id}}</tr>
            <tr>{{$item->name}}</tr>
            <tr>{{$item->email}}</tr>
            @endforeach
            
        </tbody>
    </table>
</body>
</html>