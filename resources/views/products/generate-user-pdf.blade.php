<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User List PDF</title>
    <style>
        /* Define your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .date {
            text-align: right;
            margin-bottom: 20px;
        }
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .user-table th,
        .user-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .user-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">
            <h2>{{ $title }}</h2>
        </div>
        <div class="date">
            <p>Date: {{ $date }}</p>
        </div>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Registration No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Sclary</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{$user->registration_no}}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->sclary }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
