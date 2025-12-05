<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/home.css">
    <script src="js/home.js"></script>
</head>
<body>
    <h1>Home</h1>

    <button id="btn-1">Click me</button>
    <a href="/login">Login</a>

    <form action="/create" method="POST">
        <input type="text" name="id">
        <input type="text" name="title">
        <button>Submit</button>
    </form>
    
    
    <?php
    var_dump($data);
    // foreach($products as $product) {
    //     echo '<div style="background-color:light-gray;width:200px;height:fit-content">
    //                 <p>'.$product['id'].'</p>
    //                 <p>'.$product['title'].'</p>
    //             </div>
    //         ';
    // }

    ?>

</body>
</html>