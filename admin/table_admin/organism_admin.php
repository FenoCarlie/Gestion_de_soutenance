<?php require '../../require/connection_DB.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../DataTables/datatables.min.css" rel="stylesheet"/>
    <link href="../../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="../../fontawesome/css/all.min.css" rel="stylesheet"/>
    <title>Document</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid mt-2">
        <a class="navbar-brand" href="../admin.php">GESTION DES SOUTENANCES</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Dropdown link
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <li><a class="dropdown-item" href="../table_admin/organism_admin.php">Etudiant</a></li>
                <li><a class="dropdown-item" href="../table_admin/professeur_admin.php">Professeur</a></li>
                <li><a class="dropdown-item" href="../table_admin/soutenance_admin.php">soutenue</a></li>
                <li><a class="dropdown-item" href="../table_admin/soutenir_admin.php">soutenir</a></li>
            </ul>
            </li>
        </ul>
        </div>
    </div>
    </nav>
    <div class="container mt-3">
        <a href="../../admin/add_new/add_etudiant.php">Ajouter une nouvelle organisme</a>
        
        <?php
            if(isset($_GET['msg'])) {
                $msg = $_GET['msg'];
                echo '<div class="alert aler-warning alert-dismissible fade show" role="alert">
                '.$msg.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }

            $sql = "SELECT * FROM organisme";
            $result = mysqli_query($conn, $sql);
            
            // Afficher les données dans une table HTML
            
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo '<table class="table table-hover text-center">';
                    echo '<thead>';
                    echo '<tr>
                            <th scope="col">Designation</th>
                            <th scope="col">Lieu</th>
                            </tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    echo '<tr>
                            <td>' . $row['design']. '</td>
                            <td>' . $row['lieu']. '</td>
                            <td>
                            <a href="../modif/modif_organisme.php?idorg=<?php echo $row["ididorg"]?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3" style="color: #2766d3;"></i></a>
                            <a href="../delet/delet_organisme.php?idorg=<?php echo $row["idorg"]" class="link-dark"><i class="fa-solid fa-trash fs-5 me-3" style="color: #d12335;"></i></i></a>
                            </td>
                        </tr>';
                    echo '</tbody>';
                }
            } else {
                echo '<div class="alert alert-dark mt-3" role="alert"><p class="h1">Aucun etudiant inscrit</p></div>';
            }
            
            echo "</table>";
        ?>
    </div>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
</body>
</html>