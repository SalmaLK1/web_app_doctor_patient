

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon fav-icon" href="favicon.png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santé</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/3010b1eaf1.js" crossorigin="anonymous"></script>
    <body>
        
    
<header>
        <!-- menu responsive -->
        
        <div class="menu_toggle">
            <span></span>
        </div>

        <div class="logo">
            <img src="favicon.png.webp" alt="logo">
            <p><span> Sa7ti</span>Ra7ti</p>
        </div>
        <ul class="menu">
            <li><a href="index.php">Acceuil</a></li>
            <li><a href="simple php system/doctors.php">Doctors</a></li>
            <li><a href="test.php">Services</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <button class="login_btn"><a href="simple php system/index.php">LOGIN</a></button>
    </header>

    </body>
    <script>
        //menu responsive code JS

        var menu_toggle = document.querySelector('.menu_toggle');
        var menu = document.querySelector('.menu');
        var menu_toggle_span = document.querySelector('.menu_toggle span');

        menu_toggle.onclick = function(){
            menu_toggle.classList.toggle('active');
            menu_toggle_span.classList.toggle('active');
            menu.classList.toggle('responsive') ;
        }

    </script>