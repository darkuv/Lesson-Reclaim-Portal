<!doctype html>
<html lang="en">

<head>
    <title>School in Den Haag</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="logoipsum-300.svg" />
    <link rel="stylesheet" href="mystyle.css">
</head>


<body>
    <div class=cont>
        <?php
        include('config.php');
        session_start();

        $usrnms = $pdo->query('SELECT gebruiker FROM algemeen');
        $pswrds = $pdo->query('SELECT wachtwoord FROM algemeen');
        $usrids = $pdo->query('SELECT usrid FROM algemeen');
        $foutmeldingen = array('Login: ', 'Wachtwoord: ');
        if (isset($_POST['submit'])) {
            $i = 0;
            while (
                $rowusrnms = $usrnms->fetch()
                and $rowpswrds = $pswrds->fetch()
                and $rowusrids = $usrids->fetch()
                and $i < count($foutmeldingen)
            ) {
                if (isset($_POST['submit'])) {
                    if ($_POST['gebruiker'] == $rowusrnms['gebruiker'] and $_POST['wachtwoord'] ==  $rowpswrds['wachtwoord']) {
                        $_SESSION['loggedInUser'] = $rowusrids['usrid'];
                        $_SESSION['gebruiker'] = $_POST['gebruiker'];
                        header("Location:index.php");
                        exit();
                    } else {
                        echo '<div class="whitefont">' . $foutmeldingen[$i] . 'er is iets fout gegaan!</div>' . PHP_EOL;
                        echo  PHP_EOL;
                        $i++;
                    }
                }
            }
        }

        ?>


        <div class=smlrclt>
            <img alt="banner Nederlandse Lapidaristen Club" src="logoipsum-300.svg">

            <h1> Login </h1>
            <form action="" method="POST">
                <label for="titel"><b>Gebruiker </b></label>
                <input type="text" id="gebruiker" name="gebruiker" placeholder=""><br><br>
                <label for="rating"><b>Wachtwoord </b></label>
                <input type="password" id="wachtwoord" name="wachtwoord" placeholder=""><br><br>

                <input class="btn" type="submit" id="submit" name="submit" value="Opslaan">
            </form>

            <p> Inloggen met username "daria" en wachtwoord "student001" of "mirjam" en "student002" </p>

        </div>
    </div>

</body>

</html>