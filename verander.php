<!doctype html>
<html lang="en">

<head>
    <title>School in Den Haag</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="" />
    <link rel="stylesheet" href="mystyle.css">
</head>



<body>
    <div class=cont>

        <div class=smlrclt>

            <?php
            include('config.php');
            session_start();

            //// restricted if not logged in:

            if (empty($_SESSION['loggedInUser'])) {
                header("Location: login.php");
                exit();
            }

            if (isset($_POST['logout'])) {
                $_SESSION['loggedInUser'] = null;
                header("Location: logout.php");
                exit();
            }

            $getlesnumbermaandag = $_GET['lesnumbermaandag'];
            $getlesnumberwoensdag = $_GET['lesnumberwoensdag'];
            $creditsnu = $_SESSION['aantalcredits'];

            ///////// bepaal $huidigestatus :

            if ($getlesnumbermaandag == 1) {
                $huidigestatus = $_SESSION['les1maandagst'];
            }
            if ($getlesnumbermaandag == 2) {
                $huidigestatus = $_SESSION['les2maandagst'];
            }
            if ($getlesnumberwoensdag == 1) {

                $huidigestatus =  $_SESSION['les1woensdagst'];
            }
            if ($getlesnumberwoensdag == 2) {

                $huidigestatus = $_SESSION['les2woensdagst'];
            }

            /////////// lessen status

            $usrnms = $pdo->query('SELECT gebruiker FROM algemeen');
            $maandagles1status = $pdo->query('SELECT maandagles1status FROM algemeen');
            $maandagles2status = $pdo->query('SELECT maandagles2status FROM algemeen');
            $woensdagles1status = $pdo->query('SELECT woensdagles1status FROM algemeen');
            $woensdagles2status = $pdo->query('SELECT woensdagles2status FROM algemeen');

            ////// my group and  my credits:
            $normalegroep = $pdo->query('SELECT normalegroep FROM algemeen');
            $aantalcredits = $pdo->query('SELECT aantalcredits FROM algemeen');


            ////// lessen tabel 
            $lesnumbermaandag = $pdo->query('SELECT lesnumber FROM datums');
            $lesnumberwoensdag = $pdo->query('SELECT lesnumber FROM datums');
            $lessenmaandagdatums = $pdo->query('SELECT lessenmaandag FROM datums');
            $lessenwoensdagdatums = $pdo->query('SELECT lessenwoensdag FROM datums');


            /// date 
            $date_now = new DateTime();
            $thisdate = null;
            $dateofthislesson = null;

            while (
                $rowusrnms = $usrnms->fetch()
                and $rowmaandagles1status = $maandagles1status->fetch()
                and $rowmaandagles2status = $maandagles2status->fetch()
                and $rowwoensdagles1status = $woensdagles1status->fetch()
                and $rowwoensdagles2status = $woensdagles2status->fetch()
                and $rownormalegroep = $normalegroep->fetch()
                and  $rowaantalcredits = $aantalcredits->fetch()

            ) {
                if ($_SESSION['gebruiker'] == $rowusrnms['gebruiker']) {
                    $les1maandagst = $rowmaandagles1status['maandagles1status'];
                    $les2maandagst = $rowmaandagles2status['maandagles2status'];
                    $les1woensdagst = $rowwoensdagles1status['woensdagles1status'];
                    $les2woensdagst = $rowwoensdagles2status['woensdagles2status'];
                    $groupnaam =  $rownormalegroep['normalegroep'];
                    $_SESSION['aantalcredits'] =  $rowaantalcredits['aantalcredits'];
                }
            }

            /// lessen loop 


            $countermaan = 1;
            $counterwoen = 1;

            while (
                $rowlessenmaandagdatums = $lessenmaandagdatums->fetch()
                and  $rowlessenwoensdagdatums = $lessenwoensdagdatums->fetch()

            ) {
                if ($getlesnumbermaandag == $countermaan) {
                    $thisdate = $rowlessenmaandagdatums['lessenmaandag'];
                    $dateofthislesson = new DateTime($thisdate);
                    $countermaan++;
                }
                if ($getlesnumberwoensdag == $counterwoen) {
                    $thisdate = $rowlessenwoensdagdatums['lessenwoensdag'];
                    $dateofthislesson = new DateTime($thisdate);
                    $counterwoen++;
                } else {
                    $countermaan++;
                    $counterwoen++;
                }
            }

            ?>

            <div class="continside">
                <form method="post"> <input class="btnred" type="submit" name="logout" value="Logout"> </form>
            </div>
            </p>

            <?php

            if (($date_now >= $dateofthislesson) and ($huidigestatus == 0)) {
                echo '<p class="abzc">Sorry, jij kan deze les niet meer inhalen, het is afgelopen</p>' . PHP_EOL;
                echo '<a class="btn" href="index.php">Terug</a> ' . PHP_EOL;
            }
            if (($date_now >= $dateofthislesson) and ($huidigestatus == 1)) {
                echo '<p class="abzc">Sorry, jij kan je niet meer afmelden voor deze les, het is afgelopen</p>' .  PHP_EOL;
                echo '<a class="btn" href="index.php">Terug</a> ' . PHP_EOL;
            } else {

                if ($huidigestatus == 0 and $creditsnu <= 0 and $date_now < $dateofthislesson) {
                    echo '<p class="abzc">Jij hebt niet genoeg credits om iets in te halen, meld je eerst af!</p>' . PHP_EOL;
                    echo '<a class="btn" href="index.php">Terug</a> ' . PHP_EOL;
                }
                if ($huidigestatus == 0 and $creditsnu >= 1 and $date_now < $dateofthislesson) {
                    echo '<p class="abzc">Ben je zeker dat je deze les in wil halen?</p>' . PHP_EOL;
                    echo  '<form action="" method="POST"><input class="btn" type="submit" id="submitbev" name="submitbev" value="Bevestigen"></form>';
                    echo '<a class="btn" href="index.php">Terug</a> ' . PHP_EOL;
                    if (isset($_POST['submitbev'])) {
                        if ($getlesnumbermaandag == 1) {
                            $les1maandagst = 1;
                            $_SESSION['aantalcredits'] = $_SESSION['aantalcredits'] - 1;
                            $username = $_SESSION['gebruiker'];
                            ///////////// ///////////
                            $stmt = $pdo->prepare('UPDATE algemeen
                    SET aantalcredits = ?, maandagles1status = ?
                    WHERE gebruiker = ?');
                            $stmt->bindParam(1, $_SESSION['aantalcredits'], PDO::PARAM_STR);
                            $stmt->bindParam(2, $les1maandagst, PDO::PARAM_STR);
                            $stmt->bindParam(3, $username, PDO::PARAM_STR);
                            $stmt->execute();
                            header("Location:index.php");
                            exit();
                        }
                        if ($getlesnumbermaandag == 2) {
                            $les2maandagst = 1;
                            $_SESSION['aantalcredits'] = $_SESSION['aantalcredits'] - 1;
                            $username = $_SESSION['gebruiker'];
                            //////////// ////////////
                            $stmt = $pdo->prepare('UPDATE algemeen
                    SET aantalcredits = ?, maandagles2status = ?
                    WHERE gebruiker = ?');
                            $stmt->bindParam(1, $_SESSION['aantalcredits'], PDO::PARAM_STR);
                            $stmt->bindParam(2, $les2maandagst, PDO::PARAM_STR);
                            $stmt->bindParam(3, $username, PDO::PARAM_STR);
                            $stmt->execute();
                            header("Location:index.php");
                            exit();
                        }
                        if ($getlesnumberwoensdag == 1) {
                            $les1woensdagst = 1;
                            $_SESSION['aantalcredits'] = $_SESSION['aantalcredits'] - 1;
                            $username = $_SESSION['gebruiker'];
                            //////////// /////////////
                            $stmt = $pdo->prepare('UPDATE algemeen
                    SET aantalcredits = ?, woensdagles1status = ?
                    WHERE gebruiker = ?');
                            $stmt->bindParam(1, $_SESSION['aantalcredits'], PDO::PARAM_STR);
                            $stmt->bindParam(2, $les1woensdagst, PDO::PARAM_STR);
                            $stmt->bindParam(3, $username, PDO::PARAM_STR);
                            $stmt->execute();
                            header("Location:index.php");
                            exit();
                        }
                        if ($getlesnumberwoensdag == 2) {
                            $les2woensdagst = 1;
                            $_SESSION['aantalcredits'] = $_SESSION['aantalcredits'] - 1;
                            $username = $_SESSION['gebruiker'];
                            ////////////// ///////////
                            $stmt = $pdo->prepare('UPDATE algemeen
                    SET aantalcredits = ?, woensdagles2status = ?
                    WHERE gebruiker = ?');
                            $stmt->bindParam(1, $_SESSION['aantalcredits'], PDO::PARAM_STR);
                            $stmt->bindParam(2, $les2woensdagst, PDO::PARAM_STR);
                            $stmt->bindParam(3, $username, PDO::PARAM_STR);
                            $stmt->execute();
                            header("Location:index.php");
                            exit();
                        }
                    }
                }
                if ($huidigestatus == 1) {
                    echo '<p class="abzc">Ben je zeker dat je je af wil melden voor deze les en 1 credit wil krijgen? <br></p>';
                    echo  '<form action="" method="POST"><input class="btn" type="submit" id="submitbev" name="submitbev" value="Bevestigen"></form>';
                    echo '<a class="btn" href="index.php">Terug</a> ' . PHP_EOL;

                    if (isset($_POST['submitbev'])) {
                        if ($getlesnumbermaandag == 1) {
                            $les1maandagst = 0;
                            $_SESSION['aantalcredits'] = $_SESSION['aantalcredits'] + 1;
                            $username = $_SESSION['gebruiker'];
                            ///////////// //////////////
                            $stmt = $pdo->prepare('UPDATE algemeen
                SET aantalcredits = ?, maandagles1status = ?
                WHERE gebruiker = ?');
                            $stmt->bindParam(1, $_SESSION['aantalcredits'], PDO::PARAM_STR);
                            $stmt->bindParam(2, $les1maandagst, PDO::PARAM_STR);
                            $stmt->bindParam(3, $username, PDO::PARAM_STR);
                            $stmt->execute();
                            header("Location:index.php");
                            exit();
                        }
                        if ($getlesnumbermaandag == 2) {
                            $les2maandagst = 0;
                            $_SESSION['aantalcredits'] = $_SESSION['aantalcredits'] + 1;
                            $username = $_SESSION['gebruiker'];
                            ///////////// /////////////
                            $stmt = $pdo->prepare('UPDATE algemeen
                SET aantalcredits = ?, maandagles2status = ?
                WHERE gebruiker = ?');
                            $stmt->bindParam(1, $_SESSION['aantalcredits'], PDO::PARAM_STR);
                            $stmt->bindParam(2, $les2maandagst, PDO::PARAM_STR);
                            $stmt->bindParam(3, $username, PDO::PARAM_STR);
                            $stmt->execute();
                            header("Location:index.php");
                            exit();
                        }

                        if ($getlesnumberwoensdag == 1) {
                            $les1woensdagst = 0;
                            $_SESSION['aantalcredits']++;
                            $username = $_SESSION['gebruiker'];
                            ///////////// /////////////
                            $stmt = $pdo->prepare('UPDATE algemeen
                SET aantalcredits = ?, woensdagles1status = ?
                WHERE gebruiker = ?');
                            $stmt->bindParam(1, $_SESSION['aantalcredits'], PDO::PARAM_STR);
                            $stmt->bindParam(2, $les1woensdagst, PDO::PARAM_STR);
                            $stmt->bindParam(3, $username, PDO::PARAM_STR);
                            $stmt->execute();
                            header("Location:index.php");
                            exit();
                        }
                        if ($getlesnumberwoensdag == 2) {
                            $les2woensdagst = 0;
                            $_SESSION['aantalcredits'] = $_SESSION['aantalcredits'] + 1;
                            $username = $_SESSION['gebruiker'];
                            /////////// /////////////
                            $stmt = $pdo->prepare('UPDATE algemeen
                SET aantalcredits = ?, woensdagles2status = ?
                WHERE gebruiker = ?');
                            $stmt->bindParam(1, $_SESSION['aantalcredits'], PDO::PARAM_STR);
                            $stmt->bindParam(2, $les2woensdagst, PDO::PARAM_STR);
                            $stmt->bindParam(3, $username, PDO::PARAM_STR);
                            $stmt->execute();
                            header("Location:index.php");
                            exit();
                        }
                    }
                }
            }

            $lrrty = rand(0, 2);
            if ($lrrty == 0) {
                echo '<img alt="edelstenen selectie" class="randimg" src="00rand.jpeg">';
            }
            if ($lrrty == 1) {
                echo '<img alt="edelstenen selectie" class="randimg" src="01rand.jpeg">';
            }
            if ($lrrty == 2) {
                echo '<img  alt="edelstenen selectie" class="randimg" src="02rand.jpeg">';
            }

            ?>
            <br>
        </div>
    </div>
</body>

</html>