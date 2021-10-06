<?php 
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

// Récupération des données de la bdd 
$liste_categories = $pdo->query("SELECT DISTINCT categorie FROM salle ORDER BY categorie");
$liste_villes = $pdo->query("SELECT DISTINCT ville FROM salle ORDER BY ville DESC");
$liste_capacites = $pdo->query("SELECT DISTINCT capacite FROM salle ORDER BY capacite");


// Récupération des données des tables produit et salle selon filtre
if( isset($_GET['categorie'])) {
    $liste_produits = $pdo->prepare("SELECT * FROM salle INNER JOIN produit ON produit.id_salle = salle.id_salle WHERE categorie = :categorie AND date_arrivee > CURDATE() AND etat = 'libre' ");
    $liste_produits->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
    $liste_produits->execute();
    
    } else if(isset($_GET['ville'])) {
        $liste_produits = $pdo->prepare("SELECT * FROM salle INNER JOIN produit ON produit.id_salle = salle.id_salle WHERE ville = :ville AND date_arrivee > CURDATE() AND etat = 'libre' ");
        $liste_produits->bindParam(':ville', $_GET['ville'], PDO::PARAM_STR);
        $liste_produits->execute();
    
    } else {
        $liste_produits = $pdo->query("SELECT * FROM produit INNER JOIN salle ON produit.id_salle = salle.id_salle WHERE date_arrivee > CURDATE() AND etat = 'libre' ");
}

// Récupération bdd
$produit = $liste_produits->fetchAll(PDO::FETCH_ASSOC);
$nbre_resultats = count($produit);


// Calcul de la moyenne des notes d'une salle
$liste_id_salles = $pdo->query("SELECT id_salle FROM salle ");
$liste_id_salles = $liste_id_salles->fetchAll(PDO::FETCH_ASSOC);
$nbre_salles = count($liste_id_salles); // récupère le nbre de salles

for ($i=1; $i<($nbre_salles+1); $i++) { //boucle pour créer tableau avec la note moyenne de chaque salle
    $note = $pdo->query("SELECT AVG(note) AS note FROM avis WHERE id_salle = $i ");
    $note_moyenne[$i]= $note->fetch(PDO::FETCH_ASSOC);
}

//
//
// Les affichages dans la page commencent depuis la ligne suivante :
//------------------------------------------------------------------
//
//



include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>


<main class="container-fluid px-4">

    <?php echo $msg . '<br>'; ?>

    <div class="row">

        <!-- Affichage des filtres de recherche sur la gauche -->
        <div class="col-sm-2 mt-2">

        <div class="bg-light mt-5 rounded">
        <h1 class="pb-4 border-bottom"><i class="fas fa-building couleur_icone"></i> Bienvenue </h1>
        <p class="lead">Choisissez votre categorie et votre ville pour louer une salle</p>
        <?php echo $msg . '<hr>'; // variable destinée à afficher des messages utilisateur  
        ?>
    </div>
            <div>
                <ul class="list-group">
                    <li class="list-group-item bg-primary text-white" aria-current="true">Catégorie : </li>
                    <li class="list-group-item"><a href="<?php echo URL; ?>" class="text-dark">Toutes les catégories</a></li> 
                <?php
                    while($ligne = $liste_categories->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li class="list-group-item"><a href="?categorie=' . $ligne['categorie'] . ' " class="text-dark">' . ucfirst($ligne['categorie']) . '</a></li>';
                    }
                ?>           
                </ul>
            </div>
            <div>
                <ul class="list-group mt-3">
                    <li class="list-group-item bg-primary text-white" aria-current="true">Ville : </li>
                <?php               
                    while($ligne = $liste_villes->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li class="list-group-item"><a href="?ville=' . $ligne['ville'] . '" class="text-dark">' . ucfirst($ligne['ville']) . '</a></li>';
                    }
                ?>           
                </ul>
            </div>
            <div class="mt-3">
                <label for="capacite" class="form-label ps-2 my-1">Capacité : </label>
                <select class="form-control" id="capacite" name="capacite">
                <?php
                    $lc = $liste_capacites->fetchAll(PDO::FETCH_ASSOC);
                    for($i = 0; $i < count($lc); $i++) {
                        echo '<option>' . $lc[$i]['capacite'] . '</option>';
                    }
                ?>
                </select>
            </div>
            <div>
                <?php echo '<p class="text-center fst-italic m-5">' . $nbre_resultats . ' résultat(s)</p>' ?>
            </div>
        </div>


        <!-- Affichage des résultats -->
        <div class="col-sm-10 mt-2">
            <div class="row">
                <?php

                // condition pour verifier si des salles sont dispo
                if (!empty($produit)) {
                    for ($i=0; $i < count($produit); $i++)  {
                        echo '
                        <div class=" col-sm-6 col-lg-4 mb-3">
                            <div class="card">
                                <img src="' . URL . 'assets/images_salles/' . $produit[$i]['photo'] . '" class="card-img-top img-thumbnail" alt="Une image salle : ' . $produit[$i]['categorie'] . '">
                                <div class="card-body py-2">
                                    <h5 class="card-title">
                                        <div class="d-flex justify-content-between">
                                            <span>' . ucfirst($produit[$i]['categorie']) . ' ' . ucfirst($produit[$i]['titre']) . '</span>
                                            <strong>Prix : ' . $produit[$i]['prix'] . ' €</strong>
                                        </div>
                                    </h5>
                                    <p class="card-text mb-1">' . substr($produit[$i]['description'], 0, 35) . '...' . '</p>
                                    <p class="card-text">Du ' . date("d/m/y", strtotime($produit[$i]['date_arrivee'])) . ' au ' . date("d/m/y", strtotime($produit[$i]['date_depart'])) . '</p>
                                    <div>Note moyenne</div>
                                     

                                    <div class="d-flex justify-content-between">
                                        <span>' . number_format($note_moyenne[$produit[$i]['id_salle']]['note'], 1) . ' / 5</span>
                                        <a href="' . URL. 'fiche_produit.php?id_produit=' .  $produit[$i]['id_produit'] . '" class="text-dark"><strong><i class="fas fa-search"></i>Voir</strong></a>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    } 
                } else {
                    echo '<div class="alert alert-danger mt-3">Désolé, plus aucune salle n\'est disponible.</div>';
                }
                    

                ?>
            </div>          
        </div>
    </div>
</main>


<br><br><br>


<?php 
include 'inc/footer.inc.php';