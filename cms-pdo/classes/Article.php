<?php

include_once 'config/config.php';

class Article
{

    private $conn;
    private $table = 'articles';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function get_all()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getExcerpt($content, $length = 100)
    {
        if (strlen($content) > $length) {
            return substr($content, 0, $length) . "...";
        }
        return $content;
    }

    public function getArticleById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $article = $stmt->fetch(PDO::FETCH_OBJ);

        if ($article) {
            if($article->user_id == $_SESSION['user_id']) {
                return $article;
            } else {
              redirect ("admin.php");
            }
        } else {
            return false;
        }
    }
    public function deleteWithImage($id)
    {

        $article = $this->getArticleById($id);

        if ($article) {

            if ($article->user_id == $_SESSION['user_id']) {


                if (!empty($article->image) && file_exists($article->image)) {
                    if (!unlink($article->image)) {
                        return false;
                    }
                }


                $query = "DELETE FROM " . $this->table . " WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                return $stmt->execute();
            } else {
                redirect("admin.php");
            }
        }
        return false;
    }

    public function getArticleWithOwnerById($id)
    {
        $query = "SELECT articles.id,
                        articles.title,
                        articles.image,
                        articles.content,
                        articles.created_at,
                        users.username AS author,
                        users.email AS author_email
                        FROM " . $this->table . "   
                        JOIN users ON articles.user_id = users.id 
                        WHERE articles.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $article = $stmt->fetch(PDO::FETCH_OBJ);

        if ($article) {
            return $article;
        } else {
            return false;
        }
    }

    public function getArticlesByUser($userId)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function formatCreatedAt($date)
    {
        return date('F j, Y', strtotime($date));
    }

    public function create($title, $content, $author_id, $created_at, $imagePath)
    {
        $query = "INSERT INTO " . $this->table . " (title, content, user_id, created_at, image)
        VALUES (:title, :content, :user_id, :created_at, :image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $author_id);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':image', $imagePath);

        return $stmt->execute();
    }

    public function uploadImage($file)
    {
        $targetDir = 'uploads/';

        if(!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
        }

        if(isset($file) && $file['error'] === 0){

        $targetFile = $targetDir . basename($file['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg','jpeg','png','gif','webp'];

            if(in_array($imageFileType, $allowedTypes)) {
            $uniqueFileName = uniqid() . "-" . time() . "." . $imageFileType;
            $targetFile = $targetFile . $uniqueFileName;
            
                if(move_uploaded_file($file['tmp_name'], $targetFile)) {
                return $targetFile;
                } else {
                return "THER WAS AN ERROr";
                }

            } else {
            return  "ONLY JPG, JPEG, PNG, and GID allowed";
            }
        }
        return '';
    }

    public function update($id, $title, $content, $author_id, $created_at, $imagePath = null)
    {
        $query = "UPDATE " . $this->table . "
            SET title = :title, content = :content, user_id = :user_id, created_at = :created_at";
        if($imagePath){
            $query .= ", image = :image";
        }

            $query .= " WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':user_id', $author_id);
            $stmt->bindParam(':created_at', $created_at);

        if($imagePath){
             $stmt->bindParam(':image', $imagePath, PDO::PARAM_STR);
        }
        return $stmt->execute();

    }

}