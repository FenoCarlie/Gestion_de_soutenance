<?php
require '../../require/connection_DB.php';

if(isset($_POST['submit'])) {
    $idorg = $_POST['idorg'];
    $president = $_POST['president'];
    $examinateur = $_POST['examinateur'];
    $rapporteur_ext = $_POST['president'];
    $rapporteur_int = $_POST['rapporteur_int'];
    $annee_univ = $_POST['annee_univ'];
    $matricule = $_POST['matricule'];
    $note = $_POST['note'];
    
    if (!preg_match("/^\d{4}-\d{4}$/", $annee_univ)) {
        $error_msg = "Le format de l'année universitaire est incorrect, ex: 2000-2001";
    } else {
        // vérifie si les champs obligatoires sont remplis
        if(empty($matricule) || empty($note) || empty($date) || empty($organisme)) {
            $error_msg = "Les champs Matricule, note, date et organisme sont obligatoires.";
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
                $stmt = $conn->prepare("INSERT INTO etudiant(`matricule`, idorg, `annee_univ`, note, president, examinateur, raporteur_int, `raporteur_ext`)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $matricule, $idorg, $annee_univ, $note, $president, $examinateur, $raporteur_int, $raporteur_ext);
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

    <title>add_etudient</title>
</head>
<body>

    <div class="container">
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
                    <label class="form-label">Note</label>
                    <input type="number" class="form-control" name="note" placeholder="note" value="<?php if(isset($_POST['note'])) echo htmlspecialchars($_POST['note'], ENT_QUOTES); ?>">
                </div>
                <div class="col">
                    <label class="form-label">Année universitaire</label>
                    <input type="text" class="form-control" name="annee_univ" placeholder="yyyy-yyyy" value="<?php if(isset($_POST['annee_univ'])) echo htmlspecialchars($_POST['annee_univ'], ENT_QUOTES); ?>">
                </div>
            <?php
                $sql = "SELECT * FROM organisme";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($result) > 0){
                    echo '<div class="col">';
                    echo '<label class="form-label">Organisme</label>';
                    echo '<select class="form-select" aria-label="Default select example" name="idorg">';
                    echo '<option disabled selected value="">Choisissez un organisme</option>';
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<option value="'. htmlspecialchars($row['idorg']) .' '.$selected.'">'. htmlspecialchars($row['design']) .'</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                }else{
                    echo '<div class="col"><label class="form-label">Organisme</label><input type="text" class="form-control" value="Pas d\'organisme inscrit" readonly></div>';
                }
            ?>
            </div>
            <?php
                $sql = "SELECT * FROM professeur";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($result) > 0){
                    echo '<div class="col mt-3">';
                    echo '<label class="form-label">Président des jurys</label>';
                    echo '<select class="form-select" aria-label="Default select example" name="president" onchange="updateSelects()">';
                    echo '<option disabled selected value="">Choisissez un président des jurys</option>';
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<option value="'. htmlspecialchars($row['idprof']) .'">'. htmlspecialchars($row['civilite']) . ' ' . htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['prenoms']) .'</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                    mysqli_data_seek($result, 0);
                
                    echo '<div class="col mt-3">';
                    echo '<label class="form-label">Examinateur</label>';
                    echo '<select class="form-select" aria-label="Default select example" name="examinateur" onchange="updateSelects()">';
                    echo '<option disabled selected value="">Choisissez un examinateur</option>';
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<option value="'. htmlspecialchars($row['idprof']) .'">'. htmlspecialchars($row['civilite']) . ' ' . htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['prenoms']) .'</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                    mysqli_data_seek($result, 0);
                
                    echo '<div class="col mt-3">';
                    echo '<label class="form-label">Rapporteur interne</label>';
                    echo '<select class="form-select" aria-label="Default select example" name="rapporteur_int" onchange="updateSelects()">';
                    echo '<option disabled selected value="">Choisissez un rapporteur interne</option>';
                    while($row = mysqli_fetch_assoc($result)){
                        echo '<option value="'. htmlspecialchars($row['idprof']) .'">'. htmlspecialchars($row['civilite']) . ' ' . htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['prenoms']) .'</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                }else{
                    echo '<div class="col"><label class="form-label">Membre du jury</label><input type="text" class="form-control" value="Pas de professeur inscrit" readonly></div>';
                }
            ?>
            <div class="row mb-3 mt-3">
            <label class="form-label">Rapporteur extairne</label>
                <div class="col">
                    <label class="form-label">Civilité</label>
                    <select class="form-select" aria-label="Default select example" name="civilite">
                        <option disabled selected value="">Choisissez une civilité</option>
                        <option value="Mr" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'Mr') echo 'selected'; ?>>Mr</option>
                        <option value="Mlle" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'Mlle') echo 'selected'; ?>>Mlle</option>
                        <option value="Mme" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'Mme') echo 'selected'; ?>>Mme</option>
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Nom</label>
                    <input type="text" class="form-control" name="nom" placeholder="Nom" value="<?php if(isset($_POST['nom'])) echo $_POST['nom']; ?>">
                </div>
                <div class="col">
                    <label class="form-label">Prénom</label>
                    <input type="text" class="form-control" name="prenoms" placeholder="Prénom" value="<?php if(isset($_POST['prenoms'])) echo $_POST['prenoms']; ?>">
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-success" name="submit">sauvegarder</button>
                <a href="../../admin.php" class="btn btn-danger">annuler</a>
            </div>
        </form>
    </div>
    <script src="../../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"> </script>
    <script src="../../fontawesome/js/all.min.js"></script>
</body>
</html>