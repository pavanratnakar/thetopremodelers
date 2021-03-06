<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once(Config::$site_path.'/global/Class/login.class.php');
Login::start($_GET["logoff"]);
if (!Login::checkIfLoggedIn()) {
    return;
    exit;
}

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

$app->get('/contractorMapping', 'getContractorMapping');
$app->get('/contractorMapping/:id', 'getContractorMapping');
$app->post('/contractorMapping', 'addContractorMapping');
$app->delete('/contractorMapping/:id', 'deleteContractorMapping');

$app->get('/sections', 'getSections');

$app->get('/article', 'getArticle');
$app->get('/article/:id', 'getArticle');
$app->get('/articles', 'getArticles');

$app->post('/articleMapping', 'addArticleMapping');
$app->delete('/articleMapping/:id', 'deleteArticleMapping');

$app->get('/metas', 'getMetas');
$app->get('/metas/:id', 'getMeta');
$app->post('/metas', 'addMeta');
$app->put('/metas/:id', 'updateMeta');
$app->delete('/metas/:id', 'deleteMeta');

$app->get('/categories', 'getCategories');

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
    $sql = "INSERT INTO rene_contractor (contractor_title, contractor_description, contractor_phone, contractor_address, contractor_name, contractor_additional_info, delete_flag) VALUES (:contractor_title, :contractor_description, :contractor_phone, :contractor_address, :contractor_name, :contractor_additional_info, :delete_flag)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("contractor_title", $contractor->contractor_title);
        $stmt->bindParam("contractor_description", $contractor->contractor_description);
        $stmt->bindParam("contractor_phone", $contractor->contractor_phone);
        $stmt->bindParam("contractor_address", $contractor->contractor_address);
        $stmt->bindParam("contractor_name", $contractor->contractor_name);
        $stmt->bindParam("contractor_additional_info", $contractor->contractor_additional_info);
        $stmt->bindParam("delete_flag", $contractor->delete_flag);
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
    $sql = "UPDATE rene_contractor SET contractor_title=:contractor_title, contractor_description=:contractor_description, contractor_phone=:contractor_phone, contractor_address=:contractor_address, contractor_name=:contractor_name, contractor_additional_info=:contractor_additional_info, contractor_distance=:contractor_distance, delete_flag=:delete_flag WHERE contractor_id=:contractor_id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("contractor_title", $contractor->contractor_title);
        $stmt->bindParam("contractor_description", $contractor->contractor_description);
        $stmt->bindParam("contractor_phone", $contractor->contractor_phone);
        $stmt->bindParam("contractor_address", $contractor->contractor_address);
        $stmt->bindParam("contractor_name", $contractor->contractor_name);
        $stmt->bindParam("contractor_additional_info", $contractor->contractor_additional_info);
        $stmt->bindParam("contractor_distance", $contractor->contractor_distance);
        $stmt->bindParam("delete_flag", $contractor->delete_flag);
        $stmt->bindParam("contractor_id", $id);
        $stmt->execute();
        $db = null;
        echo json_encode($contractor);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function deleteContractor($id) {
    // $sql = "DELETE FROM rene_contractor WHERE contractor_id=:id";
    // try {
    //     $db = getConnection();
    //     $stmt = $db->prepare($sql);
    //     $stmt->bindParam("id", $id);
    //     $stmt->execute();
    //     $db = null;
    // } catch(PDOException $e) {
    //     echo '{"error":{"text":'. $e->getMessage() .'}}';
    // }
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
    $sql = "SELECT a.contractorMapping_id, e.contractor_title as contractor_title, d.category_title as category_title, c.section_title as section_title, b.place_title as place_title, a.active
            FROM
            rene_contractor_mapping a
            LEFT JOIN
            rene_place b ON b.place_id=a.place_id
            LEFT JOIN
            rene_section c ON c.section_id=a.section_id
            LEFT JOIN
            rene_category d ON c.category_id=d.category_id
            LEFT JOIN
            rene_contractor e ON a.contractor_id=e.contractor_id
            WHERE
            a.contractor_id=:id
            AND a.delete_flag=FALSE
            AND b.delete_flag=FALSE
            AND c.delete_flag=FALSE
            AND d.delete_flag=FALSE
            AND e.delete_flag=FALSE
            AND a.active=TRUE
            ORDER BY place_title, category_title, section_title";
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

function deleteContractorReview($id) {
    $sql = "DELETE FROM rene_contractor_rating WHERE contractorRating_id=:id";
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

function getContractorMapping($id) {
    $sql = "SELECT a.contractorMapping_id, a.contractor_id, a.active, a.delete_flag, e.contractor_title as contractor_title, d.category_title as category_title, c.section_title as section_title, b.place_title as place_title, a.active, e.contractor_distance as contractor_distance
            FROM
            rene_contractor_mapping a
            LEFT JOIN
            rene_place b ON b.place_id=a.place_id
            LEFT JOIN
            rene_section c ON c.section_id=a.section_id
            LEFT JOIN
            rene_category d ON c.category_id=d.category_id
            LEFT JOIN
            rene_contractor e ON e.contractor_id=a.contractor_id
            WHERE
            a.delete_flag=FALSE
            AND b.delete_flag=FALSE
            AND c.delete_flag=FALSE
            AND d.delete_flag=FALSE
            AND e.delete_flag=FALSE
            AND a.active=TRUE
            AND a.contractorMapping_id = :id
            ORDER BY category_title";
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
    $sql = "INSERT INTO rene_contractor_mapping (contractor_id, section_id, place_id, active, delete_flag) VALUES (:contractor_id, :section_id, :place_id, :active, :delete_flag)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("contractor_id", $contractorMapping->contractor_id);
        $stmt->bindParam("section_id", $contractorMapping->section_id);
        $stmt->bindParam("place_id", $contractorMapping->place_id);
        $stmt->bindParam("active", $contractorMapping->active);
        $stmt->bindParam("delete_flag", $contractorMapping->delete_flag);
        $stmt->execute();
        $contractorMapping->id = $db->lastInsertId();
        $db = null;
        echo json_encode($contractorMapping);
    } catch(PDOException $e) {
        error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function deleteContractorMapping($id) {
    $sql = "DELETE FROM rene_contractor_mapping WHERE contractorMapping_id=:id";
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

function getSections() {
    $sql = "select a.section_id, a.section_title, a.section_name, a.category_id, b.category_title
            FROM
            rene_section a
            LEFT JOIN
            rene_category b ON b.category_id=a.category_id
            ORDER BY a.category_id";
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

function getArticle($id) {
    $sql = "SELECT * FROM rene_article WHERE article_id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $article = $stmt->fetchObject();
        $db = null;
        echo json_encode($article);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getMappingForArticle ($id) {
    $sql = "SELECT a.id, c.category_id, b.article_id
            FROM
            rene_article_mapping a
            LEFT JOIN
            rene_article b ON b.article_id=a.article_id
            LEFT JOIN
            rene_category c ON c.category_id=a.category_id
            WHERE
            b.article_id=:id
            AND c.delete_flag=FALSE";
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

function getArticles() {
    $sql = "select a.article_id, a.title, a.name, a.category
            FROM
            rene_article a
            ORDER BY a.category, a.title";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $article = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($article as $key => $value) {
            $value->mappings = getMappingForArticle($value->article_id);
        }
        $db = null;
        echo json_encode($article);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addArticleMapping() {
    error_log('addArticleMapping\n', 3, '/var/tmp/php.log');
    $request = Slim::getInstance()->request();
    $articleMapping = json_decode($request->getBody());
    $sql = "INSERT INTO rene_article_mapping (article_id, category_id) VALUES (:article_id, :category_id)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("article_id", $articleMapping->article_id);
        $stmt->bindParam("category_id", $articleMapping->category_id);
        $stmt->execute();
        $articleMapping->id = $db->lastInsertId();
        $db = null;
        echo json_encode($articleMapping);
    } catch(PDOException $e) {
        error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getMetas() {
    $sql = "select * FROM rene_meta ORDER BY matching";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $metas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($metas);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getMeta($id) {
    $sql = "SELECT * FROM rene_meta WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $meta = $stmt->fetchObject();
        $db = null;
        echo json_encode($meta);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addMeta() {
    error_log('addMeta\n', 3, '/var/tmp/php.log');
    $request = Slim::getInstance()->request();
    $meta = json_decode($request->getBody());
    $sql = "INSERT INTO rene_meta (title, description, keywords, matching) VALUES (:title, :description, :keywords, :matching)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("title", $meta->title);
        $stmt->bindParam("description", $meta->description);
        $stmt->bindParam("keywords", $meta->keywords);
        $stmt->bindParam("matching", $meta->matching);
        $stmt->execute();
        $meta->id = $db->lastInsertId();
        $db = null;
        echo json_encode($meta);
    } catch(PDOException $e) {
        error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateMeta($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $meta = json_decode($body);
    $sql = "UPDATE rene_meta SET title=:title, description=:description, keywords=:keywords, matching=:matching WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("title", $meta->title);
        $stmt->bindParam("description", $meta->description);
        $stmt->bindParam("keywords", $meta->keywords);
        $stmt->bindParam("matching", $meta->matching);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo json_encode($meta);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function deleteMeta($id) {
    $sql = "DELETE FROM rene_meta WHERE id=:id";
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

function getCategories() {
    $sql = "select a.category_id, a.category_title, a.category_date, a.delete_flag, a.category_name, a.category_value, a.category_order, a.position, a.active
            FROM
            rene_category a
            ORDER BY a.category_title";
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

function deleteArticleMapping ($id) {
    $sql = "DELETE FROM rene_article_mapping WHERE id=:id";
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