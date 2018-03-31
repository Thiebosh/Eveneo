<?php
require('Model/mFrontEnd.php');

function cEventsMonth($date) {
    if (empty($date)) $date = date('Y-m');

    $timeStamp = strtotime($date);
    $showDate = strftime('%B %Y', $timeStamp);
    $nbDayMonth = date('t', $timeStamp);

    $dateSplit = explode('-', $date);
    $lastMonth = date('Y-m', gmmktime(0, 0, 0, $dateSplit[1] - 1, 0, $dateSplit[0]));
    $nextMonth = date('Y-m', gmmktime(0, 0, 0, $dateSplit[1] + 1, 0, $dateSplit[0]));

    $dayName['ang'] = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
    $dayName['fr'] = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
    $dayStartMonth = date('D', gmmktime(0, 0, 0, $dateSplit[1], 1, $dateSplit[0]));//pour commencer le tableau d affichage
    $dayEndMonth = date('N', gmmktime(0, 0, 0, $dateSplit[1], $nbDay, $dateSplit[0]));//pour finir le tableau d affichage

    for ($day = 1; $day <= $nbDayMonth; $day++) {
        $fullDate = date('Y-m-d', gmmktime(0, 0, 0, $dateSplit[1], $day, $dateSplit[0]));
        $eventsMonth[] = getEventsDay($fullDate, true);//si vide, listEventsMonth[] vaudra false
    }

    require('View/FrontEnd/vReception.php');
}


function cEventsDay($date) {
    $showDate = strftime('%A %e %B %Y', strtotime($date));

    $dateSplit = explode('-', $date);
    $lastDay = date('Y-m-d', gmmktime(0, 0, 0, $dateSplit[1], $dateSplit[2] - 1, $dateSplit[0]));
    $nextDay = date('Y-m-d', gmmktime(0, 0, 0, $dateSplit[1], $dateSplit[2] + 1, $dateSplit[0]));

    $eventsDay = getEventsDay($date, false);
    if (!$eventsDay) throw new Exception('Evénements du jour : Echec de récupération des données');
    
    require('View/FrontEnd/vAllEvents.php');
}


function cEvent($id) {
    $status = getEventStatus($id);//si faux, n'est pas inscrit
    
    if (isset($_POST['script_joined'])) {
        if (!$status) postStatusEvent($id);//throw new Exception('Echec d\'enregistrement des données');//applique changement d etat (INSERT INTO renvoie quelque chose pour echec?)
        else          deleteStatusEvent($id);//throw new Exception('Echec d\'enregistrement des données');//applique changement d etat (DELETE FROM renvoie quelque chose pour echec?)

        header('Location: index.php?action=detail&id='.$id);//recharge la page
        exit();
    }

    $dataEvent = getEvent($id);
    if (!$dataEvent) throw new Exception('Evénement : Echec de récupération des données');
    $dateStart = strftime('%A %e %B %Y, %Hheures %i', strtotime($dataEvent['datestart']));
    $dateEnd = strftime('%A %e %B %Y, %Hheures %i', strtotime($dataEvent['dateend']));
    
    $lastEvent = getOtherEventDate($dataEvent['datestart'], 'last');
    $nextEvent = getOtherEventDate($dataEvent['datestart'], 'next');
    if (!$lastEvent || $nextEvent) throw new Exception('Evénement : Echec de récupération des données');
    
    require('View/FrontEnd/vEvent.php');
}
