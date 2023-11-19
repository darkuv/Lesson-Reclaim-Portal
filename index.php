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

        /////////// lessen status
        $usrnms = $pdo->query('SELECT gebruiker FROM algemeen');
        $maandagles1status = $pdo->query('SELECT maandagles1status FROM algemeen');
        $maandagles2status = $pdo->query('SELECT maandagles2status FROM algemeen');
        $woensdagles1status = $pdo->query('SELECT woensdagles1status FROM algemeen');
        $woensdagles2status = $pdo->query('SELECT woensdagles2status FROM algemeen');
        ////// my group and  my credits:
        $normalegroep = $pdo->query('SELECT normalegroep FROM algemeen');
        $aantalcredits = $pdo->query('SELECT aantalcredits FROM algemeen');

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
                $_SESSION['les1maandagst'] = $rowmaandagles1status['maandagles1status'];
                $_SESSION['les2maandagst'] = $rowmaandagles2status['maandagles2status'];
                $_SESSION['les1woensdagst'] = $rowwoensdagles1status['woensdagles1status'];
                $_SESSION['les2woensdagst'] = $rowwoensdagles2status['woensdagles2status'];
                $groupnaam =  $rownormalegroep['normalegroep'];
                $_SESSION['aantalcredits'] =  $rowaantalcredits['aantalcredits'];
            }
        }

        $lessenmaandagdatumsarray = array($_SESSION['les1maandagst'], $_SESSION['les2maandagst']);
        $lessenwoensdagdatumsarray = array($_SESSION['les1woensdagst'], $_SESSION['les2woensdagst']);

        ///// my name:
        $usrnamehere = $_SESSION['gebruiker'];

        ////// lessen tabel 
        $lesnumbermaandag = $pdo->query('SELECT lesnumber FROM datums');
        $lesnumberwoensdag = $pdo->query('SELECT lesnumber FROM datums');
        $lessenmaandagdatums = $pdo->query('SELECT lessenmaandag FROM datums');
        $lessenwoensdagdatums = $pdo->query('SELECT lessenwoensdag FROM datums');

        ?>

        <div class="continside">
            <form method="post"> <input class="btnred" type="submit" name="logout" value="Logout"> </form>
        </div>
        <div class=smlrclt>
            <img alt="banner Nederlandse Lapidaristen Club" class=" fadein fullscreenlogomineral" src="headerbanner.jpg">
            <h1>Welkom bij School in Den Haag</h1>



            <p class="greeting abzc">
                <?php

                echo 'Hi, ' .  $usrnamehere . '!<br> Jij behoort tot de groep ' . $groupnaam .  '. <br>Jij hebt momenteel ' .  $_SESSION['aantalcredits'] . ' credits. <br>Meld je op tijd af voor een les om credits te verdienen.' . PHP_EOL;
                ?>
            </p>
            <h2> Lessen op maandag: </h2>
            <div class=tblcnt>
                <table>
                    <tr>
                        <th> nummer les
                        </th>
                        <th> meld je je af voor: </th>
                        <th> status </th>
                        <th> inhalen/ afmelden </th>
                    </tr>
                    <?php

                    $x = 0;
                    while (
                        $rowlesnumbermaandag = $lesnumbermaandag->fetch()
                        and $rowlessenmaandagdatums = $lessenmaandagdatums->fetch()

                    ) {
                        if ($lessenmaandagdatumsarray[$x] == 0) {
                            $maandaglesstatus = '<span class="aanafwijzig" style="color:red">afwijzig</span>';
                        }
                        if ($lessenmaandagdatumsarray[$x] == 1) {
                            $maandaglesstatus = '<span class="aanafwijzig" style="color:#11ff00ad;">aanwijzig</span>';
                        }
                        echo '<tr><td>' . $rowlesnumbermaandag['lesnumber'] . '</td><td>' . $rowlessenmaandagdatums['lessenmaandag'] . '</td>  <td>' . $maandaglesstatus .  '</td> 
                <td>  <a class="btnver" href="verander.php?lesnumbermaandag=' . $rowlesnumbermaandag['lesnumber'] .
                            '&lesnumberwoensdag=null"">Status veranderen!</a> </td>  </tr>' . PHP_EOL;
                        $x++;
                    }
                    ?>
                </table>
            </div>
            <h2> Lessen op woensdag: </h2>
            <div class=tblcnt>
                <table>
                    <tr>
                        <th> nummer les
                        </th>
                        <th> meld je je af voor: </th>
                        <th> status </th>
                        <th> inhalen/ afmelden </th>
                    </tr>
                    <?php

                    $x = 0;
                    while (
                        $rowlesnumberwoensdag = $lesnumberwoensdag->fetch()
                        and $rowlessenwoensdagdatums = $lessenwoensdagdatums->fetch()
                    ) {
                        if ($lessenwoensdagdatumsarray[$x] == 0) {
                            $woensdaglesstatus = '<span class="aanafwijzig" style="color:red">afwijzig</span>';
                        }
                        if ($lessenwoensdagdatumsarray[$x] == 1) {
                            $woensdaglesstatus = '<span class="aanafwijzig" style="color:#11ff00ad;">aanwijzig</span>';
                        }
                        echo '<tr><td>' . $rowlesnumberwoensdag['lesnumber'] . '</td><td>' . $rowlessenwoensdagdatums['lessenwoensdag'] . '</td>  <td>' . $woensdaglesstatus .  '</td> 
                <td>  <a class="btnver" href="verander.php?lesnumberwoensdag=' . $rowlesnumberwoensdag['lesnumber'] .
                            '&lesnumbermaandag=null">Status veranderen!</a> </td>  </tr>' . PHP_EOL;
                        $x++;
                    }

                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>