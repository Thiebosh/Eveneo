<?php 
$title = 'Eveneo - Evénement';


ob_start(); ?>
    <h1><?= htmlspecialchars($data['title']) ?></h1>
<?php $headerContent = ob_get_clean();


ob_start(); ?>
    <li>
        <form method="post" action="index.php">
            <input type="submit" value="Accueil">
        </form>
    </li>
    <!--si envoie date
    <li>
        <form method="post" action="index.php?action=allEvents&amp;date=< ?= $data['id']">
            <input type="submit" value="Liste des événements de < ?= htmlspecialchars($data['day']) ?>">
        </form>
    </li>
    -->
<?php $menuContent = ob_get_clean();


ob_start(); ?>
    <form method="post" action="index.php?action=deleteEvents&amp;id=<?= $data['id'] ?>"><!--valide?-->
        <input type="submit" value="Supprimer l'événement">
    </form>
<?php $asideContent = ob_get_clean();


ob_start(); ?>
    <div class="eventDetail">
        <h3>
            <?= htmlspecialchars($dataEvent['nameConf']) ?>
        </h3>
        <?= htmlspecialchars($dataEvent['startDate']) ?>
        <?= htmlspecialchars($dataEvent['endDate']) ?>
        <?= htmlspecialchars($dataEvent['organizer']) ?>
        <?= htmlspecialchars($dataEvent['place']) ?>
        <?= htmlspecialchars($dataEvent['describeConf']) ?>
    </div>
<?php $articleContent = ob_get_clean();

require('View/template.php');
