<?php


include 'DBconnect.php';
$objDB = new DbConnect();
$conn = $objDB->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":


        if (isset($_GET['product_id'])) {
            $product_id_spe = $_GET['product_id'];
            $sql = "SELECT * FROM product WHERE product_id = :product_id";
        }


        if (!isset($_GET['product_id'])) {
            $sql = "SELECT * FROM product";
        }


        if (isset($sql)) {
            $stmt = $conn->prepare($sql);

            if (isset($product_id_spe)) {
                $stmt->bindParam(':product_id', $product_id_spe);
            }

            $stmt->execute();
            $product = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($product);
        }



        break;





    case "POST":
        $product = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO product (supplier_id, product_image, product_name, expiration_date, description, stocks) VALUES (:supplier_id, :product_image, :product_name, :expiration_date, :description, :stocks)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':supplier_id', $product->supplier_id);
        $stmt->bindParam(':product_image', $product->product_image);
        $stmt->bindParam(':product_name', $product->product_name);

        $stmt->bindParam(':expiration_date', $product->expiration_date);
        $stmt->bindParam(':description', $product->description);
        $stmt->bindParam(':stocks', $product->stocks);



        if ($stmt->execute()) {
            $response = [
                "status" => "success",
                "message" => "product notification sent successfully"
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "product notification sent failed"
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
