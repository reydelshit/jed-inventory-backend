<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":


        if (isset($_GET['racks'])) {
            $racks_spe = $_GET['racks'];
            $sql = "SELECT * FROM product INNER JOIN supplier ON supplier.supplier_id = product.supplier_id WHERE racks = :racks";
        }


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            if (isset($racks_spe)) {
                $stmt->bindParam(':racks', $racks_spe);
            }

            $stmt->execute();
            $product = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($product);
        }



        break;



    case "PUT":
        $supplier = json_decode(file_get_contents('php://input'));
        $sql = "UPDATE medication SET status= :status
                    WHERE medication_id = :medication_id";
        $stmt = $conn->prepare($sql);
        $updated_at = date('Y-m-d');
        $stmt->bindParam(':medication_id', $supplier->medication_id);
        $stmt->bindParam(':status', $supplier->status);


        if ($stmt->execute()) {

            $response = [
                "status" => "success",
                "message" => "medication updated successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "medication update failed"
            ];
        }

        echo json_encode($response);
        break;
}
