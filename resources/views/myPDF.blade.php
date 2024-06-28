
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=`device-width`, initial-scale=1.0">
    <title>Laravel 10</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <h1>{{$data}}</h1>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Magnam voluptatum quaerat facere iure. Omnis asperiores animi laboriosam fuga, quas nisi blanditiis est eius, voluptatum corporis voluptates iure consequatur iste. Facilis?</p>

    <table>
        <thead>
            <tbody>
                 <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
            @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
            </tr>
            @endforeach
            </tbody>
           
        </thead>
    </table>
</body>
</html>