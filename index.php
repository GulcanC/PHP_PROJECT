
<!-- SENDING A FORM-->
<?php
// IS RECEIVED SHORTCUT
if(isset($_GET['q'])) {

    $shortcut = htmlspecialchars($_GET['q']);

    // IS A SHORTCUT ?
    $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8','root','root');
    $req = $bdd->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut =?');
    $req->execute(array($shortcut));

    while($result = $req->fetch()){

        if($result['x'] !=1) {
            header('location: index.php?error=true&message=Adresse url non connue');
            exit();
        }
    }

    //REDIRECTION

    $req = $bdd->prepare('SELECT * from  links WHERE shortcut =?');

    $req->execute(array($shortcut));

    while($result = $req->fetch()) {

        header('location: '.$result['url']);
        exit();
    }

}

// IS SENDING A FROM


if (isset($_POST['url'])) {

    $url = $_POST['url'];
 
    //verification format adresse valide 
 
    if (!filter_var($url, FILTER_VALIDATE_URL)) { 
 
        //pas de lien
        header('location: index.php?error=true&message=Adresse url non valide');
        exit(); 
    }
// SHORTCUT
    $shortcut = crypt($url, rand());

    // HAS  BEEN ALREADY SEND ?

    $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8','root','root');

    $req = $bdd->prepare('SELECT COUNT(*) AS x from links WHERE url = ?');

    $req->execute(array($url));

    while($result = $req->fetch()) {

        if($result['x'] !=0){
            header('location:index.php?error=true&message=Adresse déjà raccourcie');
            exit();
        }
    }

    
    //SENDING
    $req = $bdd->prepare('INSERT INTO links(url, shortcut) VALUES(?,?)');

    $req->execute(array($url, $shortcut));

    header('location: index.php?short='.$shortcut);
    exit();

}
    ?>
 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PHP Projet - Gulcan COSKUN</title>

    <link rel="stylesheet" href="design/style.css" type="text/css">
    <link rel="icon" href="image/favico.png" type="image/png">
</head>
<body>
    <!--PRESENTATION-->
    <section id="hello">
        <div class="container">
            <header>
                <img src="image/logo.png" alt="logo" id="logo">
            </header>

            <h1>UNE URL LONGUE ? RACCOURCISSEZ-LA ?</h1>
            <h2>LARGMENT MEILLEUR ET PLUS COURT QUE LES AUTRES.</h2>
      

        <form action="index.php" method="post">
            <input type="url" name="url" placeholder="Collez un lien à raccourcir"> 
            <input type="submit" value="Raccourcir">
        </form>
        
        <?php if(isset($_GET['error']) && isset($_GET['message'])) { ?>
					<div class="center">
						<div id="result">
							<b><?php echo htmlspecialchars($_GET['message']); ?></b>
						</div>
					</div>
				<?php } 
                
                else if(isset($_GET['short'])) { ?>
					<div class="center">
						<div id="result">
							<b>URL RACCOURCIE : </b>
							http://localhost/?q=<?php echo htmlspecialchars($_GET['short']); ?>
						</div>
					</div>
				<?php } ?>

        </div>
    </section>
    
    <section id="brands">
        <div class="container">
            <h3>
                Ces marques nous font confiance
            </h3>
            <img src="image/1.png" alt="1png" class="picture">
            <img src="image/2.png" alt="2png" class="picture">
            <img src="image/3.png" alt="3png" class="picture">
            <img src="image/4.png" alt="4png" class="picture">
        </div>
    </section>

    <footer>
        <img src="image/logo.png" alt="logo" id="logo"><br>
        2022 &copy; Gulcan COSKUN - PHP PROJECT
        <br>
        <a href="#">CONTACT</a> || 
        <a href="#">A PROPOS</a>
    </footer>



    <style>
        
    </style>
    
</body>
</html>

