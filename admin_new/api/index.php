<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/reviews', 'getReviews');
$app->get('/reviews/:id', 'getReview');
$app->get('/reviews/search/:query', 'findReviewByProject');
$app->post('/reviews', 'addReview');
$app->put('/reviews/:id', 'updateReview');
$app->delete('/reviews/:id', 'deleteReview');


$app->get('/contractors', 'getContractors');
$app->get('/contractors/:id', 'getContractor');
$app->get('/contractors/search/:query', 'findContractorByTitle');
$app->post('/contractors', 'addContractor');
$app->put('/contractors/:id', 'updateContractor');
$app->delete('/contractors/:id', 'deleteContractor');

$app->run();

function getReviews() {
    $sql = "select * FROM rene_review ORDER BY project";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $reviews = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        // echo '{"review": ' . json_encode($reviews) . '}';
        echo json_encode($reviews);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getReview($id) {
    $sql = "SELECT * FROM rene_review WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $review = $stmt->fetchObject();
        $db = null;
        echo json_encode($review);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addReview() {
    error_log('addReview\n', 3, '/var/tmp/php.log');
    $request = Slim::getInstance()->request();
    $review = json_decode($request->getBody());
    $sql = "INSERT INTO rene_review (project, date, region, description, rating) VALUES (:project, :date, :region, :description, :rating)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("project", $review->project);
        $stmt->bindParam("date", $review->date);
        $stmt->bindParam("region", $review->region);
        $stmt->bindParam("description", $review->description);
        $stmt->bindParam("rating", $review->rating);
        $stmt->execute();
        $review->id = $db->lastInsertId();
        $db = null;
        echo json_encode($review);
    } catch(PDOException $e) {
        error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateReview($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $review = json_decode($body);
    $sql = "UPDATE rene_review SET project=:project, date=:date, region=:region, description=:description, rating=:rating WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("project", $review->project);
        $stmt->bindParam("date", $review->date);
        $stmt->bindParam("region", $review->region);
        $stmt->bindParam("description", $review->description);
        $stmt->bindParam("rating", $review->rating);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo json_encode($review);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function deleteReview($id) {
    $sql = "DELETE FROM rene_review WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function findReviewByProject($query) {
    $sql = "SELECT * FROM rene_review WHERE UPPER(project) LIKE :query ORDER BY project";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $query = "%".$query."%";
        $stmt->bindParam("query", $query);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($reviews);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getContractors() {
    $sql = "select * FROM rene_contractor ORDER BY contractor_title";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $contractors = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        // echo '{"contractor": ' . json_encode($contractors) . '}';
        echo json_encode($contractors);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getContractor($id) {
    $sql = "SELECT * FROM rene_contractor WHERE contractor_id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $contractor = $stmt->fetchObject();
        $db = null;
        echo json_encode($contractor);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addContractor() {
    error_log('addContractor\n', 3, '/var/tmp/php.log');
    $request = Slim::getInstance()->request();
    $contractor = json_decode($request->getBody());
    $sql = "INSERT INTO rene_contractor (contractor_title, contractor_description, contractor_phone, contractor_address, contractor_name) VALUES (:contractor_title, :contractor_description, :contractor_phone, :contractor_address, :contractor_name)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("contractor_title", $contractor->contractor_title);
        $stmt->bindParam("contractor_description", $review->contractor_description);
        $stmt->bindParam("contractor_phone", $review->contractor_phone);
        $stmt->bindParam("contractor_address", $review->contractor_address);
        $stmt->bindParam("contractor_name", $review->contractor_name);
        $stmt->execute();
        $contractor->id = $db->lastInsertId();
        $db = null;
        echo json_encode($contractor);
    } catch(PDOException $e) {
        error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateContractor($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $contractor = json_decode($body);
    $sql = "UPDATE rene_contractor SET contractor_title=:contractor_title, contractor_description=:contractor_description, contractor_phone=:contractor_phone, contractor_address=:contractor_address, contractor_name=:contractor_name WHERE contractor_id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("contractor_title", $contractor->contractor_title);
        $stmt->bindParam("contractor_description", $contractor->contractor_description);
        $stmt->bindParam("contractor_phone", $contractor->contractor_phone);
        $stmt->bindParam("contractor_address", $contractor->contractor_address);
        $stmt->bindParam("contractor_name", $contractor->contractor_name);
        $stmt->bindParam("contractor_id", $id);
        $stmt->execute();
        $db = null;
        echo json_encode($contractor);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function deleteContractor($id) {
    $sql = "UPDATE rene_contractor SET delete_flag=1 WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function findContractorByTitle($query) {
    $sql = "SELECT * FROM rene_contractor WHERE UPPER(contractor_title) LIKE :query ORDER BY contractor_title";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $query = "%".$query."%";
        $stmt->bindParam("query", $query);
        $stmt->execute();
        $contractors = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($contractors);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getConnection() {
    $dbhost="184.168.154.89";
    $dbuser="newtopremodelers";
    $dbpass="Herve28031986";
    $dbname="newtopremodelers";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

?>