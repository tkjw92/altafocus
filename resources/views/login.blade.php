<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h1>Login</h1>
        <form action="" method="POST">
            @csrf
            <div class="box border p-5">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary mt-2">Login</button>
                </div>
            </div>
        </form>
    </div>

    <script src="/assets/bootstrap.bundle.min.js"></script>
</body>

</html>
