<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":


        if (isset($_GET['supplier_id'])) {
            $supplier_id_spe = $_GET['supplier_id'];
            $sql = "SELECT * FROM supplier WHERE supplier_id = :supplier_id";
        }


        if (!isset($_GET['supplier_id'])) {
            $sql = "SELECT * FROM supplier";
        }


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            if (isset($supplier_id_spe)) {
                $stmt->bindParam(':supplier_id', $supplier_id_spe);
            }

            $stmt->execute();
            $supplier = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($supplier);
        }



        break;





    case "POST":
        $suppl = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO supplier (supplier_name, product_supplied, address, phone) VALUES (:supplier_name, :product_supplied, :address, :phone)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':supplier_name', $suppl->supplier_name);
        $stmt->bindParam(':product_supplied', $suppl->product_supplied);
        $stmt->bindParam(':address', $suppl->address);

        $stmt->bindParam(':phone', $suppl->phone);


        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "supplier notification sent successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "supplier notification sent failed"
            ];
        }

        echo json_encode($response);
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
