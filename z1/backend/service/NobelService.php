<?php
require_once "php/dbConnect.php";
class NobelService
{
    private $pdo;

    public function __construct()
    {
        $pdo = getPDODbConnection();
        $this->pdo = $pdo;
    }

    public function getPrizes(int $limit, int $offset, $year, $category)
    {
        if ($limit < 1) {
            $limit = null;
        }
        if ($offset < 0) {
            $offset = null;
        }

        $query =
            "SELECT 
                prz.id,
                prz.year, 
                rcv.id AS receiver_id, 
                rcv.name AS receiver_name, 
                rcv.surname AS receiver_surname, 
                rcv.organization AS receiver_organization, 
                ctg.name AS category_name
                FROM 
                prizes prz
                JOIN 
                receivers rcv ON prz.receiver_id = rcv.id
                JOIN 
                categories ctg ON prz.category_id = ctg.id
                ";

        if (isset($year)) {
            $query .= " WHERE prz.year = :year";
        }
        if (isset($category)) {
            $query .= " WHERE ctg.name = :category";
        }

        $query .= " ORDER BY prz.id ASC";

        if (isset($limit) and isset($offset)) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        try {
            $stmt = $this->pdo->prepare($query);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }

        if (isset($year)) {
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        }
        if (isset($category)) {
            $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        }

        if (isset($limit) and isset($offset)) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();

        return json_encode(['data' => $stmt->fetchAll()]);
    }
    public function getPrizesSortedByYear(int $limit, int $offset, string $sorting = "ASC", $year, $category)
    {
        if ($limit < 1) {
            $limit = null;
        }
        if ($offset < 0) {
            $offset = null;
        }

        $query =
            "SELECT 
                prz.id,
                prz.year, 
                rcv.id AS receiver_id, 
                rcv.name AS receiver_name, 
                rcv.surname AS receiver_surname,
                rcv.organization AS receiver_organization, 
                ctg.name AS category_name
                FROM 
                prizes prz
                JOIN 
                receivers rcv ON prz.receiver_id = rcv.id
                JOIN 
                categories ctg ON prz.category_id = ctg.id
                ";

        if (isset($year)) {
            $query .= " WHERE prz.year = :year";
        }
        if (isset($category)) {
            $query .= " WHERE ctg.name = :category";
        }

        $query .= " ORDER BY prz.year $sorting";

        if (isset($limit) and isset($offset)) {
            $query .= " LIMIT :limit OFFSET :offset";
        }
        try {
            $stmt = $this->pdo->prepare($query);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }

        if (isset($year)) {
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        }
        if (isset($category)) {
            $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        }

        if (isset($limit) and isset($offset)) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();

        return json_encode(['data' => $stmt->fetchAll()]);
    }
    public function getPrizesSortedByName(int $limit, int $offset, string $sorting = "ASC", $year, $category)
    {
        if ($limit < 1) {
            $limit = null;
        }
        if ($offset < 0) {
            $offset = null;
        }

        $query =
            "SELECT 
                prz.id,
                prz.year, 
                rcv.id AS receiver_id, 
                rcv.name AS receiver_name, 
                rcv.surname AS receiver_surname,
                rcv.organization AS receiver_organization, 
                ctg.name AS category_name
                FROM 
                prizes prz
                JOIN 
                receivers rcv ON prz.receiver_id = rcv.id
                JOIN 
                categories ctg ON prz.category_id = ctg.id
                ";

        if (isset($year)) {
            $query .= " WHERE prz.year = :year";
        }
        if (isset($category)) {
            $query .= " WHERE ctg.name = :category";
        }

        $query .= " ORDER BY rcv.name $sorting";

        if (isset($limit) and isset($offset)) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        try {
            $stmt = $this->pdo->prepare($query);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }

        if (isset($year)) {
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        }
        if (isset($category)) {
            $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        }

        if (isset($limit) and isset($offset)) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();

        return json_encode(['data' => $stmt->fetchAll()]);
    }
    public function getPrizesSortedByCategory(int $limit, int $offset, string $sorting = "ASC", $year, $category)
    {
        if ($limit < 1) {
            $limit = null;
        }
        if ($offset < 0) {
            $offset = null;
        }

        $query =
            "SELECT 
                prz.id,
                prz.year, 
                rcv.id AS receiver_id, 
                rcv.name AS receiver_name, 
                rcv.surname AS receiver_surname,
                rcv.organization AS receiver_organization, 
                ctg.name AS category_name
                FROM 
                prizes prz
                JOIN 
                receivers rcv ON prz.receiver_id = rcv.id
                JOIN 
                categories ctg ON prz.category_id = ctg.id
                ";

        if (isset($year)) {
            $query .= " WHERE prz.year = :year";
        }
        if (isset($category)) {
            $query .= " WHERE ctg.name = :category";
        }

        $query .= " ORDER BY ctg.name $sorting";

        if (isset($limit) and isset($offset)) {
            $query .= " LIMIT :limit OFFSET :offset";
        }
        try {
            $stmt = $this->pdo->prepare($query);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }

        if (isset($year)) {
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        }
        if (isset($category)) {
            $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        }

        if (isset($limit) and isset($offset)) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();

        return json_encode(['data' => $stmt->fetchAll()]);
    }
    public function getPrizeById(int $id)
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT 
                prz.year, 
                prz.contribution_sk,
                prz.contribution_en,
                rcv.id AS receiver_id, 
                rcv.name AS receiver_name, 
                rcv.surname AS receiver_surname,
                rcv.organization AS receiver_organization, 
                ctg.name AS category_name,
                pdt.language_sk AS language_sk,
                pdt.language_en AS language_en,
                pdt.genre_sk AS genre_sk,
                pdt.genre_sk AS genre_en
                FROM 
                prizes prz
                JOIN 
                receivers rcv ON prz.receiver_id = rcv.id
                JOIN 
                categories ctg ON prz.category_id = ctg.id
                JOIN
                prize_details pdt ON prz.detail_id = pdt.id
                WHERE prz.id = :id
                "
            );
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return json_encode(['data' => $stmt->fetch()]);
    }
    public function getCategories()
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT 
                ctg.name AS category_name
                FROM 
                categories ctg
                "
            );
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }

        $stmt->execute();

        return json_encode(['data' => $stmt->fetchAll()]);
    }

    public function getReceiverById(int $recId)
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT 
                receivers.*,
                prizes.year,
                prizes.contribution_sk,
                prizes.contribution_en,
                categories.name AS category_name,
                countries.name AS country_name,
                prize_details.language_sk,
                prize_details.language_en,
                prize_details.genre_sk,
                prize_details.genre_en
            FROM 
                receivers
            JOIN 
                prizes ON receivers.id = prizes.receiver_id
            JOIN 
                categories ON prizes.category_id = categories.id
            JOIN 
                countries ON receivers.country_id = countries.id
            JOIN 
                prize_details ON prizes.detail_id = prize_details.id
            WHERE receivers.id = :recId
            "
            );

        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
        $stmt->bindParam(':recId', $recId, PDO::PARAM_INT);
        $stmt->execute();
        return json_encode(['data' => $stmt->fetch()]);
    }

    public function deleteReceiverById(int $prizeId)
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "SELECT detail_id, receiver_id, id  FROM prizes WHERE id = :prizeId";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':prizeId', $prizeId, PDO::PARAM_INT);
            $stmt->execute();
            $idToDelete = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($idToDelete['detail_id']);
            echo json_encode($idToDelete['receiver_id']);
            echo json_encode($idToDelete['id']);

            $stmt = $this->pdo->prepare("DELETE FROM prizes WHERE id = :id");
            $stmt->execute(['id' => $idToDelete['id']]);

            $stmt = $this->pdo->prepare("DELETE FROM prize_details WHERE id = :id");
            $stmt->execute(['id' => $idToDelete['detail_id']]);

            $stmt = $this->pdo->prepare("SELECT receiver_id from prizes WHERE receiver_id = :id");
            $stmt->execute(['id' => $idToDelete['receiver_id']]);
            $receiverCount = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (sizeof($receiverCount) < 1) {
                $stmt = $this->pdo->prepare("DELETE FROM receivers WHERE id = :id");
                $stmt->execute(['id' => $idToDelete['receiver_id']]);
            }


            $this->pdo->commit();
            return json_encode(['success' => 'Success delete operation']);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return json_encode(['error' => "Error during delete operation: " . $e->getMessage()]);
        }

    }
    public function updatePrize($receiverData)
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("SELECT detail_id FROM prizes WHERE receiver_id = :recid AND year = :year LIMIT 1");
            $stmt->execute([
                ':recid' => $receiverData->name,
                ':year' => $receiverData->year
            ]);
            $prizeId = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $this->pdo->prepare("SELECT detail_id FROM prizes WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $prizeId]);
            $detailsId = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $this->pdo->prepare("SELECT id FROM countries WHERE name = :name LIMIT 1");
            $stmt->execute([':name' => $receiverData->countryName]);
            $country = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($country) {
                $countryId = $country['id'];
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO countries (name) VALUES (:name)");
                $stmt->execute([':name' => $receiverData->countryName]);
                $countryId = $this->pdo->lastInsertId();
            }

            $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE name = :name LIMIT 1");
            $stmt->execute([':name' => $receiverData->categoryName]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($category) {
                $categoryId = $category['id'];
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
                $stmt->execute([':name' => $receiverData->categoryName]);
                $categoryId = $this->pdo->lastInsertId();
            }

            $stmt = $this->pdo->prepare(
                "UPDATE receivers SET
                    name = :name, 
                    surname = :surname, 
                    organization = :organization, 
                    sex = :sex,
                    birth = :birth,
                    death = :death,                    
                    country_id = :country_id
                WHERE id = :id"
            );
            $stmt->execute([
                ':name' => $receiverData->name,
                ':surname' => $receiverData->surname,
                ':organization' => $receiverData->organization,
                ':sex' => $receiverData->sex,
                ':birth' => $receiverData->birth,
                ':death' => $receiverData->death,
                ':country_id' => $countryId,
                ':id' => $receiverData->id
            ]);

            $stmt = $this->pdo->prepare(
                "UPDATE prize_details SET
                    language_sk = :languageSk, 
                    language_en = :languageEn, 
                    genre_sk = :genreSk, 
                    genre_en = :genreEn
                WHERE id = :id"
            );
            $stmt->execute([
                ':languageSk' => $receiverData->languageSk,
                ':languageEn' => $receiverData->languageEn,
                ':genreSk' => $receiverData->genreSk,
                ':genreEn' => $receiverData->genreEn,
                ':id' => $detailsId['details_id']
            ]);


            $stmt = $this->pdo->prepare(
                "UPDATE prizes SET
                    year = :year,
                    receiver_id = :receiverId,
                    category_id = :categoryId,
                    detail_id = :detailId,
                    contribution_sk = :contributionSk,
                    contribution_en = :contributionEn
                WHERE id = :id"
            );
            $stmt->execute([
                ':year' => $receiverData->year,
                ':receiverId' => $receiverData->id,
                ':categoryId' => $categoryId,
                ':detailId' => $detailsId,
                ':contributionSk' => $receiverData->contributionSk,
                ':contributionEn' => $receiverData->contributionEn,
                ':id' => $prizeId['id']
            ]);

            $this->pdo->commit();
            header('updated: 1');

            return $this->getReceiverById($receiverData->id);
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            header('updated: 1');
            throw $e;
            // return json_encode(['error' => "Error during insert operation: " . $e->getMessage()]);
        }
    }
    public function createPrize($receiverData)
    {
        try {
            $newRec = false;

            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("SELECT id FROM countries WHERE name = :name LIMIT 1");
            $stmt->execute([':name' => $receiverData->countryName]);
            $country = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($country) {
                $countryId = $country['id'];
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO countries (name) VALUES (:name)");
                $stmt->execute([':name' => $receiverData->countryName]);
                $countryId = $this->pdo->lastInsertId();
            }

            $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE name = :name LIMIT 1");
            $stmt->execute([':name' => $receiverData->categoryName]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($category) {
                $categoryId = $category['id'];
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
                $stmt->execute([':name' => $receiverData->categoryName]);
                $categoryId = $this->pdo->lastInsertId();
            }

            $stmt = $this->pdo->prepare("SELECT id FROM receivers WHERE name = :name AND surname = :surname LIMIT 1");
            $stmt->execute([
                ':name' => $receiverData->name,
                ':surname' => $receiverData->surname
            ]);
            $receiver = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($receiver) {
                $receiverId = $receiver['id'];
            } else {
                $stmt = $this->pdo->prepare("SELECT id FROM receivers WHERE organization = :org LIMIT 1");
                $stmt->execute([
                    ':org' => $receiverData->organization
                ]);
                $receiverOrg = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($receiverOrg) {
                    $receiverId = $receiverOrg['id'];
                } else {
                    $newRec = true;
                    $stmt = $this->pdo->prepare(
                        "INSERT INTO receivers 
                        (
                            name, 
                            surname, 
                            organization, 
                            sex,
                            birth,
                            death,                    
                            country_id
                        ) 
                        VALUES (
                            :name, 
                            :surname, 
                            :organization, 
                            :sex,
                            :birth,
                            :death,                    
                            :country_id
                           )"
                    );
                    $stmt->execute([
                        ':name' => $receiverData->name,
                        ':surname' => $receiverData->surname,
                        ':organization' => $receiverData->organization,
                        ':sex' => $receiverData->sex,
                        ':birth' => $receiverData->birth,
                        ':death' => $receiverData->death,
                        ':country_id' => $countryId
                    ]);
                    $receiverId = $this->pdo->lastInsertId();
                }
            }



            $stmt = $this->pdo->prepare(
                "INSERT INTO prize_details 
                (
                    language_sk,
                    language_en,
                    genre_sk,
                    genre_en
                ) 
                VALUES (
                    :languageSk, 
                    :languageEn, 
                    :genreSk, 
                    :genreEN
                   )"
            );
            $stmt->execute([
                ':languageSk' => $receiverData->languageSk,
                ':languageEn' => $receiverData->languageEn,
                ':genreSk' => $receiverData->genreSk,
                ':genreEN' => $receiverData->genreEn
            ]);
            $detailsId = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare(
                "INSERT INTO prizes 
                (
                    year,
                    receiver_id,
                    category_id,
                    detail_id,
                    contribution_sk,
                    contribution_en
                ) 
                VALUES (
                    :year,
                    :receiverId,
                    :categoryId,
                    :detailId,
                    :contributionSk,
                    :contributionEn
                   )"
            );
            $stmt->execute([
                ':year' => $receiverData->year,
                ':receiverId' => $receiverId,
                ':categoryId' => $categoryId,
                ':detailId' => $detailsId,
                ':contributionSk' => $receiverData->contributionSk,
                ':contributionEn' => $receiverData->contributionEn
            ]);
            $this->pdo->commit();
            $newRec ? header('new-rec: 1') : header('new-rec: 0');

            return $this->getReceiverById($receiverId);
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
            // return json_encode(['error' => "Error during insert operation: " . $e->getMessage()]);
        }
    }
}

class ReceiverDetails
{
    public $id;
    public $name;
    public $surname;
    public $organization;
    public $sex;

    public $year;
    public $contributionSk;
    public $contributionEn;

    public $categoryName;

    public $countryName;

    public $languageSk;
    public $languageEn;
    public $genreSk;
    public $genreEn;
}
?>