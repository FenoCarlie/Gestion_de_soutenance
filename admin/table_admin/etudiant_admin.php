<?php require '../../require/connection_DB.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="../../fontawesome/css/all.min.css" rel="stylesheet"/>
    <title>Document</title>
    <style>
        .navbar {
            background-color: #2bc791;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container-fluid mt-2">
            <a class="navbar-brand" href="../admin.php">GESTION DES SOUTENANCES</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Table
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="../table_admin/organisme_admin.php">organisme</a></li>
                    <li><a class="dropdown-item" href="../table_admin/professeur_admin.php">Professeur</a></li>
                    <li><a class="dropdown-item" href="../table_admin/soutenir_admin.php">soutenir</a></li>
                </ul>
                </li>
            </ul>
            </div>
            <div class="d-flex">
                    <ul class="navbar-nav">
                        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">Deconecter</button>
                    </ul>
                </div>
        </div>
    </nav>
    <div class="container mt-3">
        <?php
        if (isset($_GET['msg'])) {
            $msg = htmlspecialchars($_GET['msg']);
            echo '<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                    '.$msg.'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
        ?>
        <a class="btn btn-outline-secondary mt-3" href="../../admin/add_new/add_etudiant.php" role="button">Ajouter un nouveau etudiant</a>
        
        <?php

            if(isset($_POST['search'])){
                $search = mysqli_real_escape_string($conn, $_POST['search']);
                $sql = "SELECT * FROM etudiant WHERE matricule LIKE '%$search%' OR nom LIKE '%$search%'";
            } else {
                $sql = "SELECT * FROM etudiant";
            }

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                echo '<div class="text-center m-2 "><h1>TABLES ETUDIANT</h1></div>';
                echo '<form action="" method="POST">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Rechercher par matricule ou nom">
                            <button type="submit" class="btn btn-outline-secondary">Rechercher</button>
                        </div>
                    </form>';
                echo '<table class="table table-hover text-center m-3">';
                echo '<thead>';
                echo '<tr class="table-dark">
                        <th scope="col">Matricule</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénoms</th>
                        <th scope="col">Niveau</th>
                        <th scope="col">Parcours</th>
                        <th scope="col">Adresse mail</th>
                        <th scope="col">action</th>
                    </tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                            <td>' . htmlspecialchars($row['matricule']) . '</td>
                            <td>' . htmlspecialchars($row['nom']) . '</td>
                            <td>' . htmlspecialchars($row['prenoms']) . '</td>
                            <td>' . htmlspecialchars($row['niveau']) . '</td>
                            <td>' . htmlspecialchars($row['parcours']) . '</td>
                            <td>' . htmlspecialchars($row['adr_email']) . '</td>
                            <td style="display: flex; justify-content: center;">
                                <form action="../modif/modif_etudiant.php?id='. htmlspecialchars($row["id"]) .'" method="POST">
                                    <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                                    <button type="submit" class="btn btn-link" ><i class="fa-solid fa-pen-to-square fs-5" style="color: #2766d3;"></i></button>
                                </form>
                                <form action="../delet/delet_etudiant.php?id='. htmlspecialchars($row["id"]) .'" method="POST" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cet étudiant ?\')">
                                    <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                                    <button type="submit" class="btn btn-link"><i class="fa-solid fa-trash fs-5" style="color: #d12335;"></i></button>
                                </form>
                                <form action="../add_new/add_soutenir.php?id='. htmlspecialchars($row["id"]) .'" method="POST">
                                    <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                                    <button type="submit" class="navbar btn btn-info">Soutenir</button>
                                </form>
                            </td>
                        </tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="text-center m-2 "><h1>TABLES ETUDIANT</h1></div>';
                echo '<form action="" method="POST">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Rechercher par matricule ou nom" value>
                            <button type="submit" class="btn btn-outline-secondary">Rechercher</button>
                        </div>
                    </form>';
                echo '<div class="alert alert-dark mt-3" role="alert"><p class="h1">Aucun résultat trouvé</p></div>';
            }
        ?>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    Êtes-vous sûr(e) de vouloir vous déconnecter ?
                    </div>
                    <div class="modal-footer">
                        <a href="../../index.php"><button type="button" class="btn btn-primary">oui</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../bootstrap-5.0.2-dist/js/bootstrap.min.js"></script>
</body>
</html>