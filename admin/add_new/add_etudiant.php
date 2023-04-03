<?php
require '../../require/connection_DB.php';

if(isset($_POST['submit'])) {
    $prenoms = $_POST['prenoms'];
    if (isset($_POST['niveau'])) {
        $niveau = $_POST['niveau'];
    } else {
        $niveau = "";
    }
    if (isset($_POST['parcours'])) {
        $parcours = $_POST['parcours'];
    } else {
        $parcours = "";
    }
    $adr_mail = $_POST['adr_email'];
    $matricule = $_POST['matricule'];
    $nom = $_POST['nom'];
    
    if (strlen($nom) < 2) {
        $error_msg = "Le nom doit contenir au moins deux caractères.";
    } else {
        // vérifie si les champs obligatoires sont remplis
        if(empty($matricule) || empty($matricule) || empty($nom) || empty($parcours)) {
            $error_msg = "Les champs Matricule, nom, Niveau et Parcours sont obligatoires.";
        } else {
            // vérifie si un enregistrement avec le même matricule existe déjà
            $stmt = $conn->prepare("SELECT * FROM etudiant WHERE matricule=?");
            $stmt->bind_param("s", $matricule);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // un enregistrement avec le même matricule existe déjà
                $error_msg = "Un étudiant avec ce matricule existe déjà.";
            } else {
                // insére un nouvel enregistrement
                $stmt = $conn->prepare("INSERT INTO etudiant(`matricule`, `nom`, `prenoms`, `niveau`, `parcours`, `adr_email`)
                            VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $matricule, $nom, $prenoms, $niveau, $parcours, $adr_mail);
                $stmt->execute();

                if($stmt->affected_rows > 0) {
                    header("Location: ../table_admin/etudiant_admin.php?msg=Anregistrement reussite");
                    exit();
                } else {
                    echo "Erreur: " . $conn->error;
                }
            }
        }
    }

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">

    <style>
        .navbar {
            background-color: #2bc791;
        }
    </style>

    <title>add_etudient</title>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
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
                            <li><a class="dropdown-item" href="../table_admin/etudiant_admin.php">Etudiant</a></li>
                            <li><a class="dropdown-item" href="../table_admin/professeur_admin.php">Professeur</a></li>
                            <li><a class="dropdown-item" href="../table_admin/organisme_admin.php">organisme</a></li>
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

    <div class="container mt-4">
        <div class="text-center mb4">
            <h1>Ajouter des étudiants à la liste</h1>
            <p>Complétez le formulaire ci-dessous pour ajouter un nouvel étudiant</p>
        </div>
    </div>

    <div class="container d-flex justify-content-center">
        <form action="" method="post" style="width:50vw; min-width:300px;">
            <?php if(isset($error_msg)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_msg, ENT_QUOTES); ?></div>
            <?php endif; ?>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Matricule</label>
                    <input type="text" class="form-control" name="matricule" placeholder="matricule" value="<?php if(isset($_POST['matricule'])) echo htmlspecialchars($_POST['matricule'], ENT_QUOTES); ?>">
                </div>
                <div class="col">
                    <label class="form-label">Nom</label>
                    <input style="text-transform: uppercase;" type="text" class="form-control" name="nom" placeholder="Nom" value="<?php if(isset($_POST['nom'])) echo htmlspecialchars($_POST['nom'], ENT_QUOTES); ?>">
                </div>
                <div class="col">
                    <label class="form-label">Prénom</label>
                    <input type="text" class="form-control" name="prenoms" placeholder="Prénom" value="<?php if(isset($_POST['prenoms'])) echo htmlspecialchars($_POST['prenoms'], ENT_QUOTES); ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Niveau</label>
                    <select class="form-select" aria-label="Default select example" name="niveau">
                    <option disabled selected value="">Choisissez un niveau</option>
                    <option value="L1" <?php if(isset($_POST['niveau']) && $_POST['niveau'] == 'L1') echo 'selected'; ?>>L1</option>
                    <option value="L2" <?php if(isset($_POST['niveau']) && $_POST['niveau'] == 'L2') echo 'selected'; ?>>L2</option>
                    <option value="L3" <?php if(isset($_POST['niveau']) && $_POST['niveau'] == 'L3') echo 'selected'; ?>>L3</option>
                    <option value="M1" <?php if(isset($_POST['niveau']) && $_POST['niveau'] == 'M1') echo 'selected'; ?>>M1</option>
                    <option value="M2" <?php if(isset($_POST['niveau']) && $_POST['niveau'] == 'M2') echo 'selected'; ?>>M2</option>
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Parcours</label>
                    <select class="form-select" aria-label="Default select example" name="parcours">
                        <option disabled selected value="">Choisissez une parcours</option>
                        <option value="GB" <?php if(isset($_POST['parcours']) && $_POST['parcours'] == 'GB') echo 'selected'; ?>>GB</option>
                        <option value="ASR" <?php if(isset($_POST['parcours']) && $_POST['parcours'] == 'ASR') echo 'selected'; ?>>ASR</option>
                        <option value="IG" <?php if(isset($_POST['parcours']) && $_POST['parcours'] == 'IG') echo 'selected'; ?>>IG</option>
                    </select>
                </div>
            </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="adr_email" placeholder="nom@exemple.com" value="<?php if(isset($_POST['adr_email'])) echo htmlspecialchars($_POST['adr_email'], ENT_QUOTES); ?>">
                </div>

            <div>
                <button type="submit" class="btn btn-success" name="submit">sauvegarder</button>
                <a href="../../admin/table_admin/etudiant_admin.php" class="btn btn-danger">annuler</a>
            </div>
        </form>
    </div>
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

    <script src="../../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"> </script>
    <script src="../../fontawesome/js/all.min.js"></script>
</body>
</html>