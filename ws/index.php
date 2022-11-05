<?php
include 'conexion.php';

$pdo = new Conexion();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_libro'])) {
        $sql = $pdo->prepare("SELECT * FROM libro WHERE id_libro=:id_libro");
        $sql->bindValue(':id_libro', $_GET['id_libro']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
        exit;
    } else {

        $sql = $pdo->prepare("SELECT * FROM libro");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "INSERT INTO libro (nombre, disponibilidad, registro, editorial) VALUES (:nombre, :disponibilidad, :registro, :editorial)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':nombre', $_POST['nombre']);
    $stmt->bindValue(':disponibilidad', $_POST['disponibilidad']);
    $stmt->bindValue(':registro', $_POST['registro']);
    $stmt->bindValue(':editorial', $_POST['editorial']);
    $stmt->execute();
    $idPost = $pdo->lastInsertId();
    if ($idPost) {
        header("HTTP/1.1 200 OK");
        echo json_encode($idPost);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $sql = "UPDATE libro SET nombre=:nombre, disponibilidad=:disponibilidad, registro=:registro, editorial=:editorial WHERE id_libro=:id_libro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':nombre', $_GET['nombre']);
    $stmt->bindValue(':disponibilidad', $_GET['disponibilidad']);
    $stmt->bindValue(':registro', $_GET['registro']);
    $stmt->bindValue(':editorial', $_GET['editorial']);
    $stmt->bindValue(':id_libro', $_GET['id_libro']);
    $stmt->execute();
    header("HTTP/1.1 200 OK");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $sql = "DELETE FROM libro WHERE id_libro=:id_libro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_libro', $_GET['id_libro']);
    $stmt->execute();
    header("HTTP/1.1 200 OK");
    exit;
}
header("HTTP/1.1 400 bad request");
