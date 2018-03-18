<?php 
$title = 'Eveneo - Accueil';


ob_start(); ?>
    <h1>Accueil</h1>
    <p>Liste des événements du mois de <?= $infoPage['month'] ?></p>
<?php $headerContent = ob_get_clean();


$menuContent = '';


ob_start(); ?>
    <fieldset><legend>Changer de mois</legend>
        <form method="post" action="index.php?action=lastMonth">
            <input type="submit" value="Voir le mois précédent">
        </form>

        <form method="post" action="index.php?action=nextMonth">
            <input type="submit" value="Voir le mois suivant">
        </form>

        <form method="post" action="index.php?action=newEvent">
            <input type="submit" value="Ajouter une conférence">
        </form>
    </fieldset>
<?php $asideContent = ob_get_clean();


ob_start();
    foreach($listEventsMonth as $listEventsDay) {
        echo '<div class="jour">';
        if (!$listEventsDay) {
            echo 'Pas de conférence ce jour';
        }
        else {
            foreach($listEventsDay as $event) {
                $compteur = 0;
                ?>
                <div class="event">
                    <h3>
                        <?= htmlspecialchars($event['name']) ?>
                    </h3>
                    <form method="post" action="index.php?action=deleteEvents&amp;id=<?= $event['id'] ?>"><!--valide?-->
                        <input type="submit" value="Supprimer l'événement">
                    </form>
                </div>
                <?php
                $compteur++;
                if (compteur == 5) {
                    break;
                }
            }
            
            if (count($listEventsDay) > 5) {//au moins 6 : ajoute bouton au template
                ?>
                <form method="post" action="index.php?action=allEvent">
                    <input type="submit" value="Voir plus de conférences">
                </form>
                <?php
            }
        }
        echo '</div>';
    }
$articleContent = ob_get_clean();

require('View/template.php');
