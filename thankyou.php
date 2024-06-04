<?php

/*******w******** 
    
    Name: Treasure Onah
    Date: 2024-05-27
    Description: Assignment 2 Server Side Validation

****************/



function order_summary(){}
$items = [
    ['index' => 1, 'name' => 'MacBook', 'price' => 1899.99, 'quantity' => 0],
    ['index' => 2, 'name' => 'Razer Gaming Mouse', 'price' => 79.99, 'quantity' => 0],
    ['index' => 3, 'name' => 'Portable Hard Drive', 'price' => 179.99, 'quantity' => 0],
    ['index' => 4, 'name' => 'Google Nexus 7', 'price' => 249.99, 'quantity' => 0],
    ['index' => 5, 'name' => 'Footpedal', 'price' => 119.99, 'quantity' => 0]
];

function validate_input() {
    $error = false;

    // // email validation
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $error = true;
    }


    // // Canadian postal code validation
    $postalCode = filter_input(INPUT_POST, 'postal', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[ABCEGHJ-NPRSTVXY][0-9][ABCEGHJ-NPRSTV-Z][ ]?[0-9][ABCEGHJ-NPRSTV-Z][0-9]$/')));     
    if (!$postalCode) {   
        $error = true;     
    }

    // // Credit Card number (checking if it is numeric and of valid length)
    $cardNumber = filter_input(INPUT_POST, 'cardnumber', FILTER_SANITIZE_NUMBER_INT);
    if (!$cardNumber) {
        $error = true;
    }

    // // Credit Card month
    $card_month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_NUMBER_INT);
    if ($card_month < 1 || $card_month > 12) {
        $error = true;
    }

    // // Credit card year
    $card_year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
    $currentYear = date('Y');
    if ($card_year < $currentYear || $card_year > $currentYear + 5) {
        $error = true;
    }

    // Card Type
    $cardType = isset($_POST['cardtype']);
    if(!$cardType) {
        $error = true;
    }

    // Card info
    $requiredFields = array('fullname', 'address', 'city', 'province', 'cardname');
    foreach ($requiredFields as $requiredField) {
        if (empty($_POST[$requiredField])){
            $error = true;
        }
    }

    // Province
    $province = $_POST['province'] ?? null;
    $abbrProvinces = ['AB', 'BC', 'MB', 'NB', 'NL', 'NS', 'ON', 'PE', 'QC', 'SK', 'NT', 'NU', 'YT'];
    if (!in_array($province, $abbrProvinces)) {
        $error = true;
    }

    // Quantities
    $quantities = ['qty1', 'qty2', 'qty3', 'qty4', 'qty5'];
    foreach ($quantities as $field) {
      $quantity = filter_input(INPUT_POST, $field, FILTER_VALIDATE_INT);
        if ($quantity === false && $_POST[$field] !== '') {
            $error = true;
        }
    }

    return $error;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Thanks for your order!</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <?php if(!validate_input()):?>
    <div class="invoice">
  <h2>Thanks for your order <?= $_POST['fullname'] ?></h2>
  <h3>Here's a summary of your order:</h3>
  <table>
    <tbody>
    <tr>
      <td colspan="4"><h3>Address Information</h3>
      </td>
    </tr>
    <tr>
      <td class="alignright"><span class="bold">Address:</span>
      </td>
      <td> <?= $_POST['address'] ?> </td>
      <td class="alignright"><span class="bold">City:</span>
      </td>
      <td> <?= $_POST['city'] ?> </td>
    </tr>
    <tr>
      <td class="alignright"><span class="bold">Province:</span>
      </td>
      <td> <?= $_POST['province'] ?></td>
      <td class="alignright"><span class="bold">Postal Code:</span>
      </td>
      <td> <?= $_POST['postal'] ?> </td>
    </tr>
    <tr>
      <td colspan="2" class="alignright"><span class="bold">Email:</span>
      </td>
      <td colspan="2"> <?= $_POST['email'] ?> </td>
    </tr>
    </tbody>
  </table>
  
  <table>
    <tbody>
    <?php 
    for($i = 1; $i <= 5; $i++){
        $quantity = $_POST["qty$i"];
        if($quantity > 0) {
            $description = $items[$i - 1]["name"];
            $cost = $items[$i - 1]["price"] * $quantity;

            $output = "<tr>
                        <td>$quantity</td>
                        <td>$description</td>
                        <td>$cost</td>
                    </tr>";
            echo $output;
        }
    }
?>
    </tbody>
  </table>
  </div>
    <?php else:?>
        <h2> Error</h2>
    <?php endif ?>
</body>
</html>