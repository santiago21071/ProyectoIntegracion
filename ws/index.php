<?php
include 'conexion.php';

$pdo = new Conexion();
$URIParts = explode('?', $_SERVER['REQUEST_URI']);
$URI = explode('/', $URIParts[0]);
// Shift the array, to move it past the 'root'
@array_shift($URI);
// Now, get rid of any pesky empty end slashes
while (@count($URI) > 0 && !end($URI)) {
    @array_pop($URI);
}

//print_r($URI[1]);
function getRowByID($id)
{
    $sql = $GLOBALS["pdo"]->prepare("SELECT * FROM editorial WHERE id=:id_editoral");
    $sql->bindValue(':id_editoral', $id);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);

    return json_encode($sql->fetchAll());
}


switch ($URI[1]) {
    case 'libro':
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
        break;

    case 'editorial':
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET['id_editoral'])) {
                $row = getRowByID($_GET['id_editoral']);
                header("HTTP/1.1 200 OK");
                echo $row;
                exit;
            } else {

                $sql = $pdo->prepare("SELECT * FROM editorial");
                $sql->execute();
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                header("HTTP/1.1 200 OK");
                echo json_encode($sql->fetchAll());
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $sql = "INSERT INTO editorial (nombre, descripcion) VALUES (:nombre, :descripcion)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nombre', $_POST['nombre']);
            $stmt->bindValue(':descripcion', $_POST['descripcion']);
            $stmt->execute();
            $idPost = $pdo->lastInsertId();
            if ($idPost) {
                header("HTTP/1.1 200 OK");
                echo json_encode($idPost);
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            parse_str(file_get_contents("php://input"), $put_vars);
            $sql = "UPDATE editorial SET nombre=:nombre, descripcion=:descripcion WHERE id=:id_editorial";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nombre', $put_vars['nombre']);
            $stmt->bindValue(':descripcion', $put_vars['descripcion']);
            $stmt->bindValue(':id_editorial', $put_vars['id_editorial']);
            $stmt->execute();
            $row = getRowByID($put_vars['id_editorial']);
            header("HTTP/1.1 200 OK");
            echo $row;
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            parse_str(file_get_contents("php://input"), $delete_vars);
            $row = getRowByID($delete_vars['id_editorial']);
            $sql = "DELETE FROM editorial WHERE id=:id_editorial";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id_editorial', $delete_vars['id_editorial']);
            $stmt->execute();
            header("HTTP/1.1 200 OK");
            echo $row;
            exit;
        }
        break;
    default:
        header("HTTP/1.1 404 not found");
        break;
}
