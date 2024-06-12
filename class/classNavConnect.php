<?php
    require 'classDisconnect.php';
    $disconnect = new Disconnect();
    $disconnect->disconnectModul();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
        body{
            margin:0;
            font-family:arial;
        }

        nav{
            background-color:#20B2AA;
            box-shadow: 0 0 10px 1px #DCDCDC;
            display:flex;
            flex-direction:row-reverse;
            padding-right:50px;
        }

        ul{
            display:flex;
            flex-direction:row;
            gap:20px;
        }

        a{
            text-decoration:none;
            color:white;
        }

        a:hover{
            color:black;
        }

        h1, p{
            text-align:center;
        }

        #container{
            display : flex;
            gap:20px;
        }

        #box{
            width:150px;
            text-align:center;
        }
    </style>
        <main class="main">
            <?php
                class NavConnect {
                    public function NavConnect(){
                        echo "<nav>";
                        echo "<ul>";
                        echo "<a href='../pages/welcome.php'>Home</a>";
                        echo "<form action='' method='post'>";
                        echo "<input type='submit' name='logout' value='Se déconnecter'>";
                        echo "</form>";
                        echo "</ul>";
                        echo "</nav>";
                    }
                }
            ?>
    </main>
</body>
</html>