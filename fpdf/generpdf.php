<?php
require('fpdf.php');

/**
 * A class that allows us to generate a pdf
 */
class PDF extends FPDF
{
    public $test_p = "";
    public $test_n1 = "";
    public $test_n2 = "";

                function Header()
                {
                   
                    $parcours = "";
                    switch ($this->test_p) {
                    case 'IG':
                    $parcours = "Informatique Général (IG)";
                    break;
                    case 'GB':
                    $parcours = "Génie logiciel et Bases de données (GB)";
                    break;
                    case 'ASR':
                    $parcours = "Administrateur Système et Réseau (ASR)";
                    break;
                    }
                    if ($this->test_n1 = 'L1' || 'L2' || 'L3') {
                            $diplome = "LICENCE";
                    }else {
                        $diplome = "MASTER";
                    }
                    if ($this->test_n2 = 'L1' || 'L2' || 'M1') {
                        $periode = "D'ANNÉE";
                    } else {
                        $periode = "D'ETUDES";
                    }

                    $this->Ln(9);
                    // Police Arial gras 15
                    $this->SetFont('Arial', '', 11);
                    // Décalage
                    $this->Cell(80);
                    // Titre
                    $this->Cell(30, 10, 'PROCES VERBAL');
                    // Saut de ligne
                    $this->Ln(15);

                    // Police Arial gras 15
                    $this->SetFont('Arial', '', 11);
                    $this->Cell(20);
                    // Titre
                    $this->Cell(30, 10,utf8_decode('SOUTENANCE DE FIN '. $periode .' POUR L\'OBTENTION DU DIPLOME DE '. $diplome .''));
                    // Saut de ligne
                    $this->Ln(9);

                    // Police Arial gras 15
                    $this->SetFont('Arial', '', 11);
                    // Décalage
                    $this->Cell(77);
                    // Titre
                    $this->Cell(30, 10,'PROFESSIONNELLE');
                    // Saut de ligne
                    $this->Ln(15);

                    // Police Arial gras 15
                    $this->SetFont('Arial', 'B', 11);
                    // Décalage
                    $this->Cell(75);
                    // Titre
                    $this->Cell(30, 10,'Mention :');
                    // Police Arial gras 15
                    $this->SetFont('Arial', '', 11);
                    // Décalage
                    $this->Cell(-10);
                    // Titre
                    $this->Cell(30, 10,'Informatique');
                    // Saut de ligne
                    $this->Ln(9);

                    // Police Arial gras 15
                    $this->SetFont('Arial', 'B', 11);
                    // Décalage
                    $this->Cell(57);
                    // Titre
                    $this->Cell(30, 10,'Parcours :');
                    // Police Arial gras 15
                    $this->SetFont('Arial', '', 11);
                    // Décalage
                    $this->Cell(-7);
                    // Titre
                    $this->Cell(30, 10,utf8_decode(''. $parcours .''));
                    // Saut de ligne
                    $this->Ln(10);
                }
            }

$hostname = "localhost";
$username = "root";
$password = "";
$database = "gestion_des_soutenances";
$conn = mysqli_connect($hostname, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("La connexion a échoué : " . mysqli_connect_error());}

$id = $_GET['id'];
$sql = "SELECT soutenir.note as s_note, soutenir.rapporteur_ext as s_r_ext,
soutenir.matricule as s_matricule, etudiant.nom as e_nom, etudiant.prenoms as e_prenoms, etudiant.niveau as e_niveau, etudiant.parcours as e_parcours,
p1.civilite as president_civilite,   p1.nom as president_nom,    p1.prenoms as president_prenoms,    p1.grade as president_grade,
p2.civilite as examinateur_civilite, p2.nom as examinateur_nom,  p2.prenoms as examinateur_prenoms,  p2.grade as examinateur_grade,
p3.civilite as r_int_civilite,       p3.nom as r_int_nom,        p3.prenoms as r_int_prenoms,        p3.grade as r_int_grade

FROM soutenir
JOIN etudiant ON soutenir.matricule = etudiant.matricule
JOIN organisme ON soutenir.idorg = organisme.idorg
JOIN professeur p1 ON soutenir.president = p1.idprof
JOIN professeur p2 ON soutenir.examinateur = p2.idprof
JOIN professeur p3 ON soutenir.rapporteur_int = p3.idprof WHERE soutenir.id = $id LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die(mysqli_error($conn));
}


mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){

            $etudiant = $row["e_nom"] . " " . $row["e_prenoms"];
            $president = $row["president_civilite"] . " " . $row["president_nom"] . " " . $row["president_prenoms"] . "," . " " . $row["president_grade"];
            $examinateur = $row["examinateur_civilite"] . " " . $row["examinateur_nom"] . " " . $row["examinateur_prenoms"] . "," . " " . $row["examinateur_grade"];
            $rapporteur_int  = $row["r_int_civilite"] . " " . $row["r_int_nom"] . " " . $row["r_int_prenoms"] . "," . " " . $row["r_int_grade"];
            $rapporteur_ext = $row["s_r_ext"];
            $niveau = $row["e_niveau"];
            switch ($niveau) {
                case 'L1':
                    $motif = "d'année";
                    $soutenance = "Licence professionnelle I";
                    break;
                case 'L2':
                    $motif = "d'année";
                    $soutenance = "Licence professionnelle II";
                    break;
                case 'L3':
                    $motif = "d'études";
                    $soutenance = "Licence professionnelle";
                    break;
                case 'M1':
                    $motif = "d'année";
                    $soutenance = "Master professionnelle I";
                    break;
                case 'M2':
                    $motif = "d'études";
                    $soutenance = "Master professionnelle";
                    break;
            }
            $note_chifre = $row["s_note"];
            $note_letre = "";
            switch ($note_chifre) {
                case '1':
                    $note_letre = "Un";
                    break;
                    case '1.5':
                    $note_letre = "Un virgule cinq";
                    break;
                case '2':
                    $note_letre = "Deux";
                    break;
                    case '2.5':
                    $note_letre = "Deux virgule cinq";
                    break;
                case '3':
                    $note_letre = "Trois";
                    break;
                    case '3.5':
                    $note_letre = "Troi virgule cinqs";
                    break;
                case '4':
                    $note_letre = "Quatre";
                    break;
                    case '4.5':
                    $note_letre = "Quat virgule cinqre";
                    break;
                case '5':
                    $note_letre = "Cinq";
                    break;
                    case '5.5':
                    $note_letre = "Cinq virgule cinq";
                    break;
                case '6':
                    $note_letre = "Six";
                    break;
                    case '6.5':
                    $note_letre = "Six virgule cinq" ;
                    break;
                case '7':
                    $note_letre = "Sept";
                    break;
                    case '7.5':
                    $note_letre = "Sept virgule cinq";
                    break;
                case '8':
                    $note_letre = "Huit";
                    break;
                    case '8.5':
                    $note_letre = "Huit virgule cinq";
                    break;
                case '9':
                    $note_letre = "Neuf";
                    break;
                    case '9.5':
                    $note_letre = "Neuf virgule cinq";
                    break;
                case '10':
                    $note_letre = "Dix";
                    break;
                    case '10.5':
                    $note_letre = "Dix virgule cinq";
                    break;
                case '11':
                    $note_letre = "Onze";
                    break;
                    case '11.5':
                    $note_letre = "Onze virgule cinq";
                    break;
                case '12':
                    $note_letre = "Douze";
                    break;
                    case '12.5':
                    $note_letre = "Douze virgule cinq";
                    break;
                case '13':
                    $note_letre = "Treize";
                    break;
                    case '13.5':
                    $note_letre = "Treize virgule cinq";
                    break;
                case '14':
                    $note_letre = "Quatorze";
                    break;
                    case '14.5':
                    $note_letre = "Quatorze virgule cinq";
                    break;
                case '15':
                    $note_letre = "Quinze";
                    break;
                    case '15.5':
                    $note_letre = "Quinze virgule cinq";
                    break;
                case '16':
                    $note_letre = "Seize";
                    break;
                    case '16.5':
                    $note_letre = "Seize virgule cinq";
                    break;
                case '17':
                    $note_letre = "Dix-sept";
                    break;
                    case '17.5':
                    $note_letre = "Dix-sept virgule cinq";
                    break;
                case '18':
                    $note_letre = "Dix-huit";
                    break;
                    case '18.5':
                    $note_letre = "Dix-huit virgule cinq";
                    break;
                case '19':
                    $note_letre = "Dix-neuf";
                    break;
                    case '19.5':
                    $note_letre = "Dix-neuf virgule cinq";
                    break;
                case '20':
                    $note_letre = "Vingt";
                    break;
                    case '20':
                    $note_letre = "Vingt";
                    break;
            }

            $pdf = new PDF('P','mm','A4');
            $pdf->test_p = $row["e_parcours"];
            $pdf->test_n1 = $row["e_niveau"];
            $pdf->test_n2 = $row["e_niveau"];
            $pdf->AddPage();
            $pdf->Ln(5);
            $pdf->SetFont('Arial','',);
            $pdf->cell(10);
            $pdf->Cell(30,10,utf8_decode('Mr/Mlle '. $etudiant .''));
            $pdf->Ln(10);
            $pdf->SetFont('Arial','',);
            $pdf->cell(10);
            $pdf->Cell(30,10,utf8_decode('a soutenu publiquement son mémoire de fin '. $motif .' pour l\'obtention du diplôme'));
            $pdf->Ln(7);
            $pdf->SetFont('Arial','',);
            $pdf->cell(10);
            $pdf->Cell(30,10,utf8_decode('de '. $soutenance .''));
            $pdf->Ln(10);
            $pdf->SetFont('Arial','',);
            $pdf->cell(10);
            $pdf->Cell(30,10,utf8_decode('Après la délibération, la commission des membres du Jury a attribué la note de '. $note_chifre .'/20'));
            $pdf->Ln(7);
            $pdf->SetFont('Arial','',);
            $pdf->cell(10);
            $pdf->Cell(30,10,utf8_decode('('. $note_letre .' sur vingt)'));
            $pdf->Ln(13);
            $pdf->SetFont('Arial','',);
            $pdf->cell(10);
            $pdf->SetFont('', 'U');
            $pdf->Cell(30,10,'Membres du Jury');
            $pdf->Ln(13);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->cell(10);
            $pdf->Cell(30, 10,utf8_decode('Président :'));
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(30, 10,utf8_decode(''. $president .''));
            $pdf->Ln(13);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->cell(10);
            $pdf->Cell(30, 10,'Examinateur :');
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(30, 10,utf8_decode(''. $examinateur .''));
            $pdf->Ln(13);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->cell(10);
            $pdf->Cell(30, 10,'Rapporteurs :');
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(30, 10,utf8_decode(''. $rapporteur_int .''));
            $pdf->Ln(13);
            $pdf->Cell(40);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(30, 10,utf8_decode(''. $rapporteur_ext .''));

            $nom_pdf = "proces verbal $etudiant";
            $pdf->Output('I', ''. $nom_pdf .'.pdf','true');
                }
            }
else echo "Here I am";
?>