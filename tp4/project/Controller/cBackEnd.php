<?php
require('model/mBackEnd.php');

function oEventsMonth($date) {
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

    require('View/BackEnd/vReception.php');
}


function oEventsDay($date) {
    $showDate = strftime('%A %e %B %Y', strtotime($date));

    $dateSplit = explode('-', $date);
    $lastDay = date('Y-m-d', gmmktime(0, 0, 0, $dateSplit[1], $dateSplit[2] - 1, $dateSplit[0]));
    $nextDay = date('Y-m-d', gmmktime(0, 0, 0, $dateSplit[1], $dateSplit[2] + 1, $dateSplit[0]));

    $eventsDay = getEventsDay($date, false);
    if (!$eventsDay) throw new Exception('Evénements du jour : Echec de récupération des données');
    
    require('View/BackEnd/vAllEvents.php');
}


function oEvent($id) {
    if (isset($_POST['script_delete'])) {
        $dateRedirection = getEventDate($id);
        if (!$dateRedirection) throw new Exception('Suppression : Echec de redirection');
    
        deleteEvent($id);//throw new Exception('Echec de suppression des données');//verifier que renvoi d'un delete est true ou false

        header('Location: index.php?action=reception&date='.$dateRedirection);//recharge la page
        exit();
    }
    
    $lastEvent = getOtherEventDate($id, 'last');
    $nextEvent = getOtherEventDate($id, 'next');
    if (!$lastEvent || $nextEvent) throw new Exception('Evénement : Echec de récupération des données');
    
    $dataEvent = getEvent($id);
    if (!$dataEvent) throw new Exception('Evénement : Echec de récupération des données');
    $dateStart = strftime('%A %e %B %Y, %Hheures %i', strtotime($dataEvent['datestart']));
    $dateEnd = strftime('%A %e %B %Y, %Hheures %i', strtotime($dataEvent['dateend']));
    
    require('View/BackEnd/vEvent.php');
}


function oEventNew($dataPage) {
    if (isset($_POST['script_new'])) {
        postEventData($dataPage);//throw new Exception('Création d\'événement : Echec d\'enregistrement des données');
        $id = getEventId($dataPage);//checker retours de requete
        if (!$idEvent) throw new Exception('Création d\'événement : Echec de redirection');
        
        header('Location: index.php?action=detail&id='.$id);//recharge la page
        exit();
    }

    require('View/BackEnd/vNewEvent.php');
}


function oEventEdit($dataPage) {
    if (isset($_POST['script_edit'])) {
        changeEventData($dataPage);// throw new Exception('Modification d\'événement : Echec d\'enregistrement des données');
        
        header('Location: index.php?action=detail&id='.$dataPage['id']);//recharge la page
        exit();
    }

    require('View/BackEnd/vEditEvent.php');
}
