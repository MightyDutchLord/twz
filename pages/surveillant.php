<?php
require_once '../lib/connectdb.php';
require_once '../lib/functions.php';
require_once '../lib/requireAuth.php';
require_once '../lib/requireSession.php';
require_once '../lib/requireAdmin.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <?php include_once "../includes/nav.php" ?>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Surveillanten
                    <small>Overzicht</small>
                    <a href="survcreate.php" role="button" class="btn btn-primary">Toevoegen</a>
                </h1>
                <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $werknemerID = cleanInput($_POST['werknemerid']);
                    $voornaam = cleanInput($_POST['voornaam']);
                    $tussenvoegsel = cleanInput($_POST['tussenvoegsel']);
                    $acthernaam = cleanInput($_POST['achternaam']);
                    $account = cleanInput($_POST['account']);
                    $email = cleanInput($_POST['email']);

                    if (validateNumber($werknemerID, 0, 2147483647) &&
                        validateInput($voornaam, 1, 128) &&
                        validateInput($acthernaam, 2, 128)
                    ) {

                        $data = array(
                            "WerknemerID" => $werknemerID,
                            "Voornaam" => $voornaam,
                            "Achternaam" => $acthernaam
                        );

                        if (validateInput($tussenvoegsel, 1, 16)) {
                            $data['Tussenvoegsel'] = $tussenvoegsel;
                        }

                        if($account && filter_input($email, FILTER_VALIDATE_EMAIL)) {
                            $password = randomPassword();
                            $register = $auth->register($email, $password, $password);

                            if($register['error'] == 0) {
                                echo '<div class="alert alert-success" role="alert">Het account is succesvol toegevoegd.</div>';
                            } else {
                                echo '<div class="alert alert-warning" role="alert">Het account voor de surveillant kon niet worden toegevoegd. ' . $register['message'] . '</div>';
                            }
                        }

                        $insert = $dataManager->insert('Surveillant', $data);
                        if ($insert) {
                            echo '<div class="alert alert-success" role="alert">De surveillant is succesvol toegevoegd.</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Fout bij het toevoegen aan de database.</div>';
                        }
                    } else {
                        echo '<div class="alert alert-warning" role="alert">Niet alle gegevens zijn juist ingevoerd.</div>';
                    }

                }
                ?>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>WerknemerID</th>
                                    <th>Voornaam</th>
                                    <th>Tussenvoegsel</th>
                                    <th>Achternaam</th>
                                    <th>Bewerken</th>
                                    <th>Verwijderen</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $surveillanten = $dataManager->get('Surveillant');
                                if ($dataManager->count > 0)
                                    foreach ($surveillanten as $surveillant) {
                                        echo '<tr>';
                                        echo '<td>' .  $surveillant["WerknemerID"] . '</td>';
                                        echo '<td>' .  $surveillant["Voornaam"] . '</td>';
                                        echo '<td>' .  $surveillant["Tussenvoegsel"] . '</td>';
                                        echo '<td>' .  $surveillant["Achternaam"] . '</td>';
                                        ?>
                                        <td>
                                            <form action="survedit.php" method="get">
                                                <button type="submit" class="btn btn-primary">Bewerken</button>
                                            </form>
                                        </td>
                                        <td>
                                        <form action="survdel.php" method="get">
                                            <input type="hidden" name="id" value="<?php echo $surveillant["ID"]; ?>">
                                            <button type="submit" class="btn btn-danger">Verwijderen</button>
                                        </form>
                                        </td>
                                <?php
                                        echo '</tr>';
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-6 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="../dist/js/sb-admin-2.js"></script>

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>

</body>

</html>
