
<html>
    <head>
        <title>New Property</title>
    </head>

    <body>
        <a href="seller_home.php">
            <button type="button">Back</button>  
        </a>   
        
        <header>
            <h1>Enter New Property Information:</h1>
        </header>
        
        <h2>Office Building</h2>
        <form method="POST" action="new_property.php"> <!-- adds new office entry CHANGE TO manage_property.php-->
            <input type="hidden" id="insertOfficeBuilding" name="OfficeBuilding">

            <label for="propertyID">Unique Property ID: </label>
            <input type="text" id="propertyIDOB" name = "propertyIDOB"><br>

            <label for="propertyAddress">Address:</label>
            <input type="text" id="propertyAddressOB" name = "propertyAddressOB"><br>

            <label for="sqFt">Square Feet:</label>
            <input type="text" id="sqFtOB" name = "sqFtOB"><br>

            <label for="sellerID">SellerID:</label>
            <input type="text" id="sellerIDOB" name = "sellerIDOB"><br>

            <label for="sellPrice">Sell Price:</label>
            <input type="text" id="sellPriceOB" name = "sellPriceOB"><br>
            
            <label for="reservationprice">Reservation Price:</label>
            <input type="text" id="reservationpriceOB" name = "reservationpriceOB">
            <label for="reservationprice">Changes last set reservation price!</label><br>

            <label for="numFloors">Number of Floors:</label>
            <input type="text" id="numFloors" name = "numFloors"><br>

            <input type="submit" id = "submitOB" value="Insert Office Building">

        </form>

        <h2>House</h2>
        <form name="houseinput" method="POST" action="new_property.php"> <!-- adds new house entry-->
            <input type="hidden" id="insertHouse" name="House">

            <label for="propertyID">Unique Property ID: </label>
            <input type="text" id="propertyIDH" name = "propertyIDH"><br>

            <label for="propertyAddress">Address:</label>
            <input type="text" id="propertyAddressH" name = "propertyAddressH"><br>

            <label for="sqFt">Square Feet:</label>
            <input type="text" id="sqFtH" name = "sqFtH"><br>

            <label for="sellerID">SellerID:</label>
            <input type="text" id="sellerIDH" name = "sellerIDH"><br>

            <label for="sellPrice">Sell Price:</label>
            <input type="text" id="sellPriceH" name = "sellPriceH"><br>
            
            <label for="reservationprice">Reservation Price:</label>
            <input type="text" id="reservationpriceH" name = "reservationpriceH">
            <label for="reservationprice">Changes last set reservation price!</label><br>

            <label for="numBed">Number of Bedrooms:</label>
            <input type="text" id="numBed" name = "numBed"><br>

            <label for="numBath">Number of Bathrooms:</label>
            <input type="text" id="numBath" name = "numBath"><br>

            <input type="submit" id = "submitH" value="Insert House">

        </form>

        <h2>Apartment</h2>
        <form name="apartmentinput" method="POST" action="new_property.php"> <!-- adds new apartment entry-->
            <input type="hidden" id="insertApartment" name="Apartment">

            <label for="propertyID">Unique Property ID: </label>
            <input type="text" id="propertyIDA" name = "propertyIDA"><br>

            <label for="propertyAddress">Address:</label>
            <input type="text" id="propertyAddressA" name = "propertyAddressA"><br>

            <label for="sqFt">Square Feet:</label>
            <input type="text" id="sqFtA" name = "sqFtA"><br>

            <label for="sellerID">SellerID:</label>
            <input type="text" id="sellerIDA" name = "sellerIDA"><br>

            <label for="sellPrice">Sell Price:</label>
            <input type="text" id="sellPriceA" name = "sellPriceA"><br>
            
            <label for="reservationprice">Reservation Price:</label>
            <input type="text" id="reservationpriceA" name = "reservationpriceA">
            <label for="reservationprice">Changes last set reservation price!</label><br>

            <label for="numRooms">Number of Rooms:</label>
            <input type="text" id="numRooms" name = "numRooms"><br>

            <input type="submit" id = "submitA" value="Insert Apartment">

        </form>

        <form name="viewRP" method="POST" action="new_property.php">
            <input type="hidden" id="viewRP" name="viewRP">
            <b>View Market Reservation Prices</b>
            <input type="submit" id = "submitviewRP" value="view">
        </form>

    </body>


    <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function printResultProperty($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Property:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Address</th><th>sqFt</th><th>sellerID</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printResultSeller($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Seller:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Reservation Price</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printResultPP($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Property Price:<br>";
            echo "<table>";
            echo "<tr><th>Sq Ft</th><th>Sell Price</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printResultOB($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Office Building:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Number Of Floors</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printResultH($result) { //prints results from a select statement
            echo "<br>Retrieved data from table House:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th># Bedrooms</th><th># Bathrooms</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printResultA($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Apartment:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Number Of Rooms</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_hchliu", "a18633396", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function handleOfficeBuildingInsertRequest() {
            global $db_conn;
            
            handleReturnBackRequest();
            //Getting the values from user and insert data into the table
            $propertyID = $_POST['propertyIDOB'];
            $propertyAddress = $_POST['propertyAddressOB'];
            $sqFt = $_POST['sqFtOB'];
            $sellerID = $_POST['sellerIDOB'];
            $sellPrice = $_POST['sellPriceOB'];
            $reservationprice = $_POST['reservationpriceOB'];
            $numFloors = $_POST['numFloors'];

            $insert_Pquery = "insert into Property(propertyID, propertyAddress, sqft, contractNum, buyPrice, realtorID, buyerID, sellerID) values(%d, '%s', %d, NULL, NULL, NULL, NULL, %d)";
            $formatted_insert_Pquery = sprintf($insert_Pquery, $propertyID, $propertyAddress, $sqFt, $sellerID);
            executePlainSQL($formatted_insert_Pquery);
            $insert_PPquery = "insert into PropertyPrice(sqft, sellPrice) values(%d, %d)";
            $formatted_insert_PPquery = sprintf($insert_PPquery, $sqFt, $sellPrice);
            executePlainSQL($formatted_insert_PPquery);
            $insert_OBquery = "insert into OfficeBuilding(propertyID, numFloors) values(%d, %d)";
            $formatted_insert_OBquery = sprintf($insert_OBquery, $propertyID, $numFloors);
            executePlainSQL($formatted_insert_OBquery);
            executePlainSQL("UPDATE Seller SET reservationprice='$reservationprice' WHERE sellerID='$sellerID'");
            
            // $propertyout = executePlainSQL("select propertyID, propertyAddress, sqft, sellerID from Property");
            // printResultProperty($propertyout);
            // $propertypriceout = executePlainSQL("select sqft, sellPrice from PropertyPrice");
            // printResultPP($propertypriceout);
            // $sellerout = executePlainSQL("select sellerID, reservationprice from Seller");
            // printResultSeller($sellerout);
            // $officebuildingout = executePlainSQL("select propertyID, numFloors from OfficeBuilding");
            // printResultOB($officebuildingout);
            OCICommit($db_conn);
            disconnectFromDB();
        }

        function handleHouseInsertRequest() {
            global $db_conn;
            
            handleReturnBackRequest();

            //Getting the values from user and insert data into the table
            $propertyID = $_POST['propertyIDH'];
            $propertyAddress = $_POST['propertyAddressH'];
            $sqFt = $_POST['sqFtH'];
            $sellerID = $_POST['sellerIDH'];
            $sellPrice = $_POST['sellPriceH'];
            $reservationprice = $_POST['reservationpriceH'];
            $numBed = $_POST['numBed'];
            $numBath =$_POST['numBath'];


            $insert_Pquery = "insert into Property(propertyID, propertyAddress, sqft, contractNum, buyPrice, realtorID, buyerID, sellerID) values(%d, '%s', %d, NULL, NULL, NULL, NULL, %d)";
            $formatted_insert_Pquery = sprintf($insert_Pquery, $propertyID, $propertyAddress, $sqFt, $sellerID);
            executePlainSQL($formatted_insert_Pquery);
            $insert_PPquery = "insert into PropertyPrice(sqft, sellPrice) values(%d, %d)";
            $formatted_insert_PPquery = sprintf($insert_PPquery, $sqFt, $sellPrice);
            executePlainSQL($formatted_insert_PPquery);
            $insert_Hquery = "insert into House(propertyID, numBed, numBath) values(%d, %d, %d)";
            $formatted_insert_Hquery = sprintf($insert_Hquery, $propertyID, $numBed, $numBath);
            executePlainSQL($formatted_insert_Hquery);
            executePlainSQL("UPDATE Seller SET reservationprice='$reservationprice' WHERE sellerID='$sellerID'");
            
            // $propertyout = executePlainSQL("select propertyID, propertyAddress, sqft, sellerID from Property");
            // printResultProperty($propertyout);
            // $propertypriceout = executePlainSQL("select sqft, sellPrice from PropertyPrice");
            // printResultPP($propertypriceout);
            // $sellerout = executePlainSQL("select sellerID, reservationprice from Seller");
            // printResultSeller($sellerout);
            // $houseout = executePlainSQL("select propertyID, numBed, numBath from House");
            // printResultH($houseout);
            OCICommit($db_conn);
            disconnectFromDB();
        }

        function handleApartmentInsertRequest() {
            global $db_conn;
            
            handleReturnBackRequest();

            //Getting the values from user and insert data into the table
            $propertyID = $_POST['propertyIDA'];
            $propertyAddress = $_POST['propertyAddressA'];
            $sqFt = $_POST['sqFtA'];
            $sellerID = $_POST['sellerIDA'];
            $sellPrice = $_POST['sellPriceA'];
            $reservationprice = $_POST['reservationpriceA'];
            $numRooms = $_POST['numRooms'];

            $insert_Pquery = "insert into Property(propertyID, propertyAddress, sqft, contractNum, buyPrice, realtorID, buyerID, sellerID) values(%d, '%s', %d, NULL, NULL, NULL, NULL, %d)";
            $formatted_insert_Pquery = sprintf($insert_Pquery, $propertyID, $propertyAddress, $sqFt, $sellerID);
            executePlainSQL($formatted_insert_Pquery);
            $insert_PPquery = "insert into PropertyPrice(sqft, sellPrice) values(%d, %d)";
            $formatted_insert_PPquery = sprintf($insert_PPquery, $sqFt, $sellPrice);
            executePlainSQL($formatted_insert_PPquery);
            $insert_Aquery = "insert into Apartment(propertyID, numRooms) values(%d, %d)";
            $formatted_insert_Aquery = sprintf($insert_Aquery, $propertyID, $numRooms);
            executePlainSQL($formatted_insert_Aquery);
            executePlainSQL("UPDATE Seller SET reservationprice='$reservationprice' WHERE sellerID='$sellerID'");
            
            // $propertyout = executePlainSQL("select propertyID, propertyAddress, sqft, sellerID from Property");
            // printResultProperty($propertyout);
            // $propertypriceout = executePlainSQL("select sqft, sellPrice from PropertyPrice");
            // printResultPP($propertypriceout);
            // $sellerout = executePlainSQL("select sellerID, reservationprice from Seller");
            // printResultSeller($sellerout);
            // $apartmentout = executePlainSQL("select propertyID, numRooms from Apartment");
            // printResultA($apartmentout);
            OCICommit($db_conn);
            disconnectFromDB();
        }

        function handleViewRequest() {
            global $db_conn;

            $sellerout = executePlainSQL("select sellerID, reservationprice from Seller");
            printResultSeller($sellerout);
            OCICommit($db_conn);
            disconnectFromDB();
        }

        function handleReturnBackRequest() {
            echo '<form name="view" method="POST" action="manage_property.php">
            <input type="hidden" id="view" name="view">
            <b>Go Back to View All Properties </b>
            <input type="submit" id = "submitview" value="view">
        </form>';
        }

        if (connectToDB()) {
            if (array_key_exists('OfficeBuilding', $_POST)) {
                handleOfficeBuildingInsertRequest();
            } else if (array_key_exists('House', $_POST)) {
                handleHouseInsertRequest();
            } else if (array_key_exists('Apartment', $_POST)) {
                handleApartmentInsertRequest();
            } else if (array_key_exists('viewRP', $_POST)) {
                handleViewRequest();
            }
            
        }

        disconnectFromDB();
    ?>

</html>
