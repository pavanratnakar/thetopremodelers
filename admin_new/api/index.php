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

$app->get('/contractorReviews/:id', 'getContractorReviews');
$app->get('/contractorReview/:id', 'getContractorReview');
$app->post('/contractorReview', 'addContractorReview');
$app->put('/contractorReview/:id', 'updateContractorReview');
$app->delete('/contractorReview/:id', 'deleteContractorReview');

$app->get('/place/:id', 'getPlace');
$app->get('/places', 'getPlaces');

$app->get('/placeCategory/:id', 'getPlaceCategory');
$app->get('/placeCategories/:id', 'getPlaceCategories');

$app->get('/categorySection/:id', 'getCategorySection');
$app->get('/categorySections/:id', 'getCategorySections');

$app->get('/contractorMapping/:id', 'getContractorMapping');
$app->post('/contractorMapping', 'addContractorMapping');

$app->run();

function getReviews() {
    $sql = "select * FROM rene_review ORDER BY project";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $reviews = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
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
        $contractor->reviews = getReviewsForContractor($id);
        $contractor->mappings = getMappingForContractor($id);
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
    $sql = "UPDATE rene_contractor SET contractor_title=:contractor_title, contractor_description=:contractor_description, contractor_phone=:contractor_phone, contractor_address=:contractor_address, contractor_name=:contractor_name WHERE contractor_id=:contractor_id";
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
    $sql = "UPDATE rene_contractor SET delete_flag=1 WHERE contractor_id=:id";
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

function getReviewsForContractor($id) {
    $sql = "SELECT a.contractorRating_id, a.score, a.timestamp, a.person, a.place_id, a.project, a.review
            FROM
            rene_contractor_rating a
            LEFT JOIN
            rene_contractor b ON b.contractor_id=a.contractor_id
            WHERE
            a.delete_flag=FALSE
            AND b.delete_flag=FALSE
            AND b.contractor_id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $reviews;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getMappingForContractor($id) {
    $sql = "SELECT a.contractorMapping_id,g.contractor_title as contractor_title,d.category_title as category_title,f.section_title as section_title,e.place_title as place_title,a.active
            FROM
            rene_contractor_mapping a
            LEFT JOIN
            rene_categorysection_mapping b ON a.categorySection_id=b.categorySection_id
            LEFT JOIN
            rene_placecategory_mapping c ON b.placeCategory_id=c.placeCategory_id
            LEFT JOIN
            rene_category d ON c.category_id=d.category_id
            LEFT JOIN
            rene_place e ON e.place_id=c.place_id
            LEFT JOIN
            rene_section f ON f.section_id=b.section_id
            LEFT JOIN
            rene_contractor g ON g.contractor_id=a.contractor_id
            WHERE
            a.contractor_id=:id
            AND a.delete_flag=FALSE
            AND b.delete_flag=FALSE
            AND c.delete_flag=FALSE
            AND d.delete_flag=FALSE
            AND e.delete_flag=FALSE
            AND f.delete_flag=FALSE
            AND g.delete_flag=FALSE";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $mappings = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $mappings;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getContractorReviews($id) {
    $sql = "SELECT a.contractorRating_id, a.score, a.timestamp, a.person, a.place_id, a.project, a.review
            FROM
            rene_contractor_rating a
            LEFT JOIN
            rene_contractor b ON b.contractor_id=a.contractor_id
            WHERE
            a.delete_flag=FALSE
            AND b.delete_flag=FALSE
            AND b.contractor_id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $contractorReviews = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($contractorReviews);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getContractorReview($id) {
    $sql = "SELECT * FROM rene_contractor_rating WHERE contractorRating_id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $contractorReview = $stmt->fetchObject();
        $db = null;
        echo json_encode($contractorReview);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addContractorReview() {
    error_log('addContractorReview\n', 3, '/var/tmp/php.log');
    $request = Slim::getInstance()->request();
    $review = json_decode($request->getBody());
    $sql = "INSERT INTO rene_contractor_rating (score, review, contractor_id, timestamp, person, place_id, project) VALUES (:score, :review, :contractor_id, :timestamp, :person, :place_id, :project)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("score", $review->score);
        $stmt->bindParam("review", $review->review);
        $stmt->bindParam("contractor_id", $review->contractor_id);
        $stmt->bindParam("timestamp", $review->timestamp);
        $stmt->bindParam("person", $review->person);
        $stmt->bindParam("place_id", $review->place_id);
        $stmt->bindParam("project", $review->project);
        $stmt->execute();
        $review->id = $db->lastInsertId();
        $db = null;
        echo json_encode($review);
    } catch(PDOException $e) {
        error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateContractorReview($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $review = json_decode($body);
    $sql = "UPDATE rene_contractor_rating SET score=:score, review=:review, contractor_id=:contractor_id, timestamp=:timestamp, person=:person, place_id=:place_id, project=:project  WHERE contractorRating_id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("score", $review->score);
        $stmt->bindParam("review", $review->review);
        $stmt->bindParam("contractor_id", $review->contractor_id);
        $stmt->bindParam("timestamp", $review->timestamp);
        $stmt->bindParam("person", $review->person);
        $stmt->bindParam("place_id", $review->place_id);
        $stmt->bindParam("project", $review->project);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo json_encode($review);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getPlace($id) {
    $sql = "SELECT * FROM rene_place WHERE place_id=:id AND delete_flag=0";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $place = $stmt->fetchObject();
        $db = null;
        echo json_encode($place);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getPlaces() {
    $sql = "select * FROM rene_place WHERE active=1 AND under<>0 ORDER BY place_title";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $places = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($places);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getPlaceCategory($id){
    $sql = "SELECT a.placeCategory_id, a.active, a.category_id, a.place_id, a.delete_flag
            FROM
            rene_placecategory_mapping a
            WHERE
            a.delete_flag=FALSE
            AND a.placeCategory_id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $placeCategory = $stmt->fetchObject();
        $db = null;
        echo json_encode($placeCategory);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getPlaceCategories($id) {
    $sql = "SELECT a.placeCategory_id,c.category_title as category_title,a.active
            FROM
            rene_placecategory_mapping a
            LEFT JOIN
            rene_place b ON b.place_id=a.place_id
            LEFT JOIN
            rene_category c ON a.category_id=c.category_id
            WHERE
            a.delete_flag=FALSE
            AND b.delete_flag=FALSE
            AND c.delete_flag=FALSE
            AND b.place_id=:id
            ORDER BY category_title";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $placeCategories = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($placeCategories);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getCategorySection($id) {
    $sql = "SELECT a.categorySection_id, a.placeCategory_id, a.section_id, a.categorysection_order, a.active,a.meta_id
            FROM
            rene_categorysection_mapping a
            WHERE
            a.delete_flag=FALSE
            AND a.categorySection_id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $categorySection = $stmt->fetchObject();
        $db = null;
        echo json_encode($categorySection);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getCategorySections($id) {
    $sql = "SELECT a.categorySection_id, a.placeCategory_id, a.section_id, e.place_title as place_title, d.category_title as category_title, c.section_title as section_title, a.categorysection_order, a.active,a.meta_id
            FROM
            rene_categorysection_mapping a
            LEFT JOIN
            rene_placecategory_mapping b ON b.placeCategory_id=a.placeCategory_id
            LEFT JOIN
            rene_section c ON c.section_id=a.section_id
            LEFT JOIN
            rene_category d ON b.category_id=d.category_id
            LEFT JOIN
            rene_place e ON e.place_id=b.place_id
            WHERE
            a.delete_flag=FALSE
            ANd b.delete_flag=FALSE
            AND c.delete_flag=FALSE
            AND d.delete_flag=FALSE
            AND e.delete_flag=FALSE
            AND b.placeCategory_id=:id
            ORDER BY section_title";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $categorySections = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($categorySections);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getContractorMapping($id) {
    $sql = "SELECT a.contractorMapping_id, a.contractor_id, a.categorySection_id, a.active, a.delete_flag, g.contractor_title as contractor_title, d.category_title as category_title, f.section_title as section_title,e.place_title as place_title, a.active
            FROM
            rene_contractor_mapping a
            LEFT JOIN
            rene_categorysection_mapping b ON a.categorySection_id=b.categorySection_id
            LEFT JOIN
            rene_placecategory_mapping c ON b.placeCategory_id=c.placeCategory_id
            LEFT JOIN
            rene_category d ON c.category_id=d.category_id
            LEFT JOIN
            rene_place e ON e.place_id=c.place_id
            LEFT JOIN
            rene_section f ON f.section_id=b.section_id
            LEFT JOIN
            rene_contractor g ON g.contractor_id=a.contractor_id
            WHERE
            a.delete_flag=FALSE
            AND b.delete_flag=FALSE
            AND c.delete_flag=FALSE
            AND d.delete_flag=FALSE
            AND e.delete_flag=FALSE
            AND f.delete_flag=FALSE
            AND g.delete_flag=FALSE
            AND a.contractorMapping_id = :id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $contractorMapping = $stmt->fetchObject();
        $db = null;
        echo json_encode($contractorMapping);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addContractorMapping(){
    error_log('addContractorMapping\n', 3, '/var/tmp/php.log');
    $request = Slim::getInstance()->request();
    $contractorMapping = json_decode($request->getBody());
    $sql = "INSERT INTO rene_contractor_mapping (contractor_id, categorySection_id, active, delete_flag) VALUES (:contractor_id, :categorySection_id, :active, :delete_flag)";
    echo $sql;
    // try {
    //     $db = getConnection();
    //     $stmt = $db->prepare($sql);
    //     $stmt->bindParam("contractor_title", $contractor->contractor_title);
    //     $stmt->bindParam("contractor_description", $review->contractor_description);
    //     $stmt->bindParam("contractor_phone", $review->contractor_phone);
    //     $stmt->bindParam("contractor_address", $review->contractor_address);
    //     $stmt->bindParam("contractor_name", $review->contractor_name);
    //     $stmt->execute();
    //     $contractor->id = $db->lastInsertId();
    //     $db = null;
    //     echo json_encode($contractor);
    // } catch(PDOException $e) {
    //     error_log($e->getMessage(), 3, '/var/tmp/php.log');
    //     echo '{"error":{"text":'. $e->getMessage() .'}}';
    // }
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