<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/reviews', 'getReviews');
$app->get('/reviews/:id',   'getReview');
$app->get('/reviews/search/:query', 'findReviewByProject');
$app->post('/reviews', 'addReview');
$app->put('/reviews/:id', 'updateReview');
$app->delete('/reviews/:id',    'deleteReview');

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