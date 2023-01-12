<html>
    <head>
        <title>Edit Property</title>
    </head>

    <?php
        connectToDB();
        global $db_conn;
        $format = '%d';
        $formatS = '%s';
        $pID = $_POST['propertyID'];
        $propertyAddress = "SELECT propertyAddress FROM Property WHERE propertyID ='$pID'";
        $propertyAddress = executePlainSQL($propertyAddress);
        $propertyAddress = printResult($propertyAddress);
        $sqFt = "SELECT sqFt FROM Property WHERE propertyID ='$pID'";
        $sqFt = executePlainSQL($sqFt);
        $sqFt = printResult($sqFt);
        $numBed = "SELECT numBed FROM House WHERE propertyID ='$pID'";
        $numBed = executePlainSQL($numBed);
        $numBed = printResult($numBed);
        $numBath = "SELECT numBath FROM House WHERE propertyID ='$pID'";
        $numBath = executePlainSQL($numBath);
        $numBath = printResult($numBath);
        $contractNum = "SELECT contractNum FROM Property WHERE propertyID ='$pID'";
        $contractNum = executePlainSQL($contractNum);
        $contractNum = printResult($contractNum);
        $buyPrice = "SELECT buyPrice FROM Property WHERE propertyID ='$pID'";
        $buyPrice = executePlainSQL($buyPrice);
        $buyPrice = printResult($buyPrice);
        $realtorID = "SELECT realtorID FROM Property WHERE propertyID ='$pID'";
        $realtorID = executePlainSQL($realtorID);
        $realtorID = printResult($realtorID);
        $buyerID = "SELECT buyerID FROM Property WHERE propertyID ='$pID'";
        $buyerID = executePlainSQL($buyerID);
        $buyerID = printResult($buyerID);
        $sellerID = "SELECT sellerID FROM Property WHERE propertyID ='$pID'";
        $sellerID = executePlainSQL($sellerID);
        $sellerID = printResult($sellerID);
    ?>

    <body>
        <a href="edit_property.php">
            <button type="button">Back</button>  
        </a>   
        
        <header>
            <h1 >Edit Property Information:</h1>
        </header>
        
        <h2 >House</h2>
        <form method="POST"  action="edit_propertyH.php"> <!-- adds new office entry CHANGE TO manage_property.php-->
            <input type="hidden" id="alterHouse" name="House">

            <label for="propertyID">Property ID: </label>
            <input type="text" id="propertyIDH" name = "propertyIDH" value = "<?php echo $pID; ?>"><br>

            <label for="propertyAddress">Address:</label>
            <input type="text" id="propertyAddressH" name = "propertyAddressH" value = "<?php echo ($propertyAddress); ?>">
            <label for="propertyAddress">(Delete empty spaces behind address)</label><br>

            <label for="sqFt">Square Feet:</label>
            <input type="text" id="sqFtH" name = "sqFtH" value = "<?php echo ($sqFt); ?>"><br>

            <label for="numBed">Number of Bedrooms:</label>
            <input type="text" id="numBed" name = "numBed" value = "<?php echo ($numBed); ?>"><br>

            <label for="numBath">Number of Bathrooms:</label>
            <input type="text" id="numBath" name = "numBath" value = "<?php echo ($numBath); ?>"><br>

            <label for="contractNum">Contract Number:</label>
            <input type="text" id="contractNumH" name = "contractNumH" value = "<?php echo ($contractNum); ?>"><br>

            <label for="buyPrice">Purchase Price:</label>
            <input type="text" id="buyPriceH" name = "buyPriceH" value = "<?php echo ($buyPrice); ?>"><br>

            <label for="realtorID">Realtor ID:</label>
            <input type="text" id="realtorIDH" name = "realtorIDH" value = "<?php echo ($realtorID); ?>"><br>
            
            <label for="buyerID">Buyer ID:</label>
            <input type="text" id="buyerIDH" name = "buyerIDH" value = "<?php echo ($buyerID); ?>"><br>

            <label for="sellerID">Seller ID:</label>
            <input type="text" id="sellerIDH" name = "sellerIDH" value = "<?php echo ($sellerID); ?>"><br>

            <input type="submit" id = "submitH" value="Edit House">

        </form>

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

        function printResult($result) { //prints results from a select statement

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                return $row[0];
            }
            

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

        function handleHouseUpdateRequest() {
            global $db_conn;
            //Getting the values from user and update data into the table
            $propertyID = $_POST['propertyIDH'];
            $propertyAddress = $_POST['propertyAddressH'];
            $sqFt = $_POST['sqFtH'];
            $numBed = $_POST['numBed'];
            $numBath = $_POST['numBath'];
            $contractNum = $_POST['contractNumH'];
            $buyPrice = $_POST['buyPriceH'];
            $realtorID = $_POST['realtorIDH'];
            $buyerID = $_POST['buyerIDH'];
            $sellerID = $_POST['sellerIDH'];

            executePlainSQL("UPDATE Property SET propertyAddress='$propertyAddress', sqFt='$sqFt', 
            contractNum='$contractNum', buyPrice='$buyPrice', realtorID='$realtorID', 
            buyerID='$buyerID', sellerID='$sellerID'
            WHERE propertyID='$propertyID'");

            executePlainSQL("UPDATE House SET numBed='$numBed', numBath='$numBath' WHERE propertyID='$propertyID'");
            OCICommit($db_conn);
            disconnectFromDB();

            handleReturnBackRequest();
        }

        function handleReturnBackRequest() {
            echo '<form name="view" method="POST" action="manage_property.php">
            <input type="hidden" id="view" name="view">
            <b>Go Back to View All Properties </b>
            <input type="submit" id = "submitview" value="view">
        </form>';
        }
        

        if (connectToDB()) {
            if (array_key_exists('House', $_POST)) {
                handleHouseUpdateRequest();
            }
        }

        disconnectFromDB();
    ?>

</html>
