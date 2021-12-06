<?php
require_once '_db.php';

date_default_timezone_set("UTC");

  $stmt = $db->prepare('DELETE FROM events');
  $stmt->execute();

  $stmt = $db->prepare('SELECT * FROM resources');
  $stmt->execute();
  $scheduler_resources = $stmt->fetchAll();

  foreach($scheduler_resources as $resource) {
    $rid = $resource['id'];

    for ($i = 0; $i <= 400; $i++) {
        $start = new DateTime('2014-01-01');
        $start->modify("+".$i." days");
        $startstr = $start->format('Y-m-d H:i:s');

        $end = clone $start;
        $end->modify("+2 days");
        $endstr = $end->format('Y-m-d H:i:s');

        $name = "Event ".$i;

        $stmt = $db->prepare("INSERT INTO events (name, start, end, resource_id) VALUES (:name, :start, :end, :resource)");
        $stmt->bindParam(':start', $startstr);
        $stmt->bindParam(':end', $endstr);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':resource', $rid);
        $stmt->execute();
    }

  }

class Result {}

$response = new Result();
$response->result = 'OK';
$response->message = 'Created';

header('Content-Type: application/json');
echo json_encode($response);
