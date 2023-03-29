<?php
include_once("db_connect.php");
include_once("response.php");

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  /* .container{
    margin-top: 50px;
    display: flex;
    justify-content: center;
  } */
  
  .form-group {
            width: 400px;
        }

        label,
        select {
            padding: 5px;
        }

        select {
            appearance: none;
            -moz-appearance: none;
            -webkit-appearance: none;
            border: none;
            background: url('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-arrow-down-512.png') no-repeat right center;
            background-size: 20px;
            padding: 10px;
            font-size: 16px;
            font-family: 'Roboto', sans-serif;
            font-weight: 300;
            color: #666;
            margin-bottom: 20px;
            box-shadow: none;
        }

        select:focus {
            outline: none;
        }

        select option {
            background-color: #F3F3F3;
            font-size: 16px;
            font-family: 'Roboto', sans-serif;
            font-weight: 300;
            color: #666;
        }

        select option:checked {
            background-color: #999;
            color: #fff;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        #textbox {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            background-color: #f8f8f8;
            font-size: 16px;
            resize: none;
        }

        #textbox:focus {
            outline: none;
            border: 2px solid #2ecc71;
        }

        #textbox::placeholder {
            color: #bbb;
        }
    
  </style>
    <title>Dropdown</title>
</head>

    <body>
    <div class="container col-md-4">
        <div class="form-group py-2">
            <label for="country">Academics</label>
            <select class="form-select" id="country">
                <option value="">Select Academics</option>
                <?php
                    $query = "select * from country";
                    $result = $con->query($query);
                    if ($result->num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group py-2">
            <label for="academicdep">Academics Department</label>
            <select class="form-select" id="academicdep">
                <option value="">Select Department</option>
            </select>
        </div>
        <div class="form-group py-2">
            <label for="state">Request For</label>
            <select class="form-select" id="state">
                <option value="">Select Request</option>
            </select>
        </div>
        <div class="form-group py-2">
            <label for="city">Expense category</label>
            <select class="form-select" id="city">
                <option value="">Select category</option>
            </select>
        </div>

        <!-- New fields will be added here -->
        <div id="dynamic-fields"></div>

        <div class="form-group py-2">
            <!-- Add an "Add More" button -->
            <button type="button" class="btn btn-primary" onclick="addMore()">Add More</button>
        </div>

        <form method="POST" action="manage_person.php">
            <button id="next-option" class="btn btn-primary" onclick="getNextAction()">Next</button>
        </form>
    </div>

    

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function getNextAction() {
    var country = document.getElementById("country").value;
    var academicdep = document.getElementById("academicdep").value;
 
    var state = document.getElementById("state").value;
    var city = document.getElementById("city").value;
    var textbox = document.getElementById("textbox").value;

    // Execute the existing PHP code using an AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "manage_person.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Display the next options based on the response from the PHP code
                document.getElementById("next-options").innerHTML = xhr.responseText;
            } else {
                console.error(xhr.statusText);
            }
        }
    };
    xhr.send("country=" + country + "&academicdep=" + academicdep + "&state=" + state + "&city=" + city + "&textbox=" + textbox);
}

var counter = 1;

  function addMore() {
    // Create a unique ID for the new set of fields
    var id = "dynamic-fields-" + counter;

    // Get the HTML code for the new set of fields, including a "Remove" button
    var newFields = '<div id="' + id + '"><div class="form-group py-2"><label for="city">Expense category</label><select class="form-select" id="city" name="category' + counter + '"><option value="">Select category</option></select></div><div class="form-group py-2"><label for="sum">Sum</label><input type="text" class="form-control" id="sum" name="sum' + counter + '" placeholder="Enter sum"></div><button type="button" class="btn btn-danger" onclick="removeFields(\'' + id + '\')">Remove</button></div>';

    // Add the new fields to the dynamic-fields div
    $('#dynamic-fields').append(newFields);

    // Increment the counter
    counter++;
  }

  function removeFields(id) {
    // Remove the set of fields with the given ID
    $('#' + id).remove();
  }
        $(document).ready(function() {
            $("#country").on('change', function() {
                var countryid = $(this).val();

                $.ajax({
                    method: "POST",
                    url: "response.php",
                    data: {
                        id: countryid
                    },
                    datatype: "html",
                    success: function(data) {
                        $("#academicdep").html(data);
                        $("#state").html('<option value="">Select Request</option');
                    }
                });
            });

            $("#academicdep").on('change', function() {
                var academicdepid = $(this).val();
                $.ajax({
                    method: "POST",
                    url: "response.php",
                    data: {
                        sid: academicdepid
                    },
                    datatype: "html",
                    success: function(data) {
                        $("#state").html(data);
                        $("#city").html('<option value="">Select Expense</option');

                    }

                });
            });

            $("#state").on('change', function() {
                var stateid = $(this).val();
                $.ajax({
                    method: "POST",
                    url: "response.php",
                    data: {
                        sid: stateid
                

                    },
                    datatype: "html",
                    success: function(data) {
                        $("#city").html(data);

                    }

                });
            });
        
        });
        
    </script>

</body>

</html>

